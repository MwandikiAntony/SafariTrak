<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../includes/session.php';

st_require_method('POST');
$userId = st_require_login();

$input = json_decode(file_get_contents('php://input'), true) ?? [];

$fullName = trim($input['full_name'] ?? '');
$email = trim($input['email'] ?? '');
$phone = preg_replace('/\D/', '', $input['phone'] ?? '');
$home = trim($input['home_address'] ?? '');

$errors = [];
if ($fullName === '') {
    $errors['full_name'] = 'Enter your full name.';
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Enter a valid email address.';
}
if ($phone !== '' && strlen($phone) < 9) {
    $errors['phone'] = 'Enter a valid phone number.';
}
if (!empty($errors)) {
    st_json_error('Please fix the highlighted fields.', 422, ['errors' => $errors]);
}

try {
    $db = safaritrak_db();
    $stmt = $db->prepare('UPDATE users SET full_name = ?, email = ?, phone = ?, home_address = ? WHERE id = ?');
    $stmt->execute([$fullName, $email, $phone, $home, $userId]);
} catch (Throwable $e) {
    st_json_error('Failed to update profile: ' . $e->getMessage(), 500);
}

st_json_ok(['message' => 'Profile updated']);
