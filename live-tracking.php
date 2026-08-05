<?php
session_start();
require_once 'db.php';
$userName = $_SESSION['user_name'] ?? 'Traveler';
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
    <a href="messages.php"><i class="fa-regular fa-message"></i>Messages <em>3</em></a>
    <a href="trusted-contacts.php"><i class="fa-solid fa-user-group"></i>Trusted Contacts</a>
    <a href="safety.php"><i class="fa-solid fa-shield-halved"></i>Safety</a>
  </nav>
  <div class="bottom">
    <a href="settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
    <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
    <div class="account"><span>A</span><div><b><?= htmlspecialchars($userName) ?></b><small>Traveler</small></div></div>
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
        <div class="notif-list">
          <div class="notif-item unread"><i class="fa-solid fa-route"></i><div><b>Journey started</b><small>Nairobi to Nyeri &middot; 8:40 AM</small></div></div>
          <div class="notif-item unread"><i class="fa-regular fa-message"></i><div><b>New message from Mary Wanjiku</b><small>Let me know when you arrive &middot; 10 min ago</small></div></div>
          <div class="notif-item"><i class="fa-solid fa-location-arrow"></i><div><b>John Mwangi is now watching your journey</b><small>Yesterday</small></div></div>
          <div class="notif-item"><i class="fa-solid fa-flag-checkered"></i><div><b>Journey completed</b><small>Nairobi to Meru &middot; 2 days ago</small></div></div>
        </div>
      </div>
    </div>
    <div class="avatar">A</div>
  </div>
</header>

<div class="content">

<div class="page-head">
  <div><h2>Nairobi &rarr; Nyeri</h2><p>Journey in progress, started at 8:40 AM.</p></div>
  <button type="button" class="btn-ghost" data-open-modal="endJourneyModal"><i class="fa-solid fa-circle-stop"></i>End journey</button>
</div>

<div class="card map-full">
  <div class="card-head"><div><label>LIVE MAP</label><h3>Your current position</h3></div><button id="myLocation">My location</button></div>
  <div id="map"></div>
  <div class="legend"><span><i class="current"></i>Your location</span><span><i class="destination"></i>Destination</span></div>
  <div class="eta-strip">
    <div class="eta-chip"><label>DISTANCE COVERED</label><strong>62 km</strong></div>
    <div class="eta-chip"><label>REMAINING</label><strong>86 km</strong></div>
    <div class="eta-chip"><label>ESTIMATED ARRIVAL</label><strong>11:15 AM</strong></div>
    <div class="eta-chip"><label>CURRENT SPEED</label><strong>64 km/h</strong></div>
  </div>
</div>

<section class="lower">
  <div class="card">
    <div class="card-head"><div><label>WATCHING THIS JOURNEY</label><h3>People tracking you</h3></div><a href="trusted-contacts.php">Manage</a></div>
    <div class="rows contacts">
      <div><span class="person">JM</span><div><b>John Mwangi</b><small>&#9679; Watching now</small></div><a class="msg-link" href="messages.php"><i class="fa-regular fa-message"></i></a></div>
      <div><span class="person">MW</span><div><b>Mary Wanjiku</b><small>&#9679; Watching now</small></div><a class="msg-link" href="messages.php"><i class="fa-regular fa-message"></i></a></div>
    </div>
  </div>
  <div class="card">
    <div class="card-head"><div><label>SAFETY</label><h3>While you travel</h3></div></div>
    <div class="tip-list">
      <div class="tip-row"><i class="fa-solid fa-route"></i><div><b>Route deviation alerts are on</b><p>You will be notified if you move significantly off the planned route.</p></div></div>
      <div class="tip-row"><i class="fa-solid fa-triangle-exclamation"></i><div><b>SOS is one tap away</b><p>Use the emergency button on the Safety page if you need urgent help.</p></div></div>
      <div class="tip-row"><i class="fa-solid fa-gas-pump"></i><div><b>Need a stop along the way?</b><p><a href="places.php" style="color:var(--p);font-weight:700;text-decoration:none">Find nearby hospitals, fuel stations, hotels and more</a></p></div></div>
    </div>
  </div>
</section>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<div class="modal-overlay" id="endJourneyModal">
  <div class="modal">
    <div class="modal-head"><div><h3>End this journey?</h3><p>Your trusted contacts will be notified that you have arrived.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body">
      <p>Make sure you have safely reached your destination before ending tracking.</p>
    </div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Keep travelling</button>
      <a class="primary" href="my-journeys.php">End journey</a>
    </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="dashboard.js"></script>
<script src="tracking.js"></script>
</body>
</html>
