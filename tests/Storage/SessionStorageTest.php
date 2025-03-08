<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Tests\Storage;

use PHPUnit\Framework\TestCase;

/**
 * Tests for StorageInterface implementations.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class SessionStorageTest extends TestCase
{
    /**
     * @var ArrayStorage
     */
    private ArrayStorage $storage;

    /**
     * @var string
     */
    private string $storageKey;

    /**
     * Setup before each test.
     */
    protected function setUp(): void
    {
        // Use unique key for tests
        $this->storageKey = 'test_captcha_' . uniqid();
        $this->storage = new ArrayStorage($this->storageKey);
    }

    /**
     * Tests storing and retrieving a code.
     */
    public function testStoreAndRetrieve(): void
    {
        $code = 'ABC123';

        $this->storage->store($code);
        $this->assertEquals($code, $this->storage->retrieve());
    }

    /**
     * Tests retrieving a non-existent code.
     */
    public function testRetrieveNonExistent(): void
    {
        $this->assertNull($this->storage->retrieve());
    }

    /**
     * Tests verifying a valid code (case insensitive).
     */
    public function testVerifyValidCodeCaseInsensitive(): void
    {
        $code = 'ABC123';

        $this->storage->store($code);
        $this->assertTrue($this->storage->verify('abc123', false));
    }

    /**
     * Tests verifying a valid code (case sensitive).
     */
    public function testVerifyValidCodeCaseSensitive(): void
    {
        $code = 'ABC123';

        $this->storage->store($code);
        $this->assertTrue($this->storage->verify('ABC123', true));
        $this->assertFalse($this->storage->verify('abc123', true));
    }

    /**
     * Tests verifying an invalid code.
     */
    public function testVerifyInvalidCode(): void
    {
        $code = 'ABC123';

        $this->storage->store($code);
        $this->assertFalse($this->storage->verify('XYZ789', false));
    }

    /**
     * Tests verification without stored code.
     */
    public function testVerifyWithoutStoredCode(): void
    {
        $this->assertFalse($this->storage->verify('ABC123', false));
    }

    /**
     * Tests removing a code.
     */
    public function testRemove(): void
    {
        $code = 'ABC123';

        $this->storage->store($code);
        $this->assertEquals($code, $this->storage->retrieve());

        $this->storage->remove();
        $this->assertNull($this->storage->retrieve());
    }
}
