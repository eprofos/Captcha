<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Config;

/**
 * Class for managing captcha color schemes.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class ColorScheme
{
    /**
     * Text color (hexadecimal format or color name).
     *
     * @var string
     */
    private string $textColor;

    /**
     * Background color (hexadecimal format or color name).
     *
     * @var string
     */
    private string $backgroundColor;

    /**
     * Noise color (hexadecimal format or color name).
     *
     * @var string
     */
    private string $noiseColor;

    /**
     * Line color (hexadecimal format or color name).
     *
     * @var string
     */
    private string $lineColor;

    /**
     * Indicates if gradient should be used for text.
     *
     * @var bool
     */
    private bool $useGradient;

    /**
     * Gradient end color (hexadecimal format or color name).
     *
     * @var string|null
     */
    private ?string $gradientEndColor;

    /**
     * Constructor.
     *
     * @param string $textColor Text color
     * @param string $backgroundColor Background color
     * @param string|null $noiseColor Noise color (if null, uses text color)
     * @param string|null $lineColor Line color (if null, uses text color)
     * @param bool $useGradient Use gradient for text
     * @param string|null $gradientEndColor Gradient end color
     */
    public function __construct(
        string $textColor = '#000000',
        string $backgroundColor = '#FFFFFF',
        ?string $noiseColor = null,
        ?string $lineColor = null,
        bool $useGradient = false,
        ?string $gradientEndColor = null
    ) {
        $this->textColor = $textColor;
        $this->backgroundColor = $backgroundColor;
        $this->noiseColor = $noiseColor ?? $textColor;
        $this->lineColor = $lineColor ?? $textColor;
        $this->useGradient = $useGradient;
        $this->gradientEndColor = $gradientEndColor;
    }

    /**
     * Converts a hexadecimal color to RGB components.
     *
     * @param string $hexColor Color in hexadecimal format (#RRGGBB)
     * @return array{r: int, g: int, b: int} Associative array with r, g, b components
     */
    public static function hexToRgb(string $hexColor): array
    {
        // Remove # if present
        $hexColor = ltrim($hexColor, '#');

        // Convert to RGB
        if (strlen($hexColor) === 3) {
            $r = (int) hexdec(str_repeat(substr($hexColor, 0, 1), 2));
            $g = (int) hexdec(str_repeat(substr($hexColor, 1, 1), 2));
            $b = (int) hexdec(str_repeat(substr($hexColor, 2, 1), 2));
        } else {
            $r = (int) hexdec(substr($hexColor, 0, 2));
            $g = (int) hexdec(substr($hexColor, 2, 2));
            $b = (int) hexdec(substr($hexColor, 4, 2));
        }

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

    /**
     * Gets the text color.
     *
     * @return string
     */
    public function getTextColor(): string
    {
        return $this->textColor;
    }

    /**
     * Sets the text color.
     *
     * @param string $textColor
     * @return self
     */
    public function setTextColor(string $textColor): self
    {
        $this->textColor = $textColor;

        return $this;
    }

    /**
     * Gets the background color.
     *
     * @return string
     */
    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    /**
     * Sets the background color.
     *
     * @param string $backgroundColor
     * @return self
     */
    public function setBackgroundColor(string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * Gets the noise color.
     *
     * @return string
     */
    public function getNoiseColor(): string
    {
        return $this->noiseColor;
    }

    /**
     * Sets the noise color.
     *
     * @param string $noiseColor
     * @return self
     */
    public function setNoiseColor(string $noiseColor): self
    {
        $this->noiseColor = $noiseColor;

        return $this;
    }

    /**
     * Gets the line color.
     *
     * @return string
     */
    public function getLineColor(): string
    {
        return $this->lineColor;
    }

    /**
     * Sets the line color.
     *
     * @param string $lineColor
     * @return self
     */
    public function setLineColor(string $lineColor): self
    {
        $this->lineColor = $lineColor;

        return $this;
    }

    /**
     * Checks if gradient should be used.
     *
     * @return bool
     */
    public function useGradient(): bool
    {
        return $this->useGradient;
    }

    /**
     * Sets if gradient should be used.
     *
     * @param bool $useGradient
     * @return self
     */
    public function setUseGradient(bool $useGradient): self
    {
        $this->useGradient = $useGradient;

        return $this;
    }

    /**
     * Gets the gradient end color.
     *
     * @return string|null
     */
    public function getGradientEndColor(): ?string
    {
        return $this->gradientEndColor;
    }

    /**
     * Sets the gradient end color.
     *
     * @param string|null $gradientEndColor
     * @return self
     */
    public function setGradientEndColor(?string $gradientEndColor): self
    {
        $this->gradientEndColor = $gradientEndColor;

        return $this;
    }
}
