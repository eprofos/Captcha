<?php

declare(strict_types=1);

// Autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Start session
session_start();

use Eprofos\Captcha\Captcha;
use Eprofos\Captcha\Config\CaptchaConfig;
use Eprofos\Captcha\Config\ColorScheme;

// Get complexity level from URL or use default value
$complexity = $_GET['complexity'] ?? CaptchaConfig::COMPLEXITY_MEDIUM;

// Validate complexity level
if (! in_array($complexity, [
    CaptchaConfig::COMPLEXITY_LOW,
    CaptchaConfig::COMPLEXITY_MEDIUM,
    CaptchaConfig::COMPLEXITY_HIGH,
])) {
    $complexity = CaptchaConfig::COMPLEXITY_MEDIUM;
}

// Create configuration
$config = new CaptchaConfig();
$config->setWidth(250)
       ->setHeight(80)
       ->setLength(6)
       ->setComplexity($complexity);

// Customize based on complexity
switch ($complexity) {
    case CaptchaConfig::COMPLEXITY_LOW:
        $config->setColorScheme(new ColorScheme('#2ecc71', '#ffffff'));

        break;

    case CaptchaConfig::COMPLEXITY_MEDIUM:
        $config->setColorScheme(new ColorScheme('#3498db', '#ffffff', '#2980b9'));

        break;

    case CaptchaConfig::COMPLEXITY_HIGH:
        $config->setColorScheme(new ColorScheme('#e74c3c', '#ffffff', '#c0392b'));

        break;
}

// Create captcha with session storage
$captcha = Captcha::withSessionStorage($config);

// Generate new captcha
$captcha->generate();

// Output image
$captcha->output();
