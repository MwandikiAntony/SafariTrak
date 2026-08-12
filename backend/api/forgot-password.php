<?php

require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../config/mailer.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';

st_require_method('POST');

$input = st_input();
$contact = trim($input['contact'] ?? '');

if ($contact === '') {
    st_json_error('Enter your email or phone number.', 422);
}

$db = safaritrak_db();

$stmt = $db->prepare('SELECT id, email FROM users WHERE email = ? OR phone = ?');
$stmt->execute([$contact, preg_replace('/\D/', '', $contact)]);
$user = $stmt->fetch();

$response = ['message' => 'If that account exists, a reset link has been sent.'];

if ($user) {
    $token = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $token);
    $expiresAt = date('Y-m-d H:i:s', time() + (30 * 60));

    $insertStmt = $db->prepare(
        'INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, ?)'
    );
    $insertStmt->execute([$user['id'], $tokenHash, $expiresAt]);

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $resetLink = "{$protocol}://{$host}/reset-password.html?token={$token}";
    $subject = 'SafariTrak password reset';
    $body = "A password reset was requested for your SafariTrak account.\n\n" .
            "Click the link below to set a new password:\n" .
            "{$resetLink}\n\n" .
            "If you did not request a password reset, you can ignore this message.\n";

    $mailSent = st_send_mail($user['email'], $subject, $body, 'no-reply@safaritrak.local', 'SafariTrak');
    if (!$mailSent && getenv('SAFARITRAK_DEV_MODE') === '1') {
        $response['dev_reset_link'] = 'reset-password.html?token=' . $token;
    }
}

st_json_ok($response);
