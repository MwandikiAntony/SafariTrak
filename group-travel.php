<?php
require __DIR__ . '/backend/includes/auth-guard.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Group Travel</title>
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
  <div><label>TRAVEL TOGETHER</label><h1>Group Travel</h1></div>
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
  <div><h2>Travelling as a group</h2><p>Create a group journey, invite the people coming with you, and see everyone who is authorized to be tracked.</p></div>
  <button type="button" class="btn-primary" data-open-modal="createGroupModal"><i class="fa-solid fa-plus"></i>Create group journey</button>
</div>

<div class="card">
  <div class="card-head"><div><label>YOUR GROUPS</label><h3>Group journeys</h3></div></div>
  <div class="journey-list">

    <div class="journey-row" data-open-modal="groupDetailModal1">
      <div class="jicon"><i class="fa-solid fa-user-group"></i></div>
      <div class="jinfo"><b>Family trip: Nairobi &rarr; Diani</b><small>4 members &middot; Departs Friday, 6:00 AM</small></div>
      <div class="jmeta"><strong>490 km</strong><span class="badge active">Upcoming</span></div>
    </div>

    <div class="journey-row" data-open-modal="groupDetailModal2">
      <div class="jicon"><i class="fa-solid fa-user-group"></i></div>
      <div class="jinfo"><b>Church convoy: Nairobi &rarr; Kisumu</b><small>9 members &middot; Completed 2 weeks ago</small></div>
      <div class="jmeta"><strong>350 km</strong><span class="badge completed">Completed</span></div>
    </div>

  </div>
</div>

<section class="lower" style="margin-top:18px">
  <div class="card">
    <div class="card-head"><div><label>THIS TRIP</label><h3>Family trip: Nairobi &rarr; Diani</h3></div><span class="status">4 members</span></div>
    <div id="groupMap"></div>
    <div class="legend"><span><i class="current"></i>Group members</span><span><i class="destination"></i>Destination</span></div>
  </div>

  <div class="card">
    <div class="card-head"><div><label>MEMBERS</label><h3>Who is authorized</h3></div></div>
    <div class="rows contacts">
      <div><span class="person">JM</span><div><b>John Mwangi</b><small>&#9679; Confirmed</small></div><a class="msg-link" href="messages.php"><i class="fa-regular fa-message"></i></a></div>
      <div><span class="person">MW</span><div><b>Mary Wanjiku</b><small>&#9679; Confirmed</small></div><a class="msg-link" href="messages.php"><i class="fa-regular fa-message"></i></a></div>
      <div><span class="person">PK</span><div><b>Peter Kariuki</b><small>&#9679; Invited, awaiting reply</small></div><a class="msg-link" href="messages.php"><i class="fa-regular fa-message"></i></a></div>
      <div><span class="person">You</span><div><b>You</b><small>Organizer</small></div></div>
    </div>
  </div>
</section>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<div class="modal-overlay" id="createGroupModal">
  <div class="modal">
    <div class="modal-head"><div><h3>Create a group journey</h3><p>Set up a trip and invite the people travelling with you.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body">
      <div class="form-field" style="margin-bottom:12px"><label>Trip name</label><input type="text" placeholder="e.g. Family trip to Diani"></div>
      <div class="form-field" style="margin-bottom:12px"><label>Destination</label><input type="text" placeholder="e.g. Diani Beach"></div>
      <div class="form-field" style="margin-bottom:12px"><label>Departure</label><input type="datetime-local"></div>
      <div class="form-field"><label>Invite from your trusted contacts</label>
        <div class="share-contacts">
          <div class="share-contact-row"><span class="person">JM</span><span>John Mwangi</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
          <div class="share-contact-row"><span class="person">MW</span><span>Mary Wanjiku</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
          <div class="share-contact-row"><span class="person">PK</span><span>Peter Kariuki</span><label class="toggle"><input type="checkbox"><span></span></label></div>
        </div>
      </div>
    </div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="primary" onclick="alert('Once the backend is connected, this will create the group journey and send invites.')">Create and invite</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="groupDetailModal1">
  <div class="modal">
    <div class="modal-head"><div><h3>Family trip: Nairobi &rarr; Diani</h3><p>4 members &middot; departs Friday, 6:00 AM</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>Everyone in this group can see each other's live location once the trip starts. You can remove a member at any time.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button><button type="button" class="danger" onclick="alert('Once the backend is connected, this will cancel the group journey and notify all members.')">Cancel trip</button></div>
  </div>
</div>

<div class="modal-overlay" id="groupDetailModal2">
  <div class="modal">
    <div class="modal-head"><div><h3>Church convoy: Nairobi &rarr; Kisumu</h3><p>9 members &middot; completed 2 weeks ago</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>This trip finished with all 9 members arriving safely. Total distance covered was 350 km.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="dashboard.js"></script>
<script src="notifications-widget.js"></script>
<script src="group-travel.js"></script>
</body>
</html>