<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/response.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/geo.php';

st_start_session();
st_require_method('POST');
$userId = st_require_login();

$input = st_input();
$journeyId = (int) ($input['journey_id'] ?? 0);
$lat = isset($input['lat']) ? (float) $input['lat'] : null;
$lng = isset($input['lng']) ? (float) $input['lng'] : null;
$speedKmh = isset($input['speed_kmh']) && $input['speed_kmh'] !== '' ? (float) $input['speed_kmh'] : null;

if ($lat === null || $lng === null) {
    st_json_error('A location is required.');
}

$db = safaritrak_db();

$journeyStmt = $db->prepare('SELECT id, distance_km FROM journeys WHERE id = ? AND user_id = ? AND status = "active"');
$journeyStmt->execute([$journeyId, $userId]);
$journey = $journeyStmt->fetch();

if (!$journey) {
    st_json_error('That journey is not active.', 404);
}

$lastPointStmt = $db->prepare('SELECT lat, lng FROM journey_positions WHERE journey_id = ? ORDER BY id DESC LIMIT 1');
$lastPointStmt->execute([$journeyId]);
$lastPoint = $lastPointStmt->fetch();

$db->prepare('INSERT INTO journey_positions (journey_id, lat, lng, speed_kmh) VALUES (?, ?, ?, ?)')
    ->execute([$journeyId, $lat, $lng, $speedKmh]);

$coveredStmt = $db->prepare(
    'SELECT lat, lng FROM journey_positions WHERE journey_id = ? ORDER BY id ASC'
);
$coveredStmt->execute([$journeyId]);
$points = $coveredStmt->fetchAll();

$coveredKm = 0.0;
for ($i = 1; $i < count($points); $i++) {
    $leg = st_distance_km(
        (float) $points[$i - 1]['lat'],
        (float) $points[$i - 1]['lng'],
        (float) $points[$i]['lat'],
        (float) $points[$i]['lng']
    );
    $coveredKm += $leg ?? 0;
}

st_json_ok([
    'covered_km' => round($coveredKm, 2),
    'total_km' => $journey['distance_km'] !== null ? (float) $journey['distance_km'] : null,
]);
