<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Exception;

/**
 * Exception thrown when there is a problem with fonts.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class FontException extends CaptchaException
{
    /**
     * Creates an exception for a font that cannot be found.
     *
     * @param string $fontPath The font path
     * @return self
     */
    public static function fontNotFound(string $fontPath): self
    {
        return new self(sprintf('Font "%s" not found.', $fontPath));
    }

    /**
     * Creates an exception for an invalid font.
     *
     * @param string $fontPath The font path
     * @return self
     */
    public static function invalidFont(string $fontPath): self
    {
        return new self(sprintf('Font "%s" is invalid or corrupted.', $fontPath));
    }
}
