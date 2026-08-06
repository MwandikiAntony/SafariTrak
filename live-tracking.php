<?php
require __DIR__ . '/backend/includes/auth-guard.php';

$db = safaritrak_db();

$activeStmt = $db->prepare('SELECT * FROM journeys WHERE user_id = ? AND status = "active" LIMIT 1');
$activeStmt->execute([$currentUser['id']]);
$activeJourney = $activeStmt->fetch();

$watchers = [];
if ($activeJourney) {
    $watchersStmt = $db->prepare(
        'SELECT COALESCE(u.full_name, tc.invite_name) AS display_name, tc.contact_user_id
         FROM journey_shares js
         JOIN trusted_contacts tc ON tc.id = js.trusted_contact_id
         LEFT JOIN users u ON u.id = tc.contact_user_id
         WHERE js.journey_id = ?'
    );
    $watchersStmt->execute([$activeJourney['id']]);
    $watchers = $watchersStmt->fetchAll();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Live Tracking</title>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
</head>
<body>
<div class="app">
<aside class="sidebar" id="sidebar">
  <div class="brand"><div class="logo"><i class="fa-solid fa-route"></i></div><div><b>SafariTrak</b><small>Travel smarter</small></div></div>
  <nav>
    <a href="index.php"><i class="fa-solid fa-grid-2"></i>Dashboard</a>
    <a href="my-journeys.php"><i class="fa-solid fa-map-location-dot"></i>My Journeys</a>
    <a class="active" href="live-tracking.php"><i class="fa-solid fa-location-crosshairs"></i>Live Tracking</a>
    <a href="places.php"><i class="fa-solid fa-map-pin"></i>Places</a>
    <a href="messages.php"><i class="fa-regular fa-message"></i>Messages</a>
    <a href="trusted-contacts.php"><i class="fa-solid fa-user-group"></i>Trusted Contacts</a>
    <a href="safety.php"><i class="fa-solid fa-shield-halved"></i>Safety</a>
  </nav>
  <div class="bottom">
    <a href="settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
    <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
    <div class="account"><span><?= st_avatar_inner($currentUser) ?></span><div><b><?= htmlspecialchars($userName) ?></b><small>Traveler</small></div></div>
  </div>
</aside>

<main>
<header>
  <button class="menu" id="menu"><i class="fa-solid fa-bars"></i></button>
  <div><label>ON THE ROAD</label><h1>Live Tracking</h1></div>
  <div class="head-actions">
    <div class="notif-wrap">
      <button type="button" class="notif-bell" id="notifBell"><i class="fa-regular fa-bell"></i><span class="notif-dot" id="notifDot"></span></button>
      <div class="notif-dropdown" id="notifDropdown">
        <div class="notif-dropdown-head"><b>Notifications</b><a href="notifications.php">View all</a></div>
        <div class="notif-list" id="notifDropdownList">
          <p class="notif-empty">Loading...</p>
        </div>
      </div>
    </div>
    <div class="avatar"><?= st_avatar_inner($currentUser) ?></div>
  </div>
</header>

<div class="content">

<?php if (!$activeJourney): ?>

<div class="card">
  <div class="empty" style="margin:21px">
    <i class="fa-solid fa-location-crosshairs"></i>
    <div><b>No journey in progress</b><p>Start a journey and your live position, distance covered and safety tools will show up here.</p></div>
    <a class="empty-link" href="start-journey.php">Start a journey</a>
  </div>
</div>

<?php else: ?>

<div class="page-head">
  <div><h2><?= htmlspecialchars($activeJourney['start_label']) ?> &rarr; <?= htmlspecialchars($activeJourney['end_label']) ?></h2><p>Journey in progress, started at <?= (new DateTime($activeJourney['started_at']))->format('g:i A') ?>.</p></div>
  <button type="button" class="btn-ghost" data-open-modal="endJourneyModal"><i class="fa-solid fa-circle-stop"></i>End journey</button>
</div>

<div class="card map-full">
  <div class="card-head"><div><label>LIVE MAP</label><h3>Your current position</h3></div><button id="myLocation">My location</button></div>
  <div id="map"></div>
  <div class="legend"><span><i class="current"></i>Your location</span><?php if ($activeJourney['end_lat']): ?><span><i class="destination"></i>Destination</span><?php endif; ?></div>
  <div class="eta-strip">
    <div class="eta-chip"><label>DISTANCE COVERED</label><strong id="coveredKm">0 km</strong></div>
    <div class="eta-chip"><label>TOTAL DISTANCE</label><strong id="totalKm"><?= $activeJourney['distance_km'] !== null ? number_format((float) $activeJourney['distance_km'], 1) . ' km' : 'Unknown' ?></strong></div>
    <div class="eta-chip"><label>STARTED</label><strong><?= (new DateTime($activeJourney['started_at']))->format('g:i A') ?></strong></div>
    <div class="eta-chip"><label>CURRENT SPEED</label><strong id="currentSpeed">-</strong></div>
  </div>
</div>

<section class="lower">
  <div class="card">
    <div class="card-head"><div><label>WATCHING THIS JOURNEY</label><h3>People tracking you</h3></div><a href="trusted-contacts.php">Manage</a></div>
    <div class="rows contacts">
      <?php if (empty($watchers)): ?>
      <p class="hint" style="padding:16px 21px;color:var(--muted);font-size:11px">You did not share this journey with anyone.</p>
      <?php endif; ?>
      <?php foreach ($watchers as $w): ?>
      <div><span class="person"><?= htmlspecialchars(st_initials($w['display_name'])) ?></span><div><b><?= htmlspecialchars($w['display_name']) ?></b><small>&#9679; <?= $w['contact_user_id'] ? 'Watching now' : 'Invited, not on SafariTrak yet' ?></small></div><a class="msg-link" href="messages.php"><i class="fa-regular fa-message"></i></a></div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="card">
    <div class="card-head"><div><label>SAFETY</label><h3>While you travel</h3></div></div>
    <div class="tip-list">
      <div class="tip-row"><i class="fa-solid fa-route"></i><div><b>Route deviation alerts are <?= $activeJourney['route_deviation_alert'] ? 'on' : 'off' ?></b><p>You will be notified if you move significantly off the planned route.</p></div></div>
      <div class="tip-row"><i class="fa-solid fa-triangle-exclamation"></i><div><b>SOS is one tap away</b><p>Use the emergency button on the Safety page if you need urgent help.</p></div></div>
      <div class="tip-row"><i class="fa-solid fa-gas-pump"></i><div><b>Need a stop along the way?</b><p><a href="places.php" style="color:var(--p);font-weight:700;text-decoration:none">Find nearby hospitals, fuel stations, hotels and more</a></p></div></div>
    </div>
  </div>
</section>

<?php endif; ?>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<?php if ($activeJourney): ?>
<div class="modal-overlay" id="endJourneyModal">
  <div class="modal">
    <div class="modal-head"><div><h3>End this journey?</h3><p>Your trusted contacts will be notified that you have arrived.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body">
      <p>Make sure you have safely reached your destination before ending tracking.</p>
    </div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Keep travelling</button>
      <button type="button" class="danger" id="confirmEndJourneyBtn">End journey</button>
    </div>
  </div>
</div>
<script>
const ACTIVE_JOURNEY_ID = <?= (int) $activeJourney['id'] ?>;
const JOURNEY_START_LAT = <?= $activeJourney['start_lat'] !== null ? (float) $activeJourney['start_lat'] : 'null' ?>;
const JOURNEY_START_LNG = <?= $activeJourney['start_lng'] !== null ? (float) $activeJourney['start_lng'] : 'null' ?>;
const JOURNEY_END_LAT = <?= $activeJourney['end_lat'] !== null ? (float) $activeJourney['end_lat'] : 'null' ?>;
const JOURNEY_END_LNG = <?= $activeJourney['end_lng'] !== null ? (float) $activeJourney['end_lng'] : 'null' ?>;
</script>
<?php endif; ?>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="dashboard.js"></script>
<script src="notifications-widget.js"></script>
<?php if ($activeJourney): ?>
<script src="tracking.js"></script>
<?php endif; ?>
</body>
</html>
