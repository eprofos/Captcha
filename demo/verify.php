<?php

declare(strict_types=1);

// Autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Start session
session_start();

use Eprofos\Captcha\Captcha;

// Check if code has been submitted
if (! isset($_POST['captcha'])) {
    header('Location: index.php');
    exit;
}

$userInput = $_POST['captcha'];

// Create captcha with session storage
$captcha = Captcha::withSessionStorage();

// Verify code
$isValid = $captcha->verify($userInput);

// Prepare response
$response = [
    'success' => $isValid,
    'message' => $isValid
        ? 'Captcha validated successfully!'
        : 'Incorrect captcha code. Please try again.',
];

// If it's an AJAX request, send JSON response
if (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Otherwise, redirect to index page with message
$_SESSION['captcha_message'] = $response['message'];
$_SESSION['captcha_success'] = $response['success'];

header('Location: index.php');
exit;
