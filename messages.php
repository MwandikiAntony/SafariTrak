<?php
session_start();
$userName = $_SESSION['user_name'] ?? 'Traveler';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Messages</title>
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
    <a class="active" href="messages.php"><i class="fa-regular fa-message"></i>Messages <em>3</em></a>
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
  <div><label>STAY CONNECTED</label><h1>Messages</h1></div>
  <div class="head-actions"><button><i class="fa-regular fa-bell"></i></button><div class="avatar">A</div></div>
</header>

<div class="content">
<div class="card">
  <div class="chat-shell">

    <div class="chat-list" id="chatList">
      <div class="chat-search"><input type="text" placeholder="Search conversations..."></div>

      <div class="chat-item active" data-chat="jm">
        <span class="person">JM</span>
        <div class="meta"><b>John Mwangi</b><small>Sure, I can see you on the map now</small></div>
      </div>

      <div class="chat-item" data-chat="mw">
        <span class="person">MW</span>
        <div class="meta"><b>Mary Wanjiku</b><small>Let me know when you arrive</small></div>
      </div>

      <div class="chat-item" data-chat="pk">
        <span class="person">PK</span>
        <div class="meta"><b>Peter Kariuki</b><small>Safe travels!</small></div>
      </div>

    </div>

    <div class="chat-panel">
      <div class="chat-head" id="chatHead">
        <span class="person">JM</span>
        <div><b>John Mwangi</b><small>&#9679; Watching your journey</small></div>
      </div>

      <div class="chat-messages" id="chatMessages"></div>

      <form class="chat-input" id="chatForm">
        <input type="text" id="chatInput" placeholder="Type a message..." autocomplete="off">
        <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
      </form>
    </div>

  </div>
</div>
</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>
<script src="dashboard.js"></script>
<script src="messages.js"></script>
</body>
</html>
