<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../includes/session.php';

st_start_session();
st_require_method('POST');
$userId = st_require_login();

if (!empty($_POST['remove'])) {
    $pdo = safaritrak_db();
    $existing = $pdo->prepare('SELECT avatar_path FROM users WHERE id = ?');
    $existing->execute([$userId]);
    $oldPath = $existing->fetchColumn();

    $pdo->prepare('UPDATE users SET avatar_path = NULL WHERE id = ?')->execute([$userId]);

    if ($oldPath && strpos($oldPath, 'uploads/avatars/') === 0) {
        $oldFile = __DIR__ . '/../../' . $oldPath;
        if (is_file($oldFile)) {
            @unlink($oldFile);
        }
    }

    st_json_ok(['avatar_path' => null]);
}

if (empty($_FILES['avatar']) || $_FILES['avatar']['error'] === UPLOAD_ERR_NO_FILE) {
    st_json_error('Choose a photo to upload.');
}

$file = $_FILES['avatar'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    st_json_error('That upload did not go through. Please try again.');
}

$maxBytes = 4 * 1024 * 1024;
if ($file['size'] > $maxBytes) {
    st_json_error('Please choose an image smaller than 4 MB.');
}

$allowed = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!isset($allowed[$mime])) {
    st_json_error('Please upload a JPG, PNG or WEBP image.');
}

$imageInfo = @getimagesize($file['tmp_name']);
if ($imageInfo === false) {
    st_json_error('That file does not look like a valid image.');
}

$extension = $allowed[$mime];
$uploadsDir = __DIR__ . '/../../uploads/avatars';

if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
}

$pdo = safaritrak_db();

$existing = $pdo->prepare('SELECT avatar_path FROM users WHERE id = ?');
$existing->execute([$userId]);
$oldPath = $existing->fetchColumn();

$filename = 'user-' . $userId . '-' . bin2hex(random_bytes(6)) . '.' . $extension;
$destination = $uploadsDir . '/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    st_json_error('We could not save that photo. Please try again.', 500);
}

$publicPath = 'uploads/avatars/' . $filename;

$update = $pdo->prepare('UPDATE users SET avatar_path = ? WHERE id = ?');
$update->execute([$publicPath, $userId]);

if ($oldPath && strpos($oldPath, 'uploads/avatars/') === 0) {
    $oldFile = __DIR__ . '/../../' . $oldPath;
    if (is_file($oldFile)) {
        @unlink($oldFile);
    }
}

st_json_ok(['avatar_path' => $publicPath]);
