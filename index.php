<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
$displayName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Traveler';
$userRole    = ucfirst($_SESSION['role'] ?? 'Traveler');
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Dashboard</title>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
</head>
<body>
<div class="app">
<aside class="sidebar" id="sidebar">
  <div class="brand"><div class="logo"><i class="fa-solid fa-route"></i></div><div><b>SafariTrak</b><small>Travel smarter</small></div></div>
  <nav>
    <a class="active" href="index.php"><i class="fa-solid fa-grid-2"></i>Dashboard</a>
    <a href="my-journeys.php"><i class="fa-solid fa-map-location-dot"></i>My Journeys</a>
    <a href="live-tracking.php"><i class="fa-solid fa-location-crosshairs"></i>Live Tracking</a>
    <a href="places.php"><i class="fa-solid fa-map-pin"></i>Places</a>
    <a href="messages.php"><i class="fa-regular fa-message"></i>Messages <em>3</em></a>
    <a href="trusted-contacts.php"><i class="fa-solid fa-user-group"></i>Trusted Contacts</a>
    <a href="safety.php"><i class="fa-solid fa-shield-halved"></i>Safety</a>
  </nav>
  <div class="bottom">
    <a href="settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
    <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
    <div class="account"><span>A</span><div><b><?= htmlspecialchars($displayName) ?></b><small><?= htmlspecialchars($userRole) ?></small></div></div>
  </div>
</aside>

<main>
<header>
  <button class="menu" id="menu"><i class="fa-solid fa-bars"></i></button>
  <div><label>YOUR TRAVEL COMMAND CENTER</label><h1>Good morning, <?= htmlspecialchars($userName) ?></h1></div>
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
<section class="hero">
  <div>
    <label>READY FOR YOUR NEXT JOURNEY?</label>
    <h2>Where are you going today?</h2>
    <p>Plan your route, track your journey and keep the people you trust connected along the way.</p>
    <div class="search"><i class="fa-solid fa-magnifying-glass"></i><input id="destination" placeholder="Search a destination..."><button id="locate">Use my location</button></div>
    <div class="shortcuts"><button type="button">Home</button><button type="button">Work</button><a class="shortcut-link" href="places.php">Nearby places</a></div>
  </div>
  <div class="hero-note"><i class="fa-solid fa-compass"></i><b>Travel with confidence.</b><span>Navigate. Track. Share. Connect. Stay safe.</span></div>
</section>

<section class="grid">
  <div class="card map-card">
    <div class="card-head"><div><label>LIVE MAP</label><h3>Explore your journey</h3></div><button id="myLocation">My location</button></div>
    <div id="map"></div>
    <div class="legend"><span><i class="current"></i>Your location</span><span><i class="destination"></i>Destination</span></div>
  </div>

  <div class="card actions">
    <div class="card-head"><div><label>QUICK ACTIONS</label><h3>What do you need?</h3></div></div>
    <a class="action primary" href="start-journey.php"><i class="fa-solid fa-route"></i><span><b>Start Journey</b><small>Plan and begin a trip</small></span><strong>›</strong></a>
    <button class="action" type="button" data-open-modal="shareModal"><i class="fa-solid fa-location-arrow"></i><span><b>Share Location</b><small>Let someone know where you are</small></span><strong>›</strong></button>
    <a class="action" href="trusted-contacts.php"><i class="fa-solid fa-user-group"></i><span><b>Trusted Contacts</b><small>Manage people you trust</small></span><strong>›</strong></a>
    <button class="action sos" type="button" data-open-modal="sosModal"><i class="fa-solid fa-triangle-exclamation"></i><span><b>Emergency / SOS</b><small>Get help when you need it</small></span><strong>›</strong></button>
  </div>
</section>

<section class="card journey">
  <div class="card-head"><div><label>JOURNEY STATUS</label><h3>Active journey</h3></div><span class="status">● Not travelling</span></div>
  <div class="empty"><i class="fa-solid fa-road"></i><div><b>No active journey</b><p>Start a journey to see live tracking, ETA and safety information here.</p></div><a class="empty-link" href="start-journey.php">Start a journey</a></div>
</section>

<section class="lower">
  <div class="card">
    <div class="card-head"><div><label>HISTORY</label><h3>Recent journeys</h3></div><a href="my-journeys.php">View all</a></div>
    <div class="rows">
      <a href="my-journeys.php"><i class="fa-solid fa-check"></i><div><b>Nairobi → Meru</b><small>Completed • Yesterday</small></div><strong>263 km</strong></a>
      <a href="my-journeys.php"><i class="fa-solid fa-check"></i><div><b>Nairobi → Nakuru</b><small>Completed • 29 Jul</small></div><strong>156 km</strong></a>
      <a href="my-journeys.php"><i class="fa-solid fa-check"></i><div><b>Meru → Nairobi</b><small>Completed • 25 Jul</small></div><strong>263 km</strong></a>
    </div>
  </div>
  <div class="card">
    <div class="card-head"><div><label>PEOPLE</label><h3>Trusted contacts</h3></div><a href="trusted-contacts.php">Manage</a></div>
    <div class="rows contacts">
      <div><span class="person">JM</span><div><b>John Mwangi</b><small>● Available</small></div><a class="msg-link" href="messages.php"><i class="fa-regular fa-message"></i></a></div>
      <div><span class="person">MW</span><div><b>Mary Wanjiku</b><small>● Available</small></div><a class="msg-link" href="messages.php"><i class="fa-regular fa-message"></i></a></div>
      <div><span class="person">PK</span><div><b>Peter Kariuki</b><small>● Offline</small></div><a class="msg-link" href="messages.php"><i class="fa-regular fa-message"></i></a></div>
    </div>
  </div>
</section>

<section class="safety"><i class="fa-solid fa-shield-heart"></i><div><label>SAFETRAVEL</label><h3>Your safety stays with you.</h3><p>Add trusted contacts and enable location sharing when you want someone to monitor your journey.</p></div><a class="safety-link" href="safety.php">Safety settings</a></section>
</div>
<footer>© <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<div class="modal-overlay" id="shareModal">
  <div class="modal">
    <div class="modal-head">
      <div><h3>Share your location</h3><p>Choose who can see where you are right now.</p></div>
      <button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="share-contacts">
        <div class="share-contact-row"><span class="person">JM</span><span>John Mwangi</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
        <div class="share-contact-row"><span class="person">MW</span><span>Mary Wanjiku</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
        <div class="share-contact-row"><span class="person">PK</span><span>Peter Kariuki</span><label class="toggle"><input type="checkbox"><span></span></label></div>
      </div>
    </div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="primary" onclick="alert('Once the backend is connected, this will start sharing your live location with the people you picked.')">Start sharing</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="sosModal">
  <div class="modal sos">
    <div class="modal-head">
      <div class="sos-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
      <button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <b>Send an SOS alert?</b>
      <p>This will notify all your trusted contacts with your current location and let them know you need help. Only use this if you are in real danger or need urgent assistance.</p>
    </div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="danger" onclick="alert('Once the backend is connected, this will send an emergency alert with your location to your trusted contacts.')">Send SOS</button>
    </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="dashboard.js"></script>
<script src="dashboard-map.js"></script>
</body>
</html>

