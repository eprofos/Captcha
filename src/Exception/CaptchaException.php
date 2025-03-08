<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Exception;

use Exception;

/**
 * Base exception for all captcha-related exceptions.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class CaptchaException extends Exception
{
    /**
     * Creates a new instance of CaptchaException.
     *
     * @param string $message The error message
     * @param int $code The error code
     * @param \Throwable|null $previous The previous exception
     */
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
