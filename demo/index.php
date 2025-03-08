<?php

declare(strict_types=1);

// Autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Start the session
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

// Create a configuration
$config = new CaptchaConfig();
$config->setWidth(250)
       ->setHeight(80)
       ->setLength(6)
       ->setComplexity($complexity);

// Customize based on complexity
switch ($complexity) {
    case CaptchaConfig::COMPLEXITY_LOW:
        $config->setColorScheme(new ColorScheme('#2ecc71', '#ffffff'));
        $config->setCharacterSet('23456789');
        $config->setCaseSensitive(false);

        break;

    case CaptchaConfig::COMPLEXITY_MEDIUM:
        $config->setColorScheme(new ColorScheme('#3498db', '#ffffff', '#2980b9'));
        $config->setCharacterSet('ABCDEFGHJKLMNPQRSTUVWXYZ23456789');
        $config->setCaseSensitive(false);

        break;

    case CaptchaConfig::COMPLEXITY_HIGH:
        $config->setColorScheme(new ColorScheme('#e74c3c', '#ffffff', '#c0392b'));
        $config->setCharacterSet('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz23456789');
        $config->setCaseSensitive(true);

        break;
}

// Create a captcha with session storage
$captcha = Captcha::withSessionStorage($config);

// Check if a form has been submitted
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userInput = $_POST['captcha'] ?? '';

    if ($captcha->verify($userInput)) {
        $success = true;
        $message = 'Captcha validated successfully!';
    } else {
        $message = 'Incorrect captcha code. Please try again.';
    }
}

// Generate a new captcha
$captcha->generate();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eprofos Captcha Demo</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #3498db;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .container {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #2980b9;
        }
        .captcha-container {
            margin: 15px 0;
        }
        .refresh-link {
            display: inline-block;
            margin-left: 10px;
            color: #3498db;
            text-decoration: none;
        }
        .refresh-link:hover {
            text-decoration: underline;
        }
        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .options {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <h1>Eprofos Captcha Demo</h1>
    
    <div class="container">
        <h2>Captcha Verification</h2>
        
        <?php if ($message): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="captcha">Enter the captcha code below:</label>
                <div class="captcha-container">
                    <img src="captcha.php" alt="Captcha" id="captcha-image">
                    <a href="#" class="refresh-link" onclick="document.getElementById('captcha-image').src='captcha.php?refresh='+Math.random(); return false;">Refresh</a>
                </div>
            </div>
            
            <div class="form-group">
                <input type="text" id="captcha" name="captcha" required autocomplete="off">
            </div>
            
            <button type="submit">Verify</button>
        </form>
    </div>
    
    <div class="options">
        <h2>Customization Options</h2>
        <p>This demo uses a medium configuration. You can customize the captcha with different options:</p>
        <ul>
            <li><a href="?complexity=low">Low complexity</a></li>
            <li><a href="?complexity=medium">Medium complexity</a> (default)</li>
            <li><a href="?complexity=high">High complexity</a></li>
        </ul>
    </div>
</body>
</html>
