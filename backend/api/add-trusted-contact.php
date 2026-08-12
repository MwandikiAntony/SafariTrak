<?php
require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../includes/session.php';

st_require_method('POST');
$userId = st_require_login();

$input = st_input();
$name = trim($input['invite_name'] ?? '');
$phone = trim($input['invite_phone'] ?? '');
$relationship = trim($input['relationship'] ?? '');

$errors = [];
if ($name === '') {
    $errors['invite_name'] = 'Contact name is required.';
}
if ($phone === '') {
    $errors['invite_phone'] = 'Contact phone is required.';
}

if (!empty($errors)) {
    st_json_error('Please fix the highlighted fields.', 422, ['errors' => $errors]);
}

try {
    $contactUserId = null;
    $userStmt = safaritrak_db()->prepare('SELECT id FROM users WHERE phone = ? LIMIT 1');
    $userStmt->execute([$phone]);
    $existingUser = $userStmt->fetch();
    if ($existingUser) {
        $contactUserId = $existingUser['id'];
    }

    $insertStmt = safaritrak_db()->prepare(
        'INSERT INTO trusted_contacts (owner_id, contact_user_id, invite_name, invite_phone, relationship) VALUES (?, ?, ?, ?, ?)'
    );
    $insertStmt->execute([$userId, $contactUserId, $name, $phone, $relationship]);

    st_json_ok(['message' => 'Trusted contact added successfully.']);
} catch (Exception $e) {
    st_json_error('Failed to add trusted contact: ' . $e->getMessage(), 500);
}
