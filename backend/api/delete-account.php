<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../includes/session.php';

st_require_method('POST');
$userId = st_require_login();

try {
    $db = safaritrak_db();
    $db->beginTransaction();

    $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$userId]);

    $db->commit();
} catch (Throwable $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    st_json_error('Failed to delete account.', 500);
}

st_logout();
st_json_ok(['redirect' => 'login.html']);
