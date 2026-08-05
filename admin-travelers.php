<?php
session_start();
$orgName = $_SESSION['org_name'] ?? 'Meru Transport Sacco';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Travelers</title>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div class="app">
<aside class="sidebar" id="sidebar">
  <div class="brand"><div class="logo"><i class="fa-solid fa-route"></i></div><div><b>SafariTrak</b><small>Travel smarter</small><span class="org-badge">ORGANIZATION</span></div></div>
  <nav>
    <a href="admin-dashboard.php"><i class="fa-solid fa-grid-2"></i>Overview</a>
    <a class="active" href="admin-travelers.php"><i class="fa-solid fa-users"></i>Travelers</a>
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
  <div><label>PEOPLE</label><h1>Travelers</h1></div>
  <div class="head-actions"><button><i class="fa-regular fa-bell"></i></button><div class="avatar">O</div></div>
</header>

<div class="content">

<div class="page-head">
  <div><h2>Everyone in your organization</h2><p>See who is travelling, manage access, and keep an eye on activity.</p></div>
  <button type="button" class="btn-primary" data-open-modal="addTravelerModal"><i class="fa-solid fa-user-plus"></i>Add traveler</button>
</div>

<div class="card">
  <div class="filter-bar">
    <input type="text" id="travelerSearch" placeholder="Search travelers by name or phone...">
    <select id="travelerStatusFilter">
      <option value="all">All statuses</option>
      <option value="active">Active now</option>
      <option value="offline">Offline</option>
    </select>
  </div>
  <div class="data-table-wrap">
    <table class="data-table" id="travelersTable">
      <thead>
        <tr><th>Traveler</th><th>Phone</th><th>Status</th><th>Journeys</th><th>Last active</th><th>Action</th></tr>
      </thead>
      <tbody>
        <tr data-status="active">
          <td><div class="table-person"><span class="person">JK</span>James Kariuki</div></td>
          <td>0712 445 210</td>
          <td><span class="badge active">Active now</span></td>
          <td>34</td>
          <td>10 minutes ago</td>
          <td><a class="table-action" href="#" data-open-modal="travelerModal1">View</a><a class="table-action danger" href="#" data-open-modal="deactivateModal1">Deactivate</a></td>
        </tr>
        <tr data-status="offline">
          <td><div class="table-person"><span class="person">SW</span>Sarah Wambui</div></td>
          <td>0722 810 044</td>
          <td><span class="badge completed">Offline</span></td>
          <td>21</td>
          <td>3 hours ago</td>
          <td><a class="table-action" href="#" data-open-modal="travelerModal2">View</a><a class="table-action danger" href="#" data-open-modal="deactivateModal2">Deactivate</a></td>
        </tr>
        <tr data-status="offline">
          <td><div class="table-person"><span class="person">DM</span>David Mutuku</div></td>
          <td>0733 902 187</td>
          <td><span class="badge completed">Offline</span></td>
          <td>12</td>
          <td>Yesterday</td>
          <td><a class="table-action" href="#" data-open-modal="travelerModal3">View</a><a class="table-action danger" href="#" data-open-modal="deactivateModal3">Deactivate</a></td>
        </tr>
        <tr data-status="active">
          <td><div class="table-person"><span class="person">GN</span>Grace Njeri</div></td>
          <td>0700 556 321</td>
          <td><span class="badge active">Active now</span></td>
          <td>47</td>
          <td>Just now</td>
          <td><a class="table-action" href="#" data-open-modal="travelerModal4">View</a><a class="table-action danger" href="#" data-open-modal="deactivateModal4">Deactivate</a></td>
        </tr>
      </tbody>
    </table>
  </div>
  <p class="hint" id="travelersEmptyState" style="display:none;padding:0 21px 21px;color:var(--muted);font-size:11px">No travelers match your search.</p>
</div>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<div class="modal-overlay" id="addTravelerModal">
  <div class="modal">
    <div class="modal-head"><div><h3>Add a traveler</h3><p>Invite someone to join your organization on SafariTrak.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body">
      <div class="form-field" style="margin-bottom:12px"><label>Full name</label><input type="text" placeholder="e.g. Josephine Achieng"></div>
      <div class="form-field"><label>Phone number</label><input type="tel" placeholder="e.g. 0711 223 344"></div>
    </div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="primary" onclick="alert('Once the backend is connected, this will send an invite to join your organization.')">Send invite</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="travelerModal1">
  <div class="modal">
    <div class="modal-head"><div><h3>James Kariuki</h3><p>Active now &middot; 0712 445 210</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>34 journeys logged. Currently travelling from Meru to Nairobi, started 10 minutes ago.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<div class="modal-overlay" id="travelerModal2">
  <div class="modal">
    <div class="modal-head"><div><h3>Sarah Wambui</h3><p>Offline &middot; 0722 810 044</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>21 journeys logged. Last journey completed 3 hours ago, Meru to Chuka.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<div class="modal-overlay" id="travelerModal3">
  <div class="modal">
    <div class="modal-head"><div><h3>David Mutuku</h3><p>Offline &middot; 0733 902 187</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>12 journeys logged. Had an SOS alert yesterday which was resolved by an admin.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<div class="modal-overlay" id="travelerModal4">
  <div class="modal">
    <div class="modal-head"><div><h3>Grace Njeri</h3><p>Active now &middot; 0700 556 321</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>47 journeys logged. Organizer of the upcoming staff outing group journey.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button></div>
  </div>
</div>

<div class="modal-overlay" id="deactivateModal1">
  <div class="modal">
    <div class="modal-head"><div><h3>Deactivate James Kariuki?</h3><p>They will lose access to the organization's SafariTrak account.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Cancel</button><button type="button" class="danger" onclick="alert('Once the backend is connected, this will deactivate their account.')">Deactivate</button></div>
  </div>
</div>

<div class="modal-overlay" id="deactivateModal2">
  <div class="modal">
    <div class="modal-head"><div><h3>Deactivate Sarah Wambui?</h3><p>They will lose access to the organization's SafariTrak account.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Cancel</button><button type="button" class="danger" onclick="alert('Once the backend is connected, this will deactivate their account.')">Deactivate</button></div>
  </div>
</div>

<div class="modal-overlay" id="deactivateModal3">
  <div class="modal">
    <div class="modal-head"><div><h3>Deactivate David Mutuku?</h3><p>They will lose access to the organization's SafariTrak account.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Cancel</button><button type="button" class="danger" onclick="alert('Once the backend is connected, this will deactivate their account.')">Deactivate</button></div>
  </div>
</div>

<div class="modal-overlay" id="deactivateModal4">
  <div class="modal">
    <div class="modal-head"><div><h3>Deactivate Grace Njeri?</h3><p>They will lose access to the organization's SafariTrak account.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Cancel</button><button type="button" class="danger" onclick="alert('Once the backend is connected, this will deactivate their account.')">Deactivate</button></div>
  </div>
</div>

<script src="dashboard.js"></script>
<script src="admin-travelers.js"></script>
</body>
</html>
