<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../includes/session.php';

st_require_method('POST');
$userId = st_require_login();

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$currentPassword = (string) ($input['current_password'] ?? '');
$newPassword = (string) ($input['new_password'] ?? '');
$confirmPassword = (string) ($input['confirm_password'] ?? '');

$errors = [];
if ($currentPassword === '') {
    $errors['current_password'] = 'Enter your current password.';
}
if (strlen($newPassword) < 6) {
    $errors['new_password'] = 'New password must be at least 6 characters.';
}
if ($newPassword !== $confirmPassword) {
    $errors['confirm_password'] = 'Confirm password does not match.';
}

if (!empty($errors)) {
    st_json_error('Please fix the highlighted fields.', 422, ['errors' => $errors]);
}

$db = safaritrak_db();
$stmt = $db->prepare('SELECT password_hash FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
    st_json_error('Current password is incorrect.', 401, ['errors' => ['current_password' => 'Current password is incorrect.']]);
}

$newHash = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    $updateStmt = $db->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $updateStmt->execute([$newHash, $userId]);
} catch (Throwable $e) {
    st_json_error('Failed to update password.', 500);
}

st_json_ok(['message' => 'Password updated successfully.']);
