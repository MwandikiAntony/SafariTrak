<?php
require __DIR__ . '/backend/includes/auth-guard.php';
$orgName = 'Meru Transport Sacco';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Group Journeys</title>
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
    <a class="active" href="admin-groups.php"><i class="fa-solid fa-user-group"></i>Group Journeys</a>
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
  <div><label>TRAVELLING TOGETHER</label><h1>Group Journeys</h1></div>
  <div class="head-actions"><button><i class="fa-regular fa-bell"></i></button><div class="avatar">O</div></div>
</header>

<div class="content">

<div class="page-head">
  <div><h2>Group trips across your organization</h2><p>See every group journey, who organized it, and who is authorized to be tracked.</p></div>
</div>

<div class="card">
  <div class="data-table-wrap">
    <table class="data-table">
      <thead>
        <tr><th>Trip</th><th>Organizer</th><th>Members</th><th>Date</th><th>Status</th><th>Action</th></tr>
      </thead>
      <tbody>
        <tr>
          <td><b>Staff outing: Meru &rarr; Ol Pejeta</b></td>
          <td><div class="table-person"><span class="person">GN</span>Grace Njeri</div></td>
          <td>9</td>
          <td>10 Aug 2026</td>
          <td><span class="badge active">Upcoming</span></td>
          <td><a class="table-action" href="#" data-open-modal="orgGroupModal1">View</a></td>
        </tr>
        <tr>
          <td><b>Sacco AGM: Nairobi &rarr; Meru</b></td>
          <td><div class="table-person"><span class="person">JK</span>James Kariuki</div></td>
          <td>23</td>
          <td>15 Aug 2026</td>
          <td><span class="badge active">Upcoming</span></td>
          <td><a class="table-action" href="#" data-open-modal="orgGroupModal2">View</a></td>
        </tr>
        <tr>
          <td><b>Youth conference convoy</b></td>
          <td><div class="table-person"><span class="person">SW</span>Sarah Wambui</div></td>
          <td>14</td>
          <td>2 Aug 2026</td>
          <td><span class="badge completed">Completed</span></td>
          <td><a class="table-action" href="#" data-open-modal="orgGroupModal3">View</a></td>
        </tr>
        <tr>
          <td><b>Field visit: Meru &rarr; Isiolo</b></td>
          <td><div class="table-person"><span class="person">DM</span>David Mutuku</div></td>
          <td>5</td>
          <td>28 Jul 2026</td>
          <td><span class="badge cancelled">Cancelled</span></td>
          <td><a class="table-action" href="#" data-open-modal="orgGroupModal4">View</a></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<div class="modal-overlay" id="orgGroupModal1">
  <div class="modal">
    <div class="modal-head"><div><h3>Staff outing: Meru &rarr; Ol Pejeta</h3><p>9 members &middot; 10 Aug 2026</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>Organized by Grace Njeri. All 9 members have confirmed and will be trackable once the trip starts.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<div class="modal-overlay" id="orgGroupModal2">
  <div class="modal">
    <div class="modal-head"><div><h3>Sacco AGM: Nairobi &rarr; Meru</h3><p>23 members &middot; 15 Aug 2026</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>Organized by James Kariuki. 18 of 23 members have confirmed attendance so far.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<div class="modal-overlay" id="orgGroupModal3">
  <div class="modal">
    <div class="modal-head"><div><h3>Youth conference convoy</h3><p>14 members &middot; completed 2 Aug 2026</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>All 14 members arrived safely with no route deviation alerts recorded.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<div class="modal-overlay" id="orgGroupModal4">
  <div class="modal">
    <div class="modal-head"><div><h3>Field visit: Meru &rarr; Isiolo</h3><p>5 members &middot; cancelled 28 Jul 2026</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>Cancelled by the organizer before departure. No distance was recorded.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<script src="dashboard.js"></script>
</body>
</html>
