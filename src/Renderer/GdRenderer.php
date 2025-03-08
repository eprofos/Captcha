<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Renderer;

use Eprofos\Captcha\Config\CaptchaConfig;
use Eprofos\Captcha\Config\ColorScheme;
use Eprofos\Captcha\Exception\FontException;
use Eprofos\Captcha\Exception\ImageException;

/**
 * Captcha renderer using the GD extension.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class GdRenderer implements RendererInterface
{
    /**
     * GD image.
     *
     * @var \GdImage|null
     */
    private $image = null;

    /**
     * Constructor.
     *
     * @throws ImageException If GD extension is not available
     */
    public function __construct()
    {
        if (! extension_loaded('gd')) {
            throw ImageException::gdNotAvailable();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function render(string $code, CaptchaConfig $config): mixed
    {
        // Check if font exists
        if (! file_exists($config->getFont())) {
            throw FontException::fontNotFound($config->getFont());
        }

        // Create image
        $width = $config->getWidth();
        $height = $config->getHeight();

        $this->image = imagecreatetruecolor($width, $height);

        if ($this->image === false) {
            throw ImageException::creationFailed('Unable to create image');
        }

        // Enable antialiasing
        imageantialias($this->image, true);

        // Set colors
        $colorScheme = $config->getColorScheme();
        $backgroundColor = $this->allocateColor($colorScheme->getBackgroundColor());
        $textColor = $this->allocateColor($colorScheme->getTextColor());
        $noiseColor = $this->allocateColor($colorScheme->getNoiseColor());
        $lineColor = $this->allocateColor($colorScheme->getLineColor());

        // Fill background
        imagefill($this->image, 0, 0, $backgroundColor);

        // Add background noise if enabled
        if ($config->hasBackgroundNoise()) {
            $this->addNoise($config, $noiseColor, $lineColor);
        }

        // Calculate character spacing
        $length = mb_strlen($code);
        $spacing = $width / ($length + 1);

        // Draw each character
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($code, $i, 1);
            $x = ($i + 1) * $spacing - ($spacing / 2);
            $y = $height / 2 + ($height / 4);

            // Random rotation
            $rotation = rand(-$config->getMaxRotation(), $config->getMaxRotation());

            // Random font size (slight variation)
            $fontSize = $config->getFontSize() + rand(-2, 2);

            // Draw the character
            $this->drawCharacter($char, $x, $y, $fontSize, $rotation, $textColor, $config->getFont());
        }

        // Apply wave distortion if enabled
        if ($config->hasWaveDistortion()) {
            $this->applyWaveDistortion($config);
        }

        return $this->image;
    }

    /**
     * {@inheritdoc}
     */
    public function output(): void
    {
        if ($this->image === null) {
            throw new ImageException('No image has been generated. Call render() first.');
        }

        // Send headers
        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        // Ensure image is of type GdImage
        assert($this->image instanceof \GdImage);

        // Send image
        imagepng($this->image);
        imagedestroy($this->image);
    }

    /**
     * {@inheritdoc}
     */
    public function save(string $path): bool
    {
        if ($this->image === null) {
            throw new ImageException('No image has been generated. Call render() first.');
        }

        // Determine format based on extension
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Ensure image is of type GdImage
        assert($this->image instanceof \GdImage);

        $result = match ($extension) {
            'jpg', 'jpeg' => imagejpeg($this->image, $path, 90),
            'gif' => imagegif($this->image, $path),
            'webp' => imagewebp($this->image, $path),
            default => imagepng($this->image, $path, 9),
        };

        if (! $result) {
            throw ImageException::saveFailed($path);
        }

        return true;
    }

    /**
     * Allocates a color from a hexadecimal string or color name.
     *
     * @param string $color Color in hexadecimal format (#RRGGBB) or color name
     * @return int Allocated color identifier
     * @throws ImageException If color allocation fails
     */
    private function allocateColor(string $color): int
    {
        if ($this->image === null) {
            throw new ImageException('No image has been created.');
        }

        $rgb = ColorScheme::hexToRgb($color);

        $colorId = imagecolorallocate($this->image, $rgb['r'], $rgb['g'], $rgb['b']);

        if ($colorId === false) {
            throw new ImageException('Unable to allocate color ' . $color);
        }

        return $colorId;
    }

    /**
     * Adds noise to the image (points and lines).
     *
     * @param CaptchaConfig $config Captcha configuration
     * @param int $noiseColor Noise color
     * @param int $lineColor Line color
     * @return void
     */
    private function addNoise(CaptchaConfig $config, int $noiseColor, int $lineColor): void
    {
        if ($this->image === null) {
            return;
        }

        // Ensure image is of type GdImage
        assert($this->image instanceof \GdImage);

        $width = $config->getWidth();
        $height = $config->getHeight();

        // Add noise points
        for ($i = 0; $i < $config->getNoisePoints(); $i++) {
            $x = rand(0, $width - 1);
            $y = rand(0, $height - 1);
            imagesetpixel($this->image, $x, $y, $noiseColor);
        }

        // Add noise lines
        for ($i = 0; $i < $config->getNoiseLines(); $i++) {
            $x1 = rand(0, $width - 1);
            $y1 = rand(0, $height - 1);
            $x2 = rand(0, $width - 1);
            $y2 = rand(0, $height - 1);
            imageline($this->image, $x1, $y1, $x2, $y2, $lineColor);
        }
    }

    /**
     * Draws a character on the image.
     *
     * @param string $char Character to draw
     * @param float $x X position
     * @param float $y Y position
     * @param int $fontSize Font size
     * @param float $rotation Rotation angle in degrees
     * @param int $color Text color
     * @param string $font Font path
     * @return void
     */
    private function drawCharacter(
        string $char,
        float $x,
        float $y,
        int $fontSize,
        float $rotation,
        int $color,
        string $font
    ): void {
        if ($this->image === null) {
            return;
        }

        // Ensure image is of type GdImage
        assert($this->image instanceof \GdImage);

        // Draw character with TTF font
        imagettftext(
            $this->image,
            $fontSize,
            $rotation,
            (int) $x,
            (int) $y,
            $color,
            $font,
            $char
        );
    }

    /**
     * Applies wave distortion to the image.
     *
     * @param CaptchaConfig $config Captcha configuration
     * @return void
     */
    private function applyWaveDistortion(CaptchaConfig $config): void
    {
        if ($this->image === null) {
            return;
        }

        // Ensure image is of type GdImage
        assert($this->image instanceof \GdImage);

        $width = $config->getWidth();
        $height = $config->getHeight();
        $amplitude = $config->getWaveAmplitude();
        $frequency = $config->getWaveFrequency();

        // Create temporary image
        $temp = imagecreatetruecolor($width, $height);
        if ($temp === false) {
            return;
        }

        // Ensure temporary image is of type GdImage
        assert($temp instanceof \GdImage);

        // Copy original image
        imagecopy($temp, $this->image, 0, 0, 0, 0, $width, $height);

        // Clear original image
        $backgroundColor = $this->allocateColor($config->getColorScheme()->getBackgroundColor());
        imagefill($this->image, 0, 0, $backgroundColor);

        // Apply distortion
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                // Horizontal distortion (sin)
                $xOffset = (int) ($amplitude * sin($y * $frequency));

                // Vertical distortion (cos)
                $yOffset = (int) ($amplitude * cos($x * $frequency));

                $newX = $x + $xOffset;
                $newY = $y + $yOffset;

                // Ensure coordinates are within bounds
                if ($newX >= 0 && $newX < $width && $newY >= 0 && $newY < $height) {
                    // Get pixel color from temporary image
                    $color = imagecolorat($temp, $newX, $newY);

                    // imagecolorat always returns an integer, but PHPStan doesn't know that
                    // Let's ensure $color is indeed an integer
                    assert(is_int($color));

                    // Set color in original image
                    imagesetpixel($this->image, $x, $y, $color);
                }
            }
        }

        // Free memory
        imagedestroy($temp);
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        // Free memory if image still exists
        if ($this->image !== null) {
            // Ensure image is of type GdImage
            assert($this->image instanceof \GdImage);
            imagedestroy($this->image);
            $this->image = null;
        }
    }
}
