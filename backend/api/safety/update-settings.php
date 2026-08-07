<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/session.php';

st_start_session();
st_require_method('POST');
$userId = st_require_login();

$input = st_input();
$field = $input['field'] ?? '';
$value = !empty($input['value']) ? 1 : 0;

$allowedFields = ['route_deviation_alerts', 'arrival_notifications', 'auto_sos_on_silence'];
if (!in_array($field, $allowedFields, true)) {
    st_json_error('That setting is not recognized.');
}

$db = safaritrak_db();
$db->prepare("UPDATE users SET {$field} = ? WHERE id = ?")->execute([$value, $userId]);

st_json_ok();