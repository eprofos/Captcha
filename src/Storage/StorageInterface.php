<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Storage;

/**
 * Interface for captcha code storage.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
interface StorageInterface
{
    /**
     * Stores the captcha code.
     *
     * @param string $code The captcha code to store
     * @return void
     */
    public function store(string $code): void;

    /**
     * Retrieves the stored captcha code.
     *
     * @return string|null The stored captcha code or null if it doesn't exist
     */
    public function retrieve(): ?string;

    /**
     * Verifies if the provided code matches the stored code.
     *
     * @param string $code The code to verify
     * @param bool $caseSensitive If verification should be case sensitive
     * @return bool True if code matches, false otherwise
     */
    public function verify(string $code, bool $caseSensitive = false): bool;

    /**
     * Removes the stored captcha code.
     *
     * @return void
     */
    public function remove(): void;
}
