<?php
session_start();
$orgName = $_SESSION['org_name'] ?? 'Meru Transport Sacco';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Reports</title>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div class="app">
<aside class="sidebar" id="sidebar">
  <div class="brand"><div class="logo"><i class="fa-solid fa-route"></i></div><div><b>SafariTrak</b><small>Travel smarter</small><span class="org-badge">ORGANIZATION</span></div></div>
  <nav>
    <a href="admin-dashboard.php"><i class="fa-solid fa-grid-2"></i>Overview</a>
    <a href="admin-travelers.php"><i class="fa-solid fa-users"></i>Travelers</a>
    <a href="admin-groups.php"><i class="fa-solid fa-user-group"></i>Group Journeys</a>
    <a class="active" href="admin-reports.php"><i class="fa-solid fa-chart-simple"></i>Reports</a>
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
  <div><label>THE BIG PICTURE</label><h1>Reports</h1></div>
  <div class="head-actions"><button><i class="fa-regular fa-bell"></i></button><div class="avatar">O</div></div>
</header>

<div class="content">

<div class="page-head">
  <div><h2>How your organization travelled this month</h2><p>A summary you can share with leadership or use for planning.</p></div>
  <button type="button" class="btn-ghost" onclick="alert('Once the backend is connected, this will export the report as a spreadsheet.')"><i class="fa-solid fa-file-arrow-down"></i>Export report</button>
</div>

<div class="stat-grid">
  <div class="stat-card"><label>TOTAL DISTANCE</label><strong>18,420 km</strong><small>Across all travelers this month</small></div>
  <div class="stat-card"><label>JOURNEYS COMPLETED</label><strong>486</strong><small>Up 12% from last month</small></div>
  <div class="stat-card"><label>AVERAGE JOURNEY TIME</label><strong>2h 45m</strong><small>Door to door</small></div>
  <div class="stat-card"><label>SAFETY SCORE</label><strong>98%</strong><small>Journeys with no incident</small></div>
</div>

<div class="card">
  <div class="card-head"><div><label>MOST ACTIVE TRAVELERS</label><h3>Top 5 this month</h3></div></div>
  <div class="data-table-wrap">
    <table class="data-table">
      <thead>
        <tr><th>Traveler</th><th>Journeys</th><th>Distance</th><th>Safety score</th></tr>
      </thead>
      <tbody>
        <tr><td><div class="table-person"><span class="person">GN</span>Grace Njeri</div></td><td>47</td><td>2,140 km</td><td>100%</td></tr>
        <tr><td><div class="table-person"><span class="person">JK</span>James Kariuki</div></td><td>34</td><td>1,880 km</td><td>97%</td></tr>
        <tr><td><div class="table-person"><span class="person">SW</span>Sarah Wambui</div></td><td>21</td><td>1,120 km</td><td>100%</td></tr>
        <tr><td><div class="table-person"><span class="person">DM</span>David Mutuku</div></td><td>12</td><td>640 km</td><td>92%</td></tr>
        <tr><td><div class="table-person"><span class="person">PK</span>Peter Kamau</div></td><td>10</td><td>510 km</td><td>100%</td></tr>
      </tbody>
    </table>
  </div>
</div>

<div class="card" style="margin-top:18px">
  <div class="card-head"><div><label>MONTHLY TREND</label><h3>Journeys over the last 6 months</h3></div></div>
  <div class="data-table-wrap">
    <table class="data-table">
      <thead>
        <tr><th>Month</th><th>Journeys</th><th>Distance</th><th>SOS alerts</th></tr>
      </thead>
      <tbody>
        <tr><td>March 2026</td><td>398</td><td>15,210 km</td><td>2</td></tr>
        <tr><td>April 2026</td><td>412</td><td>16,040 km</td><td>0</td></tr>
        <tr><td>May 2026</td><td>435</td><td>16,890 km</td><td>1</td></tr>
        <tr><td>June 2026</td><td>401</td><td>15,660 km</td><td>0</td></tr>
        <tr><td>July 2026</td><td>434</td><td>16,980 km</td><td>1</td></tr>
        <tr><td>August 2026</td><td>486</td><td>18,420 km</td><td>1</td></tr>
      </tbody>
    </table>
  </div>
</div>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>
<script src="dashboard.js"></script>
</body>
</html>
