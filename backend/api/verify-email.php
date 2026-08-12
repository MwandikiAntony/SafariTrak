<?php
require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';

st_require_method('POST');

$input = st_input();
$email = trim($input['email'] ?? '');
$code = trim($input['code'] ?? '');

$errors = [];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid email.';
}
if ($code === '') {
    $errors['code'] = 'Verification code is required.';
}

if (!empty($errors)) {
    st_json_error('Please fix the highlighted fields.', 422, ['errors' => $errors]);
}

$db = safaritrak_db();
$stmt = $db->prepare('SELECT id, full_name, username, otp_code, otp_expires_at, email_verified FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    st_json_error('Unable to find that signup request.', 404);
}

if ($user['email_verified']) {
    st_json_ok(['redirect' => 'index.php']);
}

if ($user['otp_code'] !== $code) {
    st_json_error('The verification code is invalid.', 401);
}

if (empty($user['otp_expires_at']) || strtotime($user['otp_expires_at']) < time()) {
    st_json_error('The verification code has expired.', 410);
}

$updateStmt = $db->prepare('UPDATE users SET email_verified = 1, otp_code = NULL, otp_expires_at = NULL WHERE id = ?');
$updateStmt->execute([$user['id']]);

st_login_user(['id' => $user['id'], 'full_name' => $user['full_name'], 'username' => $user['username']], false);

st_json_ok(['redirect' => 'index.php']);
