<?php

require_once __DIR__ . '/backend/config/database.php';
require_once __DIR__ . '/backend/includes/session.php';
require_once __DIR__ . '/backend/includes/auth-guard.php';

$db = safaritrak_db();

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: login.php');
    exit;
}

$journeyId = isset($_GET['journey_id'])
    ? (int) $_GET['journey_id']
    : 0;

if ($journeyId <= 0) {
    die('Invalid journey.');
}

$journey = null;

try {

    $stmt = $db->prepare("
        SELECT
            j.id,
            j.user_id,
            j.start_label,
            j.start_lat,
            j.start_lng,
            j.end_label,
            j.end_lat,
            j.end_lng,
            j.transport_mode,
            j.status,
            j.started_at,
            j.ended_at,
            u.name AS owner_name,
            u.username AS owner_username
        FROM journeys j
        INNER JOIN users u
            ON u.id = j.user_id
        INNER JOIN journey_shares js
            ON js.journey_id = j.id
        INNER JOIN trusted_contacts tc
            ON tc.user_id = j.user_id
            AND tc.contact_user_id = ?
        WHERE j.id = ?
        AND js.shared_with_user_id = ?
        AND tc.confirmed = 1
        AND tc.share_live_location = 1
        LIMIT 1
    ");

    $stmt->execute([
        $userId,
        $journeyId,
        $userId
    ]);

    $journey = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (Throwable $e) {

    $journey = null;
}

if (!$journey) {

    http_response_code(403);

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SafariTrak - Access Denied</title>

        <style>
            body {
                margin: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f4f7f6;
                font-family: Arial, Helvetica, sans-serif;
            }

            .box {
                width: 90%;
                max-width: 450px;
                background: white;
                border-radius: 18px;
                padding: 35px;
                text-align: center;
                box-shadow: 0 15px 40px rgba(0,0,0,.08);
            }

            .icon {
                width: 65px;
                height: 65px;
                border-radius: 50%;
                background: #fdeaea;
                color: #c0392b;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                font-size: 28px;
                font-weight: bold;
            }

            h2 {
                margin: 0 0 10px;
                color: #263238;
            }

            p {
                color: #6b7779;
                line-height: 1.6;
                font-size: 14px;
            }

            a {
                display: inline-block;
                margin-top: 15px;
                padding: 11px 20px;
                background: #087f6b;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: bold;
            }
        </style>
    </head>

    <body>

    <div class="box">

        <div class="icon">!</div>

        <h2>Journey Unavailable</h2>

        <p>
            You are not authorized to view this journey,
            or live location sharing has been disabled.
        </p>

        <a href="dashboard.php">
            Back to Dashboard
        </a>

    </div>

    </body>
    </html>
    <?php

    exit;
}

$ownerName =
    $journey['owner_name'] ??
    $journey['owner_username'] ??
    'Traveler';

$initial =
    strtoupper(
        substr($ownerName, 0, 1)
    );

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    SafariTrak - Watch Journey
</title>

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: #f4f7f6;
    color: #263238;
}

.page {
    min-height: 100vh;
    display: flex;
}

.sidebar {
    width: 230px;
    background: #10b981;
    color: white;
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
}

.brand {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 25px 22px;
}

.brand-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #e5a82c;
    display: flex;
    align-items: center;
    justify-content: center;
}

.brand-text strong {
    display: block;
    font-size: 16px;
}

.brand-text span {
    font-size: 9px;
    opacity: .85;
}

.nav {
    padding: 8px 14px;
}

.nav a {
    display: flex;
    align-items: center;
    gap: 13px;
    color: white;
    text-decoration: none;
    padding: 13px 12px;
    border-radius: 10px;
    margin-bottom: 3px;
    font-size: 13px;
}

.nav a:hover,
.nav a.active {
    background: rgba(255,255,255,.1);
}

.user-area {
    position: absolute;
    left: 20px;
    right: 20px;
    bottom: 20px;
}

.user-area:before {
    content: "";
    display: block;
    height: 1px;
    background: rgba(255,255,255,.18);
    margin-bottom: 15px;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #e6f1ee;
    color: #0e9871;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.user-info strong {
    display: block;
    font-size: 11px;
}

.user-info span {
    font-size: 9px;
}

.main {
    margin-left: 230px;
    width: calc(100% - 230px);
}

.topbar {
    height: 75px;
    background: white;
    border-bottom: 1px solid #e1e7e5;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
}

.title small {
    color: #10a77e;
    font-size: 10px;
    font-weight: bold;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.title h1 {
    margin: 4px 0 0;
    font-size: 20px;
}

.profile-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #e1eeeb;
    color: #0d9874;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.content {
    padding: 25px 30px;
}

.traveler-card {
    max-width: 1100px;
    margin: 0 auto 15px;
    background: white;
    border: 1px solid #e0e7e4;
    border-radius: 14px;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.traveler-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.traveler-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #087f6b;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.traveler-name {
    font-weight: bold;
    font-size: 14px;
}

.traveler-status {
    margin-top: 4px;
    color: #10a77e;
    font-size: 11px;
}

.live-indicator {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 11px;
    color: #087f6b;
    font-weight: bold;
}

.live-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #10b981;
    box-shadow: 0 0 0 5px rgba(16,185,129,.12);
}

.map-card {
    max-width: 1100px;
    margin: 0 auto;
    background: white;
    border: 1px solid #e0e7e4;
    border-radius: 15px;
    overflow: hidden;
}

.map-header {
    padding: 15px 18px;
    border-bottom: 1px solid #e7ecea;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.map-header strong {
    font-size: 14px;
}

.map-header span {
    color: #7c8789;
    font-size: 10px;
}

#sharedMap {
    width: 100%;
    height: 560px;
}

.map-controls {
    position: absolute;
    right: 20px;
    bottom: 20px;
    z-index: 500;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.map-control-btn {
    border: 0;
    background: white;
    color: #087f6b;
    width: 42px;
    height: 42px;
    border-radius: 9px;
    box-shadow: 0 3px 12px rgba(0,0,0,.18);
    cursor: pointer;
    font-size: 15px;
}

.info-grid {
    max-width: 1100px;
    margin: 15px auto 0;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.info-card {
    background: white;
    border: 1px solid #e0e7e4;
    border-radius: 12px;
    padding: 15px;
}

.info-card i {
    color: #087f6b;
    font-size: 15px;
    margin-bottom: 8px;
}

.info-label {
    display: block;
    color: #879193;
    font-size: 9px;
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: .5px;
}

.info-value {
    display: block;
    margin-top: 5px;
    font-size: 14px;
    font-weight: bold;
}

.footer {
    max-width: 1100px;
    margin: 15px auto;
    display: flex;
    justify-content: space-between;
    color: #8a9496;
    font-size: 9px;
}

@media(max-width:800px) {

    .sidebar {
        display: none;
    }

    .main {
        width: 100%;
        margin-left: 0;
    }

    .content {
        padding: 12px;
    }

    #sharedMap {
        height: 470px;
    }

    .info-grid {
        grid-template-columns: 1fr 1fr;
    }

    .traveler-card {
        align-items: flex-start;
        gap: 15px;
    }
}

@media(max-width:500px) {

    .topbar {
        padding: 0 15px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .traveler-card {
        flex-direction: column;
    }

    .footer {
        flex-direction: column;
        gap: 5px;
    }
}

</style>

</head>

<body>

<div class="page">

<aside class="sidebar">

<div class="brand">

<div class="brand-icon">
<i class="fas fa-route"></i>
</div>

<div class="brand-text">
<strong>SafariTrak</strong>
<span>Travel smarter</span>
</div>

</div>

<nav class="nav">

<a href="dashboard.php">
<i class="fas fa-th-large"></i>
Dashboard
</a>

<a href="my-journeys.php">
<i class="fas fa-map-marked-alt"></i>
My Journeys
</a>

<a href="live-tracking.php">
<i class="fas fa-location-crosshairs"></i>
Live Tracking
</a>

<a href="places.php">
<i class="fas fa-map-pin"></i>
Places
</a>

<a href="messages.php">
<i class="far fa-comment-alt"></i>
Messages
</a>

<a href="trusted-contacts.php" class="active">
<i class="fas fa-user-group"></i>
Trusted Contacts
</a>

<a href="safety.php">
<i class="fas fa-shield-halved"></i>
Safety
</a>

<a href="settings.php">
<i class="fas fa-gear"></i>
Settings
</a>

<a href="logout.php">
<i class="fas fa-arrow-right-from-bracket"></i>
Logout
</a>

</nav>

<div class="user-area">

<div class="user-profile">

<div class="user-avatar">
<?= htmlspecialchars($initial) ?>
</div>

<div class="user-info">

<strong>
<?= htmlspecialchars($_SESSION['name'] ?? $_SESSION['username'] ?? 'User') ?>
</strong>

<span>
Trusted Contact
</span>

</div>

</div>

</div>

</aside>

<main class="main">

<header class="topbar">

<div class="title">

<small>
Live Location
</small>

<h1>
Watch Journey
</h1>

</div>

<div class="profile-circle">
<?= htmlspecialchars($initial) ?>
</div>

</header>

<div class="content">

<div class="traveler-card">

<div class="traveler-info">

<div class="traveler-avatar">
<?= htmlspecialchars($initial) ?>
</div>

<div>

<div class="traveler-name">
<?= htmlspecialchars($ownerName) ?>
</div>

<div
class="traveler-status"
id="trackingStatus"
>
Connecting to live location...
</div>

</div>

</div>

<div class="live-indicator">

<div class="live-dot"></div>

LIVE

</div>

</div>

<div class="map-card">

<div class="map-header">

<strong>
<i class="fas fa-map-location-dot"></i>
Live Journey Route
</strong>

<span id="lastUpdateText">
Waiting for location...
</span>

</div>

<div style="position:relative;">

<div id="sharedMap"></div>

<div class="map-controls">

<button
type="button"
class="map-control-btn"
id="centerTravelerBtn"
title="Center on traveler"
>
<i class="fas fa-location-crosshairs"></i>
</button>

<button
type="button"
class="map-control-btn"
id="fitJourneyBtn"
title="Show full journey"
>
<i class="fas fa-expand"></i>
</button>

</div>

</div>

</div>

<div class="info-grid">

<div class="info-card">

<i class="fas fa-gauge-high"></i>

<span class="info-label">
Speed
</span>

<span
class="info-value"
id="sharedSpeed"
>
--
</span>

</div>

<div class="info-card">

<i class="fas fa-route"></i>

<span class="info-label">
Distance Travelled
</span>

<span
class="info-value"
id="sharedDistanceTravelled"
>
--
</span>

</div>

<div class="info-card">

<i class="fas fa-flag-checkered"></i>

<span class="info-label">
Distance to Destination
</span>

<span
class="info-value"
id="sharedDistanceRemaining"
>
--
</span>

</div>

<div class="info-card">

<i class="fas fa-clock"></i>

<span class="info-label">
Last Update
</span>

<span
class="info-value"
id="sharedLastUpdate"
>
--
</span>

</div>

</div>

<div class="footer">

<span>
© 2026 SafariTrak
</span>

<span>
Live location is shared by the traveler.
</span>

</div>

</div>

</main>

</div>

<script>

window.SafariTrakSharedJourney = {
    journeyId: <?= (int)$journeyId ?>,
    startLat: <?= is_numeric($journey['start_lat']) ? (float)$journey['start_lat'] : 'null' ?>,
    startLng: <?= is_numeric($journey['start_lng']) ? (float)$journey['start_lng'] : 'null' ?>,
    destinationLat: <?= is_numeric($journey['end_lat']) ? (float)$journey['end_lat'] : 'null' ?>,
    destinationLng: <?= is_numeric($journey['end_lng']) ? (float)$journey['end_lng'] : 'null' ?>,
    status: <?= json_encode($journey['status']) ?>
};

</script>

<script
src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
></script>

<script src="view-shared.js"></script>

</body>

</html>