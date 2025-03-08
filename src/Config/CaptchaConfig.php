<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Config;

use function dirname;

use Eprofos\Captcha\Exception\FontException;
use Eprofos\Captcha\Exception\ImageException;

use function file_exists;
use function in_array;
use function str_contains;

/**
 * Configuration for captcha generation.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class CaptchaConfig
{
    /**
     * Low complexity level.
     */
    public const COMPLEXITY_LOW = 'low';

    /**
     * Medium complexity level.
     */
    public const COMPLEXITY_MEDIUM = 'medium';

    /**
     * High complexity level.
     */
    public const COMPLEXITY_HIGH = 'high';

    /**
     * Image width in pixels.
     *
     * @var int
     */
    private int $width = 200;

    /**
     * Image height in pixels.
     *
     * @var int
     */
    private int $height = 80;

    /**
     * Captcha code length.
     *
     * @var int
     */
    private int $length = 6;

    /**
     * Character set used to generate the code.
     *
     * @var string
     */
    private string $characterSet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz23456789';

    /**
     * Path to font file.
     *
     * @var string
     */
    private string $font;

    /**
     * Font size.
     *
     * @var int
     */
    private int $fontSize = 28;

    /**
     * Captcha complexity level.
     *
     * @var string
     */
    private string $complexity = self::COMPLEXITY_MEDIUM;

    /**
     * Indicates if background noise should be added.
     *
     * @var bool
     */
    private bool $backgroundNoise = true;

    /**
     * Number of noise points to add.
     *
     * @var int
     */
    private int $noisePoints = 100;

    /**
     * Number of noise lines to add.
     *
     * @var int
     */
    private int $noiseLines = 5;

    /**
     * Indicates if wave distortion should be applied.
     *
     * @var bool
     */
    private bool $waveDistortion = true;

    /**
     * Wave distortion amplitude.
     *
     * @var int
     */
    private int $waveAmplitude = 4;

    /**
     * Wave distortion frequency.
     *
     * @var float
     */
    private float $waveFrequency = 0.1;

    /**
     * Color scheme.
     *
     * @var ColorScheme
     */
    private ColorScheme $colorScheme;

    /**
     * Maximum character rotation angle (in degrees).
     *
     * @var int
     */
    private int $maxRotation = 8;

    /**
     * Indicates if captcha is case sensitive.
     *
     * @var bool
     */
    private bool $caseSensitive = true;

    /**
     * Constructor.
     */
    public function __construct()
    {
        // Set default font path
        $this->font = dirname(__DIR__, 2) . '/assets/fonts/open-sans.ttf';

        // Set default color scheme
        $this->colorScheme = new ColorScheme();
    }

    /**
     * Gets the image width.
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * Sets the image width.
     *
     * @param int $width
     * @return self
     * @throws ImageException If width is invalid
     */
    public function setWidth(int $width): self
    {
        if ($width <= 0) {
            throw ImageException::invalidDimensions($width, $this->height);
        }
        $this->width = $width;

        return $this;
    }

    /**
     * Gets the image height.
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Sets the image height.
     *
     * @param int $height
     * @return self
     * @throws ImageException If height is invalid
     */
    public function setHeight(int $height): self
    {
        if ($height <= 0) {
            throw ImageException::invalidDimensions($this->width, $height);
        }
        $this->height = $height;

        return $this;
    }

    /**
     * Gets the captcha code length.
     *
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Sets the captcha code length.
     *
     * @param int $length
     * @return self
     */
    public function setLength(int $length): self
    {
        if ($length < 1) {
            $length = 1;
        }
        $this->length = $length;

        return $this;
    }

    /**
     * Gets the character set.
     *
     * @return string
     */
    public function getCharacterSet(): string
    {
        return $this->characterSet;
    }

    /**
     * Sets the character set.
     *
     * @param string $characterSet
     * @return self
     */
    public function setCharacterSet(string $characterSet): self
    {
        if (empty($characterSet)) {
            $characterSet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz23456789';
        }
        $this->characterSet = $characterSet;

        return $this;
    }

    /**
     * Gets the font path.
     *
     * @return string
     */
    public function getFont(): string
    {
        return $this->font;
    }

    /**
     * Sets the font path.
     *
     * @param string $font
     * @return self
     * @throws FontException If font is not found
     */
    public function setFont(string $font): self
    {
        // If it's just a font name, look in the fonts directory
        if (! str_contains($font, '/') && ! str_contains($font, '\\')) {
            $fontPath = dirname(__DIR__, 2) . '/assets/fonts/' . $font;
            if (! file_exists($fontPath)) {
                throw FontException::fontNotFound($fontPath);
            }
            $this->font = $fontPath;
        } else {
            // Full path provided
            if (! file_exists($font)) {
                throw FontException::fontNotFound($font);
            }
            $this->font = $font;
        }

        return $this;
    }

    /**
     * Gets the font size.
     *
     * @return int
     */
    public function getFontSize(): int
    {
        return $this->fontSize;
    }

    /**
     * Sets the font size.
     *
     * @param int $fontSize
     * @return self
     */
    public function setFontSize(int $fontSize): self
    {
        if ($fontSize < 8) {
            $fontSize = 8;
        }
        $this->fontSize = $fontSize;

        return $this;
    }

    /**
     * Gets the complexity level.
     *
     * @return string
     */
    public function getComplexity(): string
    {
        return $this->complexity;
    }

    /**
     * Sets the complexity level.
     *
     * @param string $complexity
     * @return self
     */
    public function setComplexity(string $complexity): self
    {
        $validComplexities = [
            self::COMPLEXITY_LOW,
            self::COMPLEXITY_MEDIUM,
            self::COMPLEXITY_HIGH,
        ];

        if (! in_array($complexity, $validComplexities)) {
            $complexity = self::COMPLEXITY_MEDIUM;
        }

        $this->complexity = $complexity;

        // Adjust parameters based on complexity
        switch ($complexity) {
            case self::COMPLEXITY_LOW:
                $this->backgroundNoise = false;
                $this->waveDistortion = false;
                $this->noisePoints = 50;
                $this->noiseLines = 2;
                $this->maxRotation = 5;
                $this->characterSet = '23456789';
                $this->caseSensitive = false;

                break;

            case self::COMPLEXITY_MEDIUM:
                $this->backgroundNoise = true;
                $this->waveDistortion = true;
                $this->noisePoints = 100;
                $this->noiseLines = 5;
                $this->maxRotation = 8;
                $this->characterSet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                $this->caseSensitive = false;

                break;

            case self::COMPLEXITY_HIGH:
                $this->backgroundNoise = true;
                $this->waveDistortion = true;
                $this->noisePoints = 200;
                $this->noiseLines = 10;
                $this->maxRotation = 12;
                $this->characterSet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz23456789';
                $this->caseSensitive = true;

                break;
        }

        return $this;
    }

    /**
     * Checks if background noise should be added.
     *
     * @return bool
     */
    public function hasBackgroundNoise(): bool
    {
        return $this->backgroundNoise;
    }

    /**
     * Sets if background noise should be added.
     *
     * @param bool $backgroundNoise
     * @return self
     */
    public function setBackgroundNoise(bool $backgroundNoise): self
    {
        $this->backgroundNoise = $backgroundNoise;

        return $this;
    }

    /**
     * Gets the number of noise points.
     *
     * @return int
     */
    public function getNoisePoints(): int
    {
        return $this->noisePoints;
    }

    /**
     * Sets the number of noise points.
     *
     * @param int $noisePoints
     * @return self
     */
    public function setNoisePoints(int $noisePoints): self
    {
        if ($noisePoints < 0) {
            $noisePoints = 0;
        }
        $this->noisePoints = $noisePoints;

        return $this;
    }

    /**
     * Gets the number of noise lines.
     *
     * @return int
     */
    public function getNoiseLines(): int
    {
        return $this->noiseLines;
    }

    /**
     * Sets the number of noise lines.
     *
     * @param int $noiseLines
     * @return self
     */
    public function setNoiseLines(int $noiseLines): self
    {
        if ($noiseLines < 0) {
            $noiseLines = 0;
        }
        $this->noiseLines = $noiseLines;

        return $this;
    }

    /**
     * Checks if wave distortion should be applied.
     *
     * @return bool
     */
    public function hasWaveDistortion(): bool
    {
        return $this->waveDistortion;
    }

    /**
     * Sets if wave distortion should be applied.
     *
     * @param bool $waveDistortion
     * @return self
     */
    public function setWaveDistortion(bool $waveDistortion): self
    {
        $this->waveDistortion = $waveDistortion;

        return $this;
    }

    /**
     * Gets the wave distortion amplitude.
     *
     * @return int
     */
    public function getWaveAmplitude(): int
    {
        return $this->waveAmplitude;
    }

    /**
     * Sets the wave distortion amplitude.
     *
     * @param int $waveAmplitude
     * @return self
     */
    public function setWaveAmplitude(int $waveAmplitude): self
    {
        if ($waveAmplitude < 0) {
            $waveAmplitude = 0;
        }
        $this->waveAmplitude = $waveAmplitude;

        return $this;
    }

    /**
     * Gets the wave distortion frequency.
     *
     * @return float
     */
    public function getWaveFrequency(): float
    {
        return $this->waveFrequency;
    }

    /**
     * Sets the wave distortion frequency.
     *
     * @param float $waveFrequency
     * @return self
     */
    public function setWaveFrequency(float $waveFrequency): self
    {
        if ($waveFrequency <= 0) {
            $waveFrequency = 0.1;
        }
        $this->waveFrequency = $waveFrequency;

        return $this;
    }

    /**
     * Gets the color scheme.
     *
     * @return ColorScheme
     */
    public function getColorScheme(): ColorScheme
    {
        return $this->colorScheme;
    }

    /**
     * Sets the color scheme.
     *
     * @param ColorScheme $colorScheme
     * @return self
     */
    public function setColorScheme(ColorScheme $colorScheme): self
    {
        $this->colorScheme = $colorScheme;

        return $this;
    }

    /**
     * Gets the maximum rotation angle.
     *
     * @return int
     */
    public function getMaxRotation(): int
    {
        return $this->maxRotation;
    }

    /**
     * Sets the maximum rotation angle.
     *
     * @param int $maxRotation
     * @return self
     */
    public function setMaxRotation(int $maxRotation): self
    {
        if ($maxRotation < 0) {
            $maxRotation = 0;
        }
        $this->maxRotation = $maxRotation;

        return $this;
    }

    /**
     * Checks if captcha is case sensitive.
     *
     * @return bool
     */
    public function isCaseSensitive(): bool
    {
        return $this->caseSensitive;
    }

    /**
     * Sets if captcha is case sensitive.
     *
     * @param bool $caseSensitive
     * @return self
     */
    public function setCaseSensitive(bool $caseSensitive): self
    {
        $this->caseSensitive = $caseSensitive;

        return $this;
    }
}
