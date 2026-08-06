<?php
require __DIR__ . '/backend/includes/auth-guard.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | My Journeys</title>
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
    <div class="account"><span><?= htmlspecialchars(strtoupper(substr($userName, 0, 1))) ?></span><div><b><?= htmlspecialchars($userName) ?></b><small>Traveler</small></div></div>
  </div>
</aside>

<main>
<header>
  <button class="menu" id="menu"><i class="fa-solid fa-bars"></i></button>
  <div><label>YOUR TRIPS</label><h1>My Journeys</h1></div>
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

<div class="page-head">
  <div><h2>Everywhere you have travelled</h2><p>See your active trip, look back at past journeys, or plan a new one.</p></div>
  <div style="display:flex;gap:10px;flex-wrap:wrap">
    <a class="btn-ghost" href="group-travel.php"><i class="fa-solid fa-user-group"></i>Group travel</a>
    <a class="btn-primary" href="start-journey.php"><i class="fa-solid fa-plus"></i>Start a journey</a>
  </div>
</div>

<div class="tabs" data-tab-group="journeys">
  <button type="button" class="tab active" data-tab="all">All</button>
  <button type="button" class="tab" data-tab="active">Active</button>
  <button type="button" class="tab" data-tab="completed">Completed</button>
  <button type="button" class="tab" data-tab="cancelled">Cancelled</button>
  <button type="button" class="tab" data-tab="group">Group</button>
</div>

<div class="card">
  <div class="journey-list" id="journeyList">

    <div class="journey-row" data-status="active" data-open-modal="journeyModal1">
      <div class="jicon"><i class="fa-solid fa-route"></i></div>
      <div class="jinfo"><b>Nairobi &rarr; Nyeri</b><small>Started today at 8:40 AM &middot; Shared with 2 contacts</small></div>
      <div class="jmeta"><strong>148 km</strong><span class="badge active">In progress</span></div>
    </div>

    <div class="journey-row" data-status="group" data-open-modal="journeyModalGroup1">
      <div class="jicon"><i class="fa-solid fa-user-group"></i></div>
      <div class="jinfo"><b>Family trip: Nairobi &rarr; Diani</b><small>Group journey &middot; 4 members &middot; Departs Friday, 6:00 AM</small></div>
      <div class="jmeta"><strong>490 km</strong><span class="badge active">Upcoming</span></div>
    </div>

    <div class="journey-row" data-status="completed" data-open-modal="journeyModal2">
      <div class="jicon"><i class="fa-solid fa-check"></i></div>
      <div class="jinfo"><b>Nairobi &rarr; Meru</b><small>Completed &middot; Yesterday, arrived 1:52 PM</small></div>
      <div class="jmeta"><strong>263 km</strong><span class="badge completed">Completed</span></div>
    </div>

    <div class="journey-row" data-status="completed" data-open-modal="journeyModal3">
      <div class="jicon"><i class="fa-solid fa-check"></i></div>
      <div class="jinfo"><b>Nairobi &rarr; Nakuru</b><small>Completed &middot; 29 Jul, arrived 11:20 AM</small></div>
      <div class="jmeta"><strong>156 km</strong><span class="badge completed">Completed</span></div>
    </div>

    <div class="journey-row" data-status="completed" data-open-modal="journeyModal4">
      <div class="jicon"><i class="fa-solid fa-check"></i></div>
      <div class="jinfo"><b>Meru &rarr; Nairobi</b><small>Completed &middot; 25 Jul, arrived 4:05 PM</small></div>
      <div class="jmeta"><strong>263 km</strong><span class="badge completed">Completed</span></div>
    </div>

    <div class="journey-row" data-status="cancelled" data-open-modal="journeyModal5">
      <div class="jicon"><i class="fa-solid fa-xmark"></i></div>
      <div class="jinfo"><b>Nairobi &rarr; Naivasha</b><small>Cancelled &middot; 20 Jul before departure</small></div>
      <div class="jmeta"><strong>93 km</strong><span class="badge cancelled">Cancelled</span></div>
    </div>

  </div>
  <p class="hint" id="emptyState" style="display:none;padding:0 21px 21px;color:var(--muted);font-size:11px">No journeys in this category yet.</p>
</div>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<div class="modal-overlay" id="journeyModalGroup1">
  <div class="modal">
    <div class="modal-head"><div><h3>Family trip: Nairobi &rarr; Diani</h3><p>Group journey &middot; departs Friday, 6:00 AM</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body">
      <p><b>Distance:</b> 490 km &middot; <b>Members:</b> 4</p>
      <div class="share-contacts" style="margin-top:10px">
        <div class="share-contact-row"><span class="person">JM</span><span>John Mwangi</span></div>
        <div class="share-contact-row"><span class="person">MW</span><span>Mary Wanjiku</span></div>
        <div class="share-contact-row"><span class="person">PK</span><span>Peter Kariuki</span></div>
        <div class="share-contact-row"><span class="person">You</span><span>You (organizer)</span></div>
      </div>
    </div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Close</button>
      <a class="primary" href="group-travel.php">Manage group</a>
    </div>
  </div>
</div>

<div class="modal-overlay" id="journeyModal1">
  <div class="modal">
    <div class="modal-head"><div><h3>Nairobi &rarr; Nyeri</h3><p>In progress &middot; started 8:40 AM</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body">
      <p><b>Distance:</b> 148 km &middot; <b>ETA:</b> 11:15 AM</p>
      <p style="margin-top:8px"><b>Shared with:</b> John Mwangi, Mary Wanjiku</p>
      <p style="margin-top:8px">Live tracking and route details will show here once the map is connected to your journey data.</p>
    </div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Close</button>
      <a class="primary" href="live-tracking.php">View on map</a>
    </div>
  </div>
</div>

<div class="modal-overlay" id="journeyModal2">
  <div class="modal">
    <div class="modal-head"><div><h3>Nairobi &rarr; Meru</h3><p>Completed yesterday</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body">
      <p><b>Distance:</b> 263 km &middot; <b>Duration:</b> 4h 12m</p>
      <p style="margin-top:8px">Departed 9:40 AM, arrived 1:52 PM. No route deviations were flagged during this trip.</p>
    </div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<div class="modal-overlay" id="journeyModal3">
  <div class="modal">
    <div class="modal-head"><div><h3>Nairobi &rarr; Nakuru</h3><p>Completed 29 Jul</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body">
      <p><b>Distance:</b> 156 km &middot; <b>Duration:</b> 2h 30m</p>
      <p style="margin-top:8px">Departed 8:50 AM, arrived 11:20 AM. Shared with Mary Wanjiku for the full trip.</p>
    </div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<div class="modal-overlay" id="journeyModal4">
  <div class="modal">
    <div class="modal-head"><div><h3>Meru &rarr; Nairobi</h3><p>Completed 25 Jul</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body">
      <p><b>Distance:</b> 263 km &middot; <b>Duration:</b> 4h 25m</p>
      <p style="margin-top:8px">Departed 11:40 AM, arrived 4:05 PM. A short stop was recorded near Nyeri.</p>
    </div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<div class="modal-overlay" id="journeyModal5">
  <div class="modal">
    <div class="modal-head"><div><h3>Nairobi &rarr; Naivasha</h3><p>Cancelled 20 Jul</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body">
      <p>This journey was cancelled before departure and no distance was recorded.</p>
    </div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<script src="dashboard.js"></script>
<script src="journeys.js"></script>
</body>
</html>
