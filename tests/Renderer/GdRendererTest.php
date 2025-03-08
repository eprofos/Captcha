<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Tests\Renderer;

use Eprofos\Captcha\Config\CaptchaConfig;
use Eprofos\Captcha\Exception\FontException;
use Eprofos\Captcha\Exception\ImageException;
use Eprofos\Captcha\Renderer\GdRenderer;
use PHPUnit\Framework\TestCase;

/**
 * Tests for GdRenderer class.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class GdRendererTest extends TestCase
{
    /**
     * @var GdRenderer
     */
    private GdRenderer $renderer;

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

        $this->renderer = new GdRenderer();
        $this->config = new CaptchaConfig();
    }

    /**
     * Tests image creation.
     */
    public function testRender(): void
    {
        $code = 'ABC123';
        $result = $this->renderer->render($code, $this->config);

        $this->assertNotNull($result);

        $this->assertInstanceOf(\GdImage::class, $result);
    }

    /**
     * Tests exception for font not found.
     */
    public function testRenderWithInvalidFont(): void
    {
        $this->expectException(FontException::class);

        $code = 'ABC123';
        $this->config->setFont('/invalid/path/font.ttf');

        $this->renderer->render($code, $this->config);
    }

    /**
     * Tests image saving.
     */
    public function testSave(): void
    {
        $code = 'ABC123';
        $this->renderer->render($code, $this->config);

        $tempFile = sys_get_temp_dir() . '/captcha_test.png';

        $result = $this->renderer->save($tempFile);
        $this->assertTrue($result);
        $this->assertFileExists($tempFile);

        // Cleanup
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }

    /**
     * Tests exception when saving without prior rendering.
     */
    public function testSaveWithoutRender(): void
    {
        $this->expectException(ImageException::class);

        $tempFile = sys_get_temp_dir() . '/captcha_test.png';

        // Create a new renderer without calling render()
        $renderer = new GdRenderer();
        $renderer->save($tempFile);
    }

    /**
     * Tests exception when outputting without prior rendering.
     */
    public function testOutputWithoutRender(): void
    {
        $this->expectException(ImageException::class);

        // Create a new renderer without calling render()
        $renderer = new GdRenderer();
        $renderer->output();
    }
}
