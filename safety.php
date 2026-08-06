<?php
require __DIR__ . '/backend/includes/auth-guard.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Safety</title>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div class="app">
<aside class="sidebar" id="sidebar">
  <div class="brand"><div class="logo"><i class="fa-solid fa-route"></i></div><div><b>SafariTrak</b><small>Travel smarter</small></div></div>
  <nav>
    <a href="index.php"><i class="fa-solid fa-grid-2"></i>Dashboard</a>
    <a href="my-journeys.php"><i class="fa-solid fa-map-location-dot"></i>My Journeys</a>
    <a href="live-tracking.php"><i class="fa-solid fa-location-crosshairs"></i>Live Tracking</a>
    <a href="places.php"><i class="fa-solid fa-map-pin"></i>Places</a>
    <a href="messages.php"><i class="fa-regular fa-message"></i>Messages <em>3</em></a>
    <a href="trusted-contacts.php"><i class="fa-solid fa-user-group"></i>Trusted Contacts</a>
    <a class="active" href="safety.php"><i class="fa-solid fa-shield-halved"></i>Safety</a>
  </nav>
  <div class="bottom">
    <a href="settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
    <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
    <div class="account"><span><?= htmlspecialchars(strtoupper(substr($userName, 0, 1))) ?></span><div><b><?= htmlspecialchars($userName) ?></b><small>Traveler</small></div></div>
  </div>
</aside>

<main>
<header>
  <button class="menu" id="menu"><i class="fa-solid fa-bars"></i></button>
  <div><label>YOUR SAFETY</label><h1>Safety</h1></div>
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
    <div class="avatar"><?= htmlspecialchars(strtoupper(substr($userName, 0, 1))) ?></div>
  </div>
</header>

<div class="content">

<div class="sos-panel">
  <button type="button" class="sos-btn" data-open-modal="sosModal"><i class="fa-solid fa-triangle-exclamation"></i></button>
  <div><h3>Need help right now?</h3><p>Press the SOS button to alert your trusted contacts with your current location. Only use this in a real emergency.</p></div>
</div>

<section class="lower">

  <div class="card">
    <div class="card-head"><div><label>EMERGENCY CONTACTS</label><h3>Who gets notified</h3></div><a href="trusted-contacts.php">Manage</a></div>
    <div class="rows contacts">
      <div><span class="person">JM</span><div><b>John Mwangi</b><small>Primary emergency contact</small></div><a class="msg-link" href="messages.php"><i class="fa-regular fa-message"></i></a></div>
      <div><span class="person">MW</span><div><b>Mary Wanjiku</b><small>Emergency contact</small></div><a class="msg-link" href="messages.php"><i class="fa-regular fa-message"></i></a></div>
    </div>
  </div>

  <div class="card">
    <div class="card-head"><div><label>SETTINGS</label><h3>Safety preferences</h3></div></div>
    <div style="padding:0 21px 8px">
      <div class="toggle-row"><span><b>Route deviation alerts</b><small>Notify me if I move significantly off my planned route</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
      <div class="toggle-row"><span><b>Arrival notifications</b><small>Let my trusted contacts know when I reach my destination</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
      <div class="toggle-row"><span><b>Automatic SOS on long silence</b><small>Send an alert if I have not moved or responded for a long time</small></span><label class="toggle"><input type="checkbox"><span></span></label></div>
      <div class="toggle-row"><span><b>Share location with police stations nearby</b><small>Only during an active SOS alert</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    </div>
  </div>

</section>

<div class="card" style="margin-top:18px">
  <div class="card-head"><div><label>STAYING SAFE</label><h3>Travel safety tips</h3></div></div>
  <div class="tip-list">
    <div class="tip-row"><i class="fa-solid fa-route"></i><div><b>Always share your journey</b><p>Let a trusted contact know your route before you start travelling, especially at night.</p></div></div>
    <div class="tip-row"><i class="fa-solid fa-gas-pump"></i><div><b>Plan your stops</b><p>Use the places search to find fuel stations, hospitals and hotels along your route.</p></div></div>
    <div class="tip-row"><i class="fa-solid fa-battery-three-quarters"></i><div><b>Keep your phone charged</b><p>Live tracking and the SOS button need battery. Carry a power bank on long trips.</p></div></div>
  </div>
</div>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
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

<script src="dashboard.js"></script>
</body>
</html>
