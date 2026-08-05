<?php
session_start();
$orgName = $_SESSION['org_name'] ?? 'Meru Transport Sacco';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Organization Overview</title>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div class="app">
<aside class="sidebar" id="sidebar">
  <div class="brand"><div class="logo"><i class="fa-solid fa-route"></i></div><div><b>SafariTrak</b><small>Travel smarter</small><span class="org-badge">ORGANIZATION</span></div></div>
  <nav>
    <a class="active" href="admin-dashboard.php"><i class="fa-solid fa-grid-2"></i>Overview</a>
    <a href="admin-travelers.php"><i class="fa-solid fa-users"></i>Travelers</a>
    <a href="admin-groups.php"><i class="fa-solid fa-user-group"></i>Group Journeys</a>
    <a href="admin-reports.php"><i class="fa-solid fa-chart-simple"></i>Reports</a>
  </nav>
  <div class="bottom">
    <a href="index.php"><i class="fa-solid fa-arrow-right-arrow-left"></i>Switch to traveler view</a>
    <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
    <div class="account"><span>O</span><div><b><?= htmlspecialchars($orgName) ?></b><small>Organization admin</small></div></div>
  </div>
</aside>

<main>
<header>
  <button class="menu" id="menu"><i class="fa-solid fa-bars"></i></button>
  <div><label>ORGANIZATION OVERVIEW</label><h1><?= htmlspecialchars($orgName) ?></h1></div>
  <div class="head-actions"><button><i class="fa-regular fa-bell"></i></button><div class="avatar">O</div></div>
</header>

<div class="content">

<div class="page-head">
  <div><h2>How your organization is travelling</h2><p>A snapshot of your travelers, group journeys, and safety activity.</p></div>
</div>

<div class="stat-grid">
  <div class="stat-card"><label>TOTAL TRAVELERS</label><strong>212</strong><small>18 added this month</small></div>
  <div class="stat-card"><label>ACTIVE JOURNEYS</label><strong>14</strong><small>Right now</small></div>
  <div class="stat-card"><label>UPCOMING GROUP TRIPS</label><strong>3</strong><small>Next 7 days</small></div>
  <div class="stat-card"><label>SOS ALERTS</label><strong>1</strong><small>This month, resolved</small></div>
</div>

<div class="card">
  <div class="card-head"><div><label>RECENT ACTIVITY</label><h3>What's happening</h3></div></div>
  <div class="journey-list">
    <div class="journey-row">
      <div class="jicon"><i class="fa-solid fa-route"></i></div>
      <div class="jinfo"><b>James Kariuki started a journey</b><small>Meru &rarr; Nairobi &middot; 10 minutes ago</small></div>
      <div class="jmeta"><span class="badge active">Active</span></div>
    </div>
    <div class="journey-row">
      <div class="jicon"><i class="fa-solid fa-user-group"></i></div>
      <div class="jinfo"><b>Staff outing group journey created</b><small>Organized by Grace Njeri &middot; 9 members &middot; 1 hour ago</small></div>
      <div class="jmeta"><span class="badge active">Upcoming</span></div>
    </div>
    <div class="journey-row">
      <div class="jicon"><i class="fa-solid fa-check"></i></div>
      <div class="jinfo"><b>Sarah Wambui completed a journey</b><small>Meru &rarr; Chuka &middot; 3 hours ago</small></div>
      <div class="jmeta"><span class="badge completed">Completed</span></div>
    </div>
    <div class="journey-row">
      <div class="jicon"><i class="fa-solid fa-triangle-exclamation"></i></div>
      <div class="jinfo"><b>SOS alert resolved</b><small>David Mutuku &middot; flagged and closed by admin &middot; yesterday</small></div>
      <div class="jmeta"><span class="badge completed">Resolved</span></div>
    </div>
  </div>
</div>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>
<script src="dashboard.js"></script>
</body>
</html>
