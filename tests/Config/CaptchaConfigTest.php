<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Tests\Config;

use Eprofos\Captcha\Config\CaptchaConfig;
use Eprofos\Captcha\Config\ColorScheme;
use Eprofos\Captcha\Exception\ImageException;
use PHPUnit\Framework\TestCase;

/**
 * Tests for CaptchaConfig class.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class CaptchaConfigTest extends TestCase
{
    /**
     * @var CaptchaConfig
     */
    private CaptchaConfig $config;

    /**
     * Setup before each test.
     */
    protected function setUp(): void
    {
        $this->config = new CaptchaConfig();
    }

    /**
     * Tests default values.
     */
    public function testDefaultValues(): void
    {
        $this->assertEquals(200, $this->config->getWidth());
        $this->assertEquals(80, $this->config->getHeight());
        $this->assertEquals(6, $this->config->getLength());
        $this->assertEquals('ABCDEFGHJKLMNPQRSTUVWXYZ23456789', $this->config->getCharacterSet());
        $this->assertEquals(CaptchaConfig::COMPLEXITY_MEDIUM, $this->config->getComplexity());
        $this->assertTrue($this->config->hasBackgroundNoise());
        $this->assertTrue($this->config->hasWaveDistortion());
        $this->assertFalse($this->config->isCaseSensitive());
    }

    /**
     * Tests width getter and setter.
     */
    public function testWidthSetterGetter(): void
    {
        $this->config->setWidth(300);
        $this->assertEquals(300, $this->config->getWidth());
    }

    /**
     * Tests exception for invalid width.
     */
    public function testInvalidWidth(): void
    {
        $this->expectException(ImageException::class);
        $this->config->setWidth(0);
    }

    /**
     * Tests height getter and setter.
     */
    public function testHeightSetterGetter(): void
    {
        $this->config->setHeight(100);
        $this->assertEquals(100, $this->config->getHeight());
    }

    /**
     * Tests exception for invalid height.
     */
    public function testInvalidHeight(): void
    {
        $this->expectException(ImageException::class);
        $this->config->setHeight(0);
    }

    /**
     * Tests length getter and setter.
     */
    public function testLengthSetterGetter(): void
    {
        $this->config->setLength(8);
        $this->assertEquals(8, $this->config->getLength());
    }

    /**
     * Tests correction of invalid length.
     */
    public function testInvalidLength(): void
    {
        $this->config->setLength(0);
        $this->assertEquals(1, $this->config->getLength());
    }

    /**
     * Tests character set getter and setter.
     */
    public function testCharacterSetSetterGetter(): void
    {
        $characterSet = 'ABC123';
        $this->config->setCharacterSet($characterSet);
        $this->assertEquals($characterSet, $this->config->getCharacterSet());
    }

    /**
     * Tests correction of empty character set.
     */
    public function testEmptyCharacterSet(): void
    {
        $this->config->setCharacterSet('');
        $this->assertEquals('ABCDEFGHJKLMNPQRSTUVWXYZ23456789', $this->config->getCharacterSet());
    }

    /**
     * Tests font size getter and setter.
     */
    public function testFontSizeSetterGetter(): void
    {
        $this->config->setFontSize(32);
        $this->assertEquals(32, $this->config->getFontSize());
    }

    /**
     * Tests correction of invalid font size.
     */
    public function testInvalidFontSize(): void
    {
        $this->config->setFontSize(5);
        $this->assertEquals(8, $this->config->getFontSize());
    }

    /**
     * Tests complexity level getter and setter.
     */
    public function testComplexitySetterGetter(): void
    {
        $this->config->setComplexity(CaptchaConfig::COMPLEXITY_HIGH);
        $this->assertEquals(CaptchaConfig::COMPLEXITY_HIGH, $this->config->getComplexity());

        // Check that parameters were adjusted
        $this->assertTrue($this->config->hasBackgroundNoise());
        $this->assertTrue($this->config->hasWaveDistortion());
        $this->assertEquals(200, $this->config->getNoisePoints());
        $this->assertEquals(10, $this->config->getNoiseLines());
        $this->assertEquals(12, $this->config->getMaxRotation());
    }

    /**
     * Tests correction of invalid complexity level.
     */
    public function testInvalidComplexity(): void
    {
        $this->config->setComplexity('invalid');
        $this->assertEquals(CaptchaConfig::COMPLEXITY_MEDIUM, $this->config->getComplexity());
    }

    /**
     * Tests background noise getter and setter.
     */
    public function testBackgroundNoiseSetterGetter(): void
    {
        $this->config->setBackgroundNoise(false);
        $this->assertFalse($this->config->hasBackgroundNoise());
    }

    /**
     * Tests noise points getter and setter.
     */
    public function testNoisePointsSetterGetter(): void
    {
        $this->config->setNoisePoints(150);
        $this->assertEquals(150, $this->config->getNoisePoints());
    }

    /**
     * Tests correction of invalid noise points.
     */
    public function testInvalidNoisePoints(): void
    {
        $this->config->setNoisePoints(-10);
        $this->assertEquals(0, $this->config->getNoisePoints());
    }

    /**
     * Tests noise lines getter and setter.
     */
    public function testNoiseLinesSetterGetter(): void
    {
        $this->config->setNoiseLines(8);
        $this->assertEquals(8, $this->config->getNoiseLines());
    }

    /**
     * Tests correction of invalid noise lines.
     */
    public function testInvalidNoiseLines(): void
    {
        $this->config->setNoiseLines(-5);
        $this->assertEquals(0, $this->config->getNoiseLines());
    }

    /**
     * Tests wave distortion getter and setter.
     */
    public function testWaveDistortionSetterGetter(): void
    {
        $this->config->setWaveDistortion(false);
        $this->assertFalse($this->config->hasWaveDistortion());
    }

    /**
     * Tests wave amplitude getter and setter.
     */
    public function testWaveAmplitudeSetterGetter(): void
    {
        $this->config->setWaveAmplitude(6);
        $this->assertEquals(6, $this->config->getWaveAmplitude());
    }

    /**
     * Tests correction of invalid wave amplitude.
     */
    public function testInvalidWaveAmplitude(): void
    {
        $this->config->setWaveAmplitude(-2);
        $this->assertEquals(0, $this->config->getWaveAmplitude());
    }

    /**
     * Tests wave frequency getter and setter.
     */
    public function testWaveFrequencySetterGetter(): void
    {
        $this->config->setWaveFrequency(0.2);
        $this->assertEquals(0.2, $this->config->getWaveFrequency());
    }

    /**
     * Tests correction of invalid wave frequency.
     */
    public function testInvalidWaveFrequency(): void
    {
        $this->config->setWaveFrequency(0);
        $this->assertEquals(0.1, $this->config->getWaveFrequency());
    }

    /**
     * Tests color scheme getter and setter.
     */
    public function testColorSchemeSetterGetter(): void
    {
        $colorScheme = new ColorScheme('#FF0000', '#FFFFFF');
        $this->config->setColorScheme($colorScheme);
        $this->assertSame($colorScheme, $this->config->getColorScheme());
    }

    /**
     * Tests max rotation angle getter and setter.
     */
    public function testMaxRotationSetterGetter(): void
    {
        $this->config->setMaxRotation(15);
        $this->assertEquals(15, $this->config->getMaxRotation());
    }

    /**
     * Tests correction of invalid max rotation angle.
     */
    public function testInvalidMaxRotation(): void
    {
        $this->config->setMaxRotation(-5);
        $this->assertEquals(0, $this->config->getMaxRotation());
    }

    /**
     * Tests case sensitivity getter and setter.
     */
    public function testCaseSensitiveSetterGetter(): void
    {
        $this->config->setCaseSensitive(true);
        $this->assertTrue($this->config->isCaseSensitive());
    }
}
