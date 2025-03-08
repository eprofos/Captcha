<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Storage;

/**
 * Storage for captcha codes in PHP session.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class SessionStorage implements StorageInterface
{
    /**
     * Key used to store the code in session.
     *
     * @var string
     */
    private string $sessionKey;

    /**
     * Constructor.
     *
     * @param string $sessionKey Key used to store the code in session
     */
    public function __construct(string $sessionKey = 'eprofos_captcha_code')
    {
        $this->sessionKey = $sessionKey;

        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE && ! headers_sent()) {
            session_start();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function store(string $code): void
    {
        $_SESSION[$this->sessionKey] = $code;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve(): ?string
    {
        return $_SESSION[$this->sessionKey] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function verify(string $code, bool $caseSensitive = false): bool
    {
        $storedCode = $this->retrieve();

        if ($storedCode === null) {
            return false;
        }

        if ($caseSensitive) {
            return $code === $storedCode;
        }

        return strtolower($code) === strtolower($storedCode);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(): void
    {
        if (isset($_SESSION[$this->sessionKey])) {
            unset($_SESSION[$this->sessionKey]);
        }
    }
}
