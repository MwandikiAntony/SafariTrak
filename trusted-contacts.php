<?php
session_start();
$userName = $_SESSION['user_name'] ?? 'Traveler';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Trusted Contacts</title>
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
    <a href="messages.php"><i class="fa-regular fa-message"></i>Messages <em>3</em></a>
    <a class="active" href="trusted-contacts.php"><i class="fa-solid fa-user-group"></i>Trusted Contacts</a>
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
  <div><label>PEOPLE YOU TRUST</label><h1>Trusted Contacts</h1></div>
  <div class="head-actions"><button><i class="fa-regular fa-bell"></i></button><div class="avatar">A</div></div>
</header>

<div class="content">

<div class="page-head">
  <div><h2>Who can keep an eye on you</h2><p>Manage who can see your journeys and get notified if something goes wrong.</p></div>
  <button type="button" class="btn-primary" data-open-modal="addContactModal"><i class="fa-solid fa-user-plus"></i>Add trusted contact</button>
</div>

<div class="contacts-grid">

  <div class="contact-card">
    <div class="top"><span class="person">JM</span><div><b>John Mwangi</b><small>Brother &middot; &#9679; Available</small></div></div>
    <div class="permission-row"><span>See my live location</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="permission-row"><span>Journey start and end alerts</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="permission-row"><span>SOS alerts</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="card-buttons"><a href="messages.php" class="btn-ghost">Message</a><button type="button" class="btn-ghost remove-btn" data-open-modal="removeModalJM">Remove</button></div>
  </div>

  <div class="contact-card">
    <div class="top"><span class="person">MW</span><div><b>Mary Wanjiku</b><small>Spouse &middot; &#9679; Available</small></div></div>
    <div class="permission-row"><span>See my live location</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="permission-row"><span>Journey start and end alerts</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="permission-row"><span>SOS alerts</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="card-buttons"><a href="messages.php" class="btn-ghost">Message</a><button type="button" class="btn-ghost remove-btn" data-open-modal="removeModalMW">Remove</button></div>
  </div>

  <div class="contact-card">
    <div class="top"><span class="person">PK</span><div><b>Peter Kariuki</b><small>Colleague &middot; &#9679; Offline</small></div></div>
    <div class="permission-row"><span>See my live location</span><label class="toggle"><input type="checkbox"><span></span></label></div>
    <div class="permission-row"><span>Journey start and end alerts</span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="permission-row"><span>SOS alerts</span><label class="toggle"><input type="checkbox"><span></span></label></div>
    <div class="card-buttons"><a href="messages.php" class="btn-ghost">Message</a><button type="button" class="btn-ghost remove-btn" data-open-modal="removeModalPK">Remove</button></div>
  </div>

  <button type="button" class="add-contact-card" data-open-modal="addContactModal">
    <i class="fa-solid fa-user-plus"></i>
    <span>Add someone new</span>
  </button>

</div>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<div class="modal-overlay" id="addContactModal">
  <div class="modal">
    <div class="modal-head"><div><h3>Add a trusted contact</h3><p>Invite someone to keep track of your journeys.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body">
      <div class="form-field" style="margin-bottom:12px"><label>Full name</label><input type="text" placeholder="e.g. Grace Njeri"></div>
      <div class="form-field" style="margin-bottom:12px"><label>Phone number</label><input type="tel" placeholder="e.g. 0712 345 678"></div>
      <div class="form-field"><label>Relationship</label><input type="text" placeholder="e.g. Sister, Friend, Colleague"></div>
    </div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="primary" onclick="alert('Once the backend is connected, this will send an invite to your new trusted contact.')">Send invite</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="removeModalJM">
  <div class="modal">
    <div class="modal-head"><div><h3>Remove John Mwangi?</h3><p>They will no longer be able to see your journeys.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="danger" onclick="alert('Once the backend is connected, this will remove them from your trusted contacts.')">Remove</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="removeModalMW">
  <div class="modal">
    <div class="modal-head"><div><h3>Remove Mary Wanjiku?</h3><p>They will no longer be able to see your journeys.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="danger" onclick="alert('Once the backend is connected, this will remove them from your trusted contacts.')">Remove</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="removeModalPK">
  <div class="modal">
    <div class="modal-head"><div><h3>Remove Peter Kariuki?</h3><p>They will no longer be able to see your journeys.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="danger" onclick="alert('Once the backend is connected, this will remove them from your trusted contacts.')">Remove</button>
    </div>
  </div>
</div>

<script src="dashboard.js"></script>
</body>
</html>