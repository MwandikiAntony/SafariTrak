<?php
require __DIR__ . '/backend/includes/auth-guard.php';

$journeyId = (int) ($_GET['id'] ?? 0);

$db = safaritrak_db();

$isPlatformAdminStmt = $db->prepare('SELECT id FROM platform_admins WHERE user_id = ?');
$isPlatformAdminStmt->execute([$currentUser['id']]);
$isPlatformAdmin = (bool) $isPlatformAdminStmt->fetch();

if ($isPlatformAdmin) {
    $stmt = $db->prepare(
        'SELECT j.*, u.full_name AS traveler_name, u.avatar_path AS traveler_avatar
         FROM journeys j JOIN users u ON u.id = j.user_id
         WHERE j.id = ?
         LIMIT 1'
    );
    $stmt->execute([$journeyId]);
} else {
    $stmt = $db->prepare(
        'SELECT j.*, u.full_name AS traveler_name, u.avatar_path AS traveler_avatar
         FROM journeys j
         JOIN users u ON u.id = j.user_id
         JOIN journey_shares js ON js.journey_id = j.id
         JOIN trusted_contacts tc ON tc.id = js.trusted_contact_id
         WHERE j.id = ? AND tc.contact_user_id = ? AND tc.status = "confirmed"
         LIMIT 1'
    );
    $stmt->execute([$journeyId, $currentUser['id']]);
}
$journey = $stmt->fetch();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Watching a Journey</title>
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
    <a href="messages.php"><i class="fa-regular fa-message"></i>Messages<?= $unreadConversationCount > 0 ? " <em>" . $unreadConversationCount . "</em>" : "" ?></a>
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
  <div><label>WATCHING</label><h1><?= $journey ? htmlspecialchars($journey['traveler_name']) . "'s journey" : 'Journey' ?></h1></div>
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

<?php if (!$journey): ?>

<div class="card">
  <div class="empty" style="margin:21px">
    <i class="fa-solid fa-lock"></i>
    <div><b>This journey is not available</b><p>Either it was not shared with you, or the person sharing it has stopped sharing.</p></div>
    <a class="empty-link" href="live-tracking.php">Back to Live Tracking</a>
  </div>
</div>

<?php else: ?>

<div class="page-head">
  <div>
    <h2><?= htmlspecialchars($journey['start_label']) ?> &rarr; <?= htmlspecialchars($journey['end_label']) ?></h2>
    <p>
      <?php if ($journey['status'] === 'active'): ?>
      <span class="badge active">Live</span> Started <?= (new DateTime($journey['started_at']))->format('g:i A') ?>
      <?php elseif ($journey['status'] === 'completed'): ?>
      <span class="badge completed">Completed</span> Arrived <?= $journey['ended_at'] ? (new DateTime($journey['ended_at']))->format('g:i A, j M') : '' ?>
      <?php else: ?>
      <span class="badge cancelled">Cancelled</span>
      <?php endif; ?>
    </p>
  </div>
  <a class="btn-ghost" href="messages.php?to=<?= (int) $journey['user_id'] ?>"><i class="fa-regular fa-message"></i>Message <?= htmlspecialchars($journey['traveler_name']) ?></a>
</div>

<div class="card map-full">
  <div class="card-head"><div><label>MAP</label><h3><?= htmlspecialchars($journey['traveler_name']) ?>'s route</h3></div></div>
  <div id="map"></div>
  <div class="legend"><span><i class="current"></i><?= $journey['status'] === 'active' ? 'Current position' : 'Last known position' ?></span><?php if ($journey['end_lat']): ?><span><i class="destination"></i>Destination</span><?php endif; ?></div>
  <div class="eta-strip">
    <div class="eta-chip"><label>DISTANCE COVERED</label><strong id="coveredKm">-</strong></div>
    <div class="eta-chip"><label>TOTAL DISTANCE</label><strong><?= $journey['distance_km'] !== null ? number_format((float) $journey['distance_km'], 1) . ' km' : 'Unknown' ?></strong></div>
    <div class="eta-chip"><label><?= $journey['status'] === 'active' ? 'STARTED' : 'STATUS' ?></label><strong><?= $journey['status'] === 'active' ? (new DateTime($journey['started_at']))->format('g:i A') : ucfirst($journey['status']) ?></strong></div>
    <div class="eta-chip"><label>LAST UPDATE</label><strong id="lastUpdate">-</strong></div>
  </div>
</div>

<?php if ($journey['status'] === 'active'): ?>
<p class="hint" style="color:var(--muted);font-size:11px;margin-top:14px"><i class="fa-solid fa-circle-info"></i> This page refreshes automatically while <?= htmlspecialchars($journey['traveler_name']) ?> is travelling.</p>
<?php endif; ?>

<?php endif; ?>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<?php if ($journey): ?>
<script>
window.WATCH_JOURNEY_ID = <?= (int) $journey['id'] ?>;
window.WATCH_IS_ACTIVE = <?= $journey['status'] === 'active' ? 'true' : 'false' ?>;
window.WATCH_START_LAT = <?= $journey['start_lat'] !== null ? (float) $journey['start_lat'] : 'null' ?>;
window.WATCH_START_LNG = <?= $journey['start_lng'] !== null ? (float) $journey['start_lng'] : 'null' ?>;
window.WATCH_END_LAT = <?= $journey['end_lat'] !== null ? (float) $journey['end_lat'] : 'null' ?>;
window.WATCH_END_LNG = <?= $journey['end_lng'] !== null ? (float) $journey['end_lng'] : 'null' ?>;
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<?php endif; ?>
<script src="dashboard.js"></script>
<script src="notifications-widget.js"></script>
<?php if ($journey): ?>
<script src="watch-journey.js"></script>
<?php endif; ?>
</body>
</html>
