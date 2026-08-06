<?php
require __DIR__ . '/backend/includes/auth-guard.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Notifications</title>
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
  <div><label>UPDATES</label><h1>Notifications</h1></div>
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
  <div><h2>Everything that needs your attention</h2><p>Journey updates, messages, and safety alerts all in one place.</p></div>
  <button type="button" class="btn-ghost" id="markAllRead"><i class="fa-solid fa-check-double"></i>Mark all as read</button>
</div>

<div class="tabs" id="notifTabs">
  <button type="button" class="tab active" data-filter="all">All</button>
  <button type="button" class="tab" data-filter="unread">Unread</button>
  <button type="button" class="tab" data-filter="safety">Safety</button>
  <button type="button" class="tab" data-filter="messages">Messages</button>
</div>

<div class="card">

  <div class="notif-date-label">TODAY</div>
  <div class="notif-page-list" data-group="today">
    <div class="notif-row unread" data-type="journey">
      <div class="nicon"><i class="fa-solid fa-route"></i></div>
      <div class="ninfo"><b>Journey started</b><p>Your trip from Nairobi to Nyeri has begun. Live tracking is on.</p><small>8:40 AM</small></div>
      <span class="unread-dot"></span>
    </div>
    <div class="notif-row unread" data-type="messages">
      <div class="nicon"><i class="fa-regular fa-message"></i></div>
      <div class="ninfo"><b>New message from Mary Wanjiku</b><p>"Let me know when you arrive"</p><small>10 minutes ago</small></div>
      <span class="unread-dot"></span>
    </div>
    <div class="notif-row" data-type="safety">
      <div class="nicon"><i class="fa-solid fa-location-arrow"></i></div>
      <div class="ninfo"><b>John Mwangi is now watching your journey</b><p>They can see your live location until you end this trip.</p><small>9:02 AM</small></div>
    </div>
  </div>

  <div class="notif-date-label">YESTERDAY</div>
  <div class="notif-page-list" data-group="yesterday">
    <div class="notif-row" data-type="journey">
      <div class="nicon"><i class="fa-solid fa-flag-checkered"></i></div>
      <div class="ninfo"><b>Journey completed</b><p>You arrived safely in Meru. Total distance 263 km.</p><small>1:52 PM</small></div>
    </div>
    <div class="notif-row" data-type="safety">
      <div class="nicon"><i class="fa-solid fa-route"></i></div>
      <div class="ninfo"><b>Route deviation cleared</b><p>You are back on your planned route to Meru.</p><small>11:20 AM</small></div>
    </div>
  </div>

  <div class="notif-date-label">EARLIER THIS WEEK</div>
  <div class="notif-page-list" data-group="earlier">
    <div class="notif-row" data-type="messages">
      <div class="nicon"><i class="fa-regular fa-message"></i></div>
      <div class="ninfo"><b>New message from Peter Kariuki</b><p>"Safe travels!"</p><small>Monday, 6:15 PM</small></div>
    </div>
    <div class="notif-row sos" data-type="safety">
      <div class="nicon"><i class="fa-solid fa-triangle-exclamation"></i></div>
      <div class="ninfo"><b>SOS test alert sent</b><p>You sent a test SOS alert to check your emergency contacts are set up correctly.</p><small>Sunday, 4:02 PM</small></div>
    </div>
    <div class="notif-row" data-type="journey">
      <div class="nicon"><i class="fa-solid fa-user-group"></i></div>
      <div class="ninfo"><b>Mary Wanjiku accepted your trusted contact request</b><p>They can now see journeys you choose to share.</p><small>Saturday, 9:40 AM</small></div>
    </div>
  </div>

</div>

<p class="notif-empty" id="notifEmptyState" style="display:none">Nothing here yet.</p>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>
<script src="dashboard.js"></script>
<script src="notifications-widget.js"></script>
<script src="notifications.js"></script>
</body>
</html>