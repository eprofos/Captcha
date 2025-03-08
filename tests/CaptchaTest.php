<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Tests;

use Eprofos\Captcha\Captcha;
use Eprofos\Captcha\Config\CaptchaConfig;
use Eprofos\Captcha\Exception\CaptchaException;
use Eprofos\Captcha\Renderer\GdRenderer;
use Eprofos\Captcha\Tests\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;

/**
 * Tests for Captcha class.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class CaptchaTest extends TestCase
{
    /**
     * @var Captcha
     */
    private Captcha $captcha;

    /**
     * @var CaptchaConfig
     */
    private CaptchaConfig $config;

    /**
     * Setup before each test.
     */
    protected function setUp(): void
    {
        // Check if GD extension is available
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not available.');
        }

        $this->config = new CaptchaConfig();
        $this->captcha = new Captcha($this->config);
    }

    /**
     * Tests captcha code generation.
     */
    public function testGenerate(): void
    {
        $code = $this->captcha->generate();

        $this->assertNotNull($code);
        $this->assertIsString($code);
        $this->assertEquals($this->config->getLength(), strlen($code));
        $this->assertEquals($code, $this->captcha->getCode());
    }

    /**
     * Tests generation with storage.
     */
    public function testGenerateWithStorage(): void
    {
        $storageKey = 'test_captcha_' . uniqid();
        $storage = new ArrayStorage($storageKey);

        $captcha = new Captcha($this->config, $storage);
        $code = $captcha->generate();

        $this->assertEquals($code, $storage->retrieve());
    }

    /**
     * Tests valid code verification.
     */
    public function testVerifyValidCode(): void
    {
        $storageKey = 'test_captcha_' . uniqid();
        $storage = new ArrayStorage($storageKey);

        $captcha = new Captcha($this->config, $storage);
        $code = $captcha->generate();

        $this->assertTrue($captcha->verify($code));
    }

    /**
     * Tests invalid code verification.
     */
    public function testVerifyInvalidCode(): void
    {
        $storageKey = 'test_captcha_' . uniqid();
        $storage = new ArrayStorage($storageKey);

        $captcha = new Captcha($this->config, $storage);
        $captcha->generate();

        $this->assertFalse($captcha->verify('INVALID'));
    }

    /**
     * Tests exception when verifying without storage.
     */
    public function testVerifyWithoutStorage(): void
    {
        $this->expectException(CaptchaException::class);

        $captcha = new Captcha($this->config);
        $captcha->generate();
        $captcha->verify('ABC123');
    }

    /**
     * Tests exception when outputting without prior generation.
     */
    public function testOutputWithoutGenerate(): void
    {
        $this->expectException(CaptchaException::class);

        $captcha = new Captcha($this->config);
        $captcha->output();
    }

    /**
     * Tests exception when saving without prior generation.
     */
    public function testSaveWithoutGenerate(): void
    {
        $this->expectException(CaptchaException::class);

        $captcha = new Captcha($this->config);
        $captcha->save('/tmp/test.png');
    }

    /**
     * Tests creation with session storage.
     */
    public function testWithSessionStorage(): void
    {
        $captcha = Captcha::withSessionStorage($this->config);

        $this->assertInstanceOf(Captcha::class, $captcha);
        $this->assertInstanceOf(\Eprofos\Captcha\Storage\SessionStorage::class, $captcha->getStorage());
    }

    /**
     * Tests getters and setters.
     */
    public function testGettersSetters(): void
    {
        $config = new CaptchaConfig();
        $storage = new ArrayStorage();
        $renderer = new GdRenderer();

        $captcha = new Captcha();

        $captcha->setConfig($config);
        $this->assertSame($config, $captcha->getConfig());

        $captcha->setStorage($storage);
        $this->assertSame($storage, $captcha->getStorage());

        $captcha->setRenderer($renderer);
        $this->assertSame($renderer, $captcha->getRenderer());
    }
}
