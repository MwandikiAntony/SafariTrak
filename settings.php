<?php
require __DIR__ . '/backend/includes/auth-guard.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Settings</title>
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
    <a class="active" href="settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
    <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
    <div class="account"><span id="sidebarAvatar"><?= st_avatar_inner($currentUser) ?></span><div><b><?= htmlspecialchars($userName) ?></b><small>Traveler</small></div></div>
  </div>
</aside>

<main>
<header>
  <button class="menu" id="menu"><i class="fa-solid fa-bars"></i></button>
  <div><label>YOUR ACCOUNT</label><h1>Settings</h1></div>
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
    <div class="avatar" id="headerAvatar"><?= st_avatar_inner($currentUser) ?></div>
  </div>
</header>

<div class="content">

<div class="card">
  <div class="settings-tabs" data-tab-group="settings">
    <button type="button" class="tab active" data-tab="profile">Profile</button>
    <button type="button" class="tab" data-tab="notifications">Notifications</button>
    <button type="button" class="tab" data-tab="privacy">Privacy</button>
    <button type="button" class="tab" data-tab="account">Account</button>
  </div>

  <div class="settings-panel active" data-tab-panel-group="settings" data-tab-panel="profile">
    <div class="avatar-row">
      <div class="big-avatar" id="profileAvatarPreview"><?= st_avatar_inner($currentUser) ?></div>
      <div>
        <input type="file" id="avatarFileInput" accept="image/png,image/jpeg,image/webp" style="display:none">
        <button type="button" class="btn-ghost" id="changePhotoBtn">Change photo</button>
        <?php if (!empty($currentUser['avatar_path'])): ?>
        <button type="button" class="btn-ghost" id="removePhotoBtn" style="color:#c94b4b">Remove photo</button>
        <?php endif; ?>
        <p class="hint" style="margin-top:8px">JPG, PNG or WEBP, up to 4 MB</p>
      </div>
    </div>
    <div class="form-grid" style="padding:0">
      <div class="form-field"><label>Full name</label><input type="text" value="<?= htmlspecialchars($userName) ?>"></div>
      <div class="form-field"><label>Phone number</label><input type="tel" placeholder="0712 345 678"></div>
      <div class="form-field full"><label>Email address</label><input type="email" placeholder="you@example.com"></div>
      <div class="form-field full"><label>Home address</label><input type="text" placeholder="Used to suggest your usual routes"></div>
    </div>
    <div class="form-actions" style="padding-left:0;padding-right:0">
      <button type="button" class="btn-primary" onclick="alert('Once the backend is connected, this will update your profile.')">Save changes</button>
    </div>
  </div>

  <div class="settings-panel" data-tab-panel-group="settings" data-tab-panel="notifications">
    <div class="toggle-row"><span><b>Journey started and completed</b><small>Get notified when your journeys begin and end</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="toggle-row"><span><b>New messages</b><small>Get notified when a trusted contact sends you a message</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="toggle-row"><span><b>Route deviation</b><small>Get notified if you move off your planned route</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="toggle-row"><span><b>SOS alerts from contacts</b><small>Get notified if someone you are watching sends an SOS</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="toggle-row"><span><b>Product updates</b><small>Occasional emails about new SafariTrak features</small></span><label class="toggle"><input type="checkbox"><span></span></label></div>
  </div>

  <div class="settings-panel" data-tab-panel-group="settings" data-tab-panel="privacy">
    <div class="toggle-row"><span><b>Show my journey history to trusted contacts</b><small>They can see your past trips, not just live journeys</small></span><label class="toggle"><input type="checkbox"><span></span></label></div>
    <div class="toggle-row"><span><b>Allow group journeys</b><small>Let organizers add you to group trips</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="toggle-row"><span><b>Discoverable by phone number</b><small>Let people find and add you as a trusted contact using your number</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
  </div>

  <div class="settings-panel" data-tab-panel-group="settings" data-tab-panel="account">
    <div class="form-field" style="max-width:360px;margin-bottom:14px"><label>Current password</label><input type="password" placeholder="Enter current password"></div>
    <div class="form-field" style="max-width:360px;margin-bottom:14px"><label>New password</label><input type="password" placeholder="Enter new password"></div>
    <button type="button" class="btn-primary" onclick="alert('Once the backend is connected, this will update your password.')">Update password</button>
    <hr style="border:0;border-top:1px solid var(--line);margin:22px 0">
    <button type="button" class="btn-ghost" style="color:#c94b4b;border-color:#f3d4d4" data-open-modal="deleteAccountModal">Delete my account</button>
    <hr style="border:0;border-top:1px solid var(--line);margin:22px 0">
    <a class="btn-ghost" href="admin-dashboard.php"><i class="fa-solid fa-building"></i>Manage an organization</a>
  </div>

</div>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<div class="modal-overlay" id="deleteAccountModal">
  <div class="modal">
    <div class="modal-head"><div><h3>Delete your account?</h3><p>This removes all your journeys, contacts and messages.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>This cannot be undone. We recommend downloading your journey history first once that feature is available.</p></div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="danger" onclick="alert('Once the backend is connected, this will permanently delete your account.')">Delete account</button>
    </div>
  </div>
</div>

<script src="dashboard.js"></script>
<script src="notifications-widget.js"></script>
<script src="settings.js"></script>
</body>
</html>
