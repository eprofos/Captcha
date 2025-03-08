# Eprofos Captcha

A professional PHP 8+ library for generating visually sophisticated captchas.

## Features

- Generation of visually sophisticated captchas
- Complete customization (dimensions, fonts, complexity levels, etc.)
- Distortion and wave effects
- Background noise and pattern options
- Color schemes and gradient options
- Character set and length configuration
- Session integration capabilities
- Implementation with modern PHP 8 features (attributes, typed properties, union types)

## Installation

```bash
composer require eprofos/captcha
```

## Requirements

- PHP 8.0 or higher
- GD extension
- mbstring extension

## Basic Usage

```php
<?php

use Eprofos\Captcha\Captcha;
use Eprofos\Captcha\Config\CaptchaConfig;

// Create a captcha configuration
$config = new CaptchaConfig();
$config->setWidth(200)
       ->setHeight(80)
       ->setLength(6)
       ->setComplexity(CaptchaConfig::COMPLEXITY_MEDIUM);

// Create a captcha instance
$captcha = new Captcha($config);

// Generate and display the captcha
$captcha->generate();
$captcha->output();

// Store the code in the session
$_SESSION['captcha_code'] = $captcha->getCode();
```

## Captcha Verification

```php
<?php

// Verify the submitted captcha code
$userInput = $_POST['captcha'] ?? '';
$storedCode = $_SESSION['captcha_code'] ?? '';

if (strtolower($userInput) === strtolower($storedCode)) {
    echo "Captcha validated successfully!";
} else {
    echo "Incorrect captcha code!";
}
```

## Advanced Customization

```php
<?php

use Eprofos\Captcha\Captcha;
use Eprofos\Captcha\Config\CaptchaConfig;
use Eprofos\Captcha\Config\ColorScheme;

// Create a custom configuration
$config = new CaptchaConfig();
$config->setWidth(300)
       ->setHeight(100)
       ->setLength(8)
       ->setComplexity(CaptchaConfig::COMPLEXITY_HIGH)
       ->setFont('montserrat.ttf')
       ->setBackgroundNoise(true)
       ->setWaveDistortion(true)
       ->setCharacterSet('ABCDEFGHJKLMNPQRSTUVWXYZ23456789')
       ->setColorScheme(new ColorScheme('#3498db', '#2c3e50'));

// Create and generate the captcha
$captcha = new Captcha($config);
$captcha->generate();

// Save the image
$captcha->save('/path/to/captcha.png');

// Or send the image as an HTTP response
$captcha->output();
```

## Using with Built-in Session Storage

```php
<?php

use Eprofos\Captcha\Captcha;
use Eprofos\Captcha\Config\CaptchaConfig;
use Eprofos\Captcha\Storage\SessionStorage;

// Start the session
session_start();

// Create a configuration
$config = new CaptchaConfig();

// Create a captcha with session storage
$storage = new SessionStorage('my_captcha_key');
$captcha = new Captcha($config, $storage);

// Generate and display the captcha
$captcha->generate();
$captcha->output();

// Verify the code (in another script)
if ($captcha->verify($_POST['captcha'] ?? '')) {
    echo "Captcha validated successfully!";
} else {
    echo "Incorrect captcha code!";
}
```

## License

MIT