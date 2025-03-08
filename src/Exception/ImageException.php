<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Exception;

/**
 * Exception thrown when there is a problem with images.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class ImageException extends CaptchaException
{
    /**
     * Creates an exception for image creation error.
     *
     * @param string $reason The reason for failure
     * @return self
     */
    public static function creationFailed(string $reason): self
    {
        return new self(sprintf('Unable to create image: %s', $reason));
    }

    /**
     * Creates an exception for image save error.
     *
     * @param string $path The path where the image should be saved
     * @return self
     */
    public static function saveFailed(string $path): self
    {
        return new self(sprintf('Unable to save image to "%s".', $path));
    }

    /**
     * Creates an exception for missing GD extension.
     *
     * @return self
     */
    public static function gdNotAvailable(): self
    {
        return new self('The GD extension is not available. It is required to generate captchas.');
    }

    /**
     * Creates an exception for invalid image dimensions.
     *
     * @param int $width The width
     * @param int $height The height
     * @return self
     */
    public static function invalidDimensions(int $width, int $height): self
    {
        return new self(sprintf('Invalid image dimensions: %dx%d. Dimensions must be positive.', $width, $height));
    }
}
