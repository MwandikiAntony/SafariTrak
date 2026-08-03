<?php
session_start();
$userName = $_SESSION['user_name'] ?? 'Traveler';
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
    <a class="active" href="#"><i class="fa-solid fa-grid-2"></i>Dashboard</a>
    <a href="#"><i class="fa-solid fa-map-location-dot"></i>My Journeys</a>
    <a href="#"><i class="fa-solid fa-location-crosshairs"></i>Live Tracking</a>
    <a href="#"><i class="fa-regular fa-message"></i>Messages <em>3</em></a>
    <a href="#"><i class="fa-solid fa-user-group"></i>Trusted Contacts</a>
    <a href="#"><i class="fa-solid fa-shield-halved"></i>Safety</a>
  </nav>
  <div class="bottom">
    <a href="#"><i class="fa-solid fa-gear"></i>Settings</a>
    <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
    <div class="account"><span>A</span><div><b><?= htmlspecialchars($userName) ?></b><small>Traveler</small></div></div>
  </div>
</aside>

<main>
<header>
  <button class="menu" id="menu"><i class="fa-solid fa-bars"></i></button>
  <div><label>YOUR TRAVEL COMMAND CENTER</label><h1>Good morning, <?= htmlspecialchars($userName) ?></h1></div>
  <div class="head-actions"><button><i class="fa-regular fa-bell"></i></button><div class="avatar">A</div></div>
</header>

<div class="content">
<section class="hero">
  <div>
    <label>READY FOR YOUR NEXT JOURNEY?</label>
    <h2>Where are you going today?</h2>
    <p>Plan your route, track your journey and keep the people you trust connected along the way.</p>
    <div class="search"><i class="fa-solid fa-magnifying-glass"></i><input id="destination" placeholder="Search a destination..."><button id="locate">Use my location</button></div>
    <div class="shortcuts"><button>Home</button><button>Work</button><button>Recent places</button></div>
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
    <button class="action primary"><i class="fa-solid fa-route"></i><span><b>Start Journey</b><small>Plan and begin a trip</small></span><strong>›</strong></button>
    <button class="action"><i class="fa-solid fa-location-arrow"></i><span><b>Share Location</b><small>Let someone know where you are</small></span><strong>›</strong></button>
    <button class="action"><i class="fa-solid fa-user-group"></i><span><b>Trusted Contacts</b><small>Manage people you trust</small></span><strong>›</strong></button>
    <button class="action sos"><i class="fa-solid fa-life-ring"></i><span><b>Emergency / SOS</b><small>Get help when you need it</small></span><strong>›</strong></button>
  </div>
</section>

<section class="card journey">
  <div class="card-head"><div><label>JOURNEY STATUS</label><h3>Active journey</h3></div><span class="status">● Not travelling</span></div>
  <div class="empty"><i class="fa-solid fa-road"></i><div><b>No active journey</b><p>Start a journey to see live tracking, ETA and safety information here.</p></div><button>Start a journey</button></div>
</section>

<section class="lower">
  <div class="card">
    <div class="card-head"><div><label>HISTORY</label><h3>Recent journeys</h3></div><a href="#">View all</a></div>
    <div class="rows">
      <div><i class="fa-solid fa-check"></i><div><b>Nairobi → Meru</b><small>Completed • Yesterday</small></div><strong>263 km</strong></div>
      <div><i class="fa-solid fa-check"></i><div><b>Nairobi → Nakuru</b><small>Completed • 29 Jul</small></div><strong>156 km</strong></div>
      <div><i class="fa-solid fa-check"></i><div><b>Meru → Nairobi</b><small>Completed • 25 Jul</small></div><strong>263 km</strong></div>
    </div>
  </div>
  <div class="card">
    <div class="card-head"><div><label>PEOPLE</label><h3>Trusted contacts</h3></div><a href="#">Manage</a></div>
    <div class="rows contacts">
      <div><span class="person">JM</span><div><b>John Mwangi</b><small>● Available</small></div><button><i class="fa-regular fa-message"></i></button></div>
      <div><span class="person">MW</span><div><b>Mary Wanjiku</b><small>● Available</small></div><button><i class="fa-regular fa-message"></i></button></div>
      <div><span class="person">PK</span><div><b>Peter Kariuki</b><small>● Offline</small></div><button><i class="fa-regular fa-message"></i></button></div>
    </div>
  </div>
</section>

<section class="safety"><i class="fa-solid fa-shield-heart"></i><div><label>SAFETRAVEL</label><h3>Your safety stays with you.</h3><p>Add trusted contacts and enable location sharing when you want someone to monitor your journey.</p></div><button>Safety settings</button></section>
</div>
<footer>© <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="dashboard.js"></script>
</body>
</html>