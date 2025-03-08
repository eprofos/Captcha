<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Tests\Storage;

use Eprofos\Captcha\Storage\StorageInterface;

/**
 * Implementation of StorageInterface for tests that uses an in-memory array
 * instead of PHP sessions.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class ArrayStorage implements StorageInterface
{
    /**
     * Stored data.
     *
     * @var array<string, string>
     */
    private array $data = [];

    /**
     * Key used to store the code.
     *
     * @var string
     */
    private string $key;

    /**
     * Constructor.
     *
     * @param string $key Key used to store the code
     */
    public function __construct(string $key = 'captcha_code')
    {
        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function store(string $code): void
    {
        $this->data[$this->key] = $code;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve(): ?string
    {
        return $this->data[$this->key] ?? null;
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
        if (isset($this->data[$this->key])) {
            unset($this->data[$this->key]);
        }
    }
}
