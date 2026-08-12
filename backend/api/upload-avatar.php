<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../includes/session.php';

st_require_method('POST');
$userId = st_require_login();

if (empty($_FILES['avatar']) || !is_uploaded_file($_FILES['avatar']['tmp_name'])) {
    st_json_error('No file uploaded.', 400);
}

$file = $_FILES['avatar'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    st_json_error('Upload error.', 400);
}

$maxBytes = 2 * 1024 * 1024; // 2MB
if ($file['size'] > $maxBytes) {
    st_json_error('File too large. Maximum is 2MB.', 413);
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']);
$allowed = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp'
];

if (!isset($allowed[$mime])) {
    st_json_error('Unsupported image type. Use JPG, PNG or WEBP.', 415);
}

$ext = $allowed[$mime];
$uploadDir = __DIR__ . '/../../uploads/avatars';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        st_json_error('Failed to create upload directory.', 500);
    }
}

$filename = bin2hex(random_bytes(16)) . '.' . $ext;
$dest = $uploadDir . '/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    st_json_error('Failed to save uploaded file.', 500);
}

$webPath = 'uploads/avatars/' . $filename;

try {
    $db = safaritrak_db();
    $stmt = $db->prepare('UPDATE users SET avatar_path = ? WHERE id = ?');
    $stmt->execute([$webPath, $userId]);
} catch (Throwable $e) {
    // cleanup
    @unlink($dest);
    st_json_error('Failed to update user avatar: ' . $e->getMessage(), 500);
}

st_json_ok(['url' => $webPath]);
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
