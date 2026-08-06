<?php
require __DIR__ . '/backend/includes/auth-guard.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Start Journey</title>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div class="app">
<aside class="sidebar" id="sidebar">
  <div class="brand"><div class="logo"><i class="fa-solid fa-route"></i></div><div><b>SafariTrak</b><small>Travel smarter</small></div></div>
  <nav>
    <a href="index.php"><i class="fa-solid fa-grid-2"></i>Dashboard</a>
    <a class="active" href="my-journeys.php"><i class="fa-solid fa-map-location-dot"></i>My Journeys</a>
    <a href="live-tracking.php"><i class="fa-solid fa-location-crosshairs"></i>Live Tracking</a>
    <a href="places.php"><i class="fa-solid fa-map-pin"></i>Places</a>
    <a href="messages.php"><i class="fa-regular fa-message"></i>Messages <em>3</em></a>
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
  <div><label>PLAN A TRIP</label><h1>Start a journey</h1></div>
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

<div class="page-head">
  <div><h2>Where are you headed?</h2><p>Fill in your trip details and choose who should be able to follow along.</p></div>
  <a class="btn-ghost" href="my-journeys.php"><i class="fa-solid fa-arrow-left"></i>Back to my journeys</a>
</div>

<p style="font-size:11px;color:var(--muted);margin:-10px 0 16px">Travelling with other people? <a href="group-travel.php" style="color:var(--p);font-weight:700;text-decoration:none">Create a group journey instead</a></p>

<div class="card">
  <div class="card-head"><div><label>TRIP DETAILS</label><h3>Journey information</h3></div></div>
  <form class="form-grid" id="startJourneyForm" novalidate>

    <div class="form-field">
      <label for="startPoint">Starting point</label>
      <input type="text" id="startPoint" placeholder="e.g. Nairobi CBD" required>
    </div>

    <div class="form-field">
      <label for="endPoint">Destination</label>
      <input type="text" id="endPoint" placeholder="e.g. Meru Town" required>
    </div>

    <div class="form-field">
      <label for="transportMode">Mode of transport</label>
      <select id="transportMode">
        <option value="car">Car</option>
        <option value="bus">Bus / Matatu</option>
        <option value="motorbike">Motorbike</option>
        <option value="walking">Walking</option>
      </select>
    </div>

    <div class="form-field">
      <label for="departureTime">Planned departure</label>
      <input type="datetime-local" id="departureTime">
    </div>

    <div class="form-field full">
      <label for="journeyNote">Note for your trusted contacts</label>
      <textarea id="journeyNote" rows="3" placeholder="e.g. Travelling for a family visit, will call once I arrive"></textarea>
    </div>

    <div class="form-field full">
      <label>Share this journey with</label>
      <div class="share-contacts sj-share-contacts">
        <div class="share-contact-row"><span class="person">JM</span><span>John Mwangi</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
        <div class="share-contact-row"><span class="person">MW</span><span>Mary Wanjiku</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
        <div class="share-contact-row"><span class="person">PK</span><span>Peter Kariuki</span><label class="toggle"><input type="checkbox"><span></span></label></div>
      </div>
    </div>
    <style>
      .sj-share-contacts .person{width:22px;height:22px;font-size:7px;flex:0 0 22px}
    </style>

    <div class="form-field full" style="padding-top:4px">
      <div class="toggle-row" style="border-top:0;padding-top:0">
        <span><b>Alert me if I go off route</b><small>Get notified if your journey deviates significantly from the planned route</small></span>
        <label class="toggle"><input type="checkbox" id="deviationAlert" checked><span></span></label>
      </div>
    </div>

  </form>
  <div class="form-actions">
    <button type="button" class="btn-primary" id="submitJourney"><i class="fa-solid fa-route"></i>Start journey</button>
    <a class="btn-ghost" href="my-journeys.php">Cancel</a>
  </div>
</div>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>
<script src="dashboard.js"></script>
<script src="notifications-widget.js"></script>
<script src="start-journey.js"></script>
</body>
</html>