<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../includes/session.php';

st_require_method('POST');
$userId = st_require_login();

$input = json_decode(file_get_contents('php://input'), true) ?? [];

$title = trim($input['title'] ?? '');
$destination = trim($input['destination'] ?? '');
$departure_at = trim($input['departure_at'] ?? '');
$invites = $input['invites'] ?? [];

$errors = [];
if ($title === '') {
    $errors['title'] = 'Enter a trip name.';
}
if ($destination === '') {
    $errors['destination'] = 'Enter a destination.';
}
if (!empty($departure_at)) {
    $timestamp = strtotime($departure_at);
    if ($timestamp === false) {
        $errors['departure_at'] = 'Select a valid departure date and time.';
    } else {
        $departure_at = date('Y-m-d H:i:s', $timestamp);
    }
}
if (!empty($errors)) {
    st_json_error('Please correct the form fields.', 422, ['errors' => $errors]);
}

try {
    $db = safaritrak_db();
    $stmt = $db->prepare('INSERT INTO group_journeys (organizer_id, title, destination_label, departure_at, status) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$userId, $title, $destination, $departure_at !== '' ? $departure_at : null, 'upcoming']);
    $groupId = (int)$db->lastInsertId();

    if (!empty($invites) && is_array($invites)) {
        $memberStmt = $db->prepare('INSERT INTO group_members (group_journey_id, user_id, invite_name, invite_phone, status) VALUES (?, ?, ?, ?, ?)');
        foreach ($invites as $invite) {
            $name = trim($invite['name'] ?? '');
            $phone = preg_replace('/\D/', '', $invite['phone'] ?? '');
            if ($name === '' && $phone === '') {
                continue;
            }
            $memberStmt->execute([$groupId, null, $name, $phone, 'invited']);
        }
    }
} catch (Throwable $e) {
    st_json_error('Failed to create group journey: ' . $e->getMessage(), 500);
}

st_json_ok(['message' => 'Group journey created', 'group_id' => $groupId]);
