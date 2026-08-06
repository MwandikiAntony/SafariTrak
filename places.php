<?php
require __DIR__ . '/backend/includes/auth-guard.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Places</title>
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
    <a href="my-journeys.php"><i class="fa-solid fa-map-location-dot"></i>My Journeys</a>
    <a href="live-tracking.php"><i class="fa-solid fa-location-crosshairs"></i>Live Tracking</a>
    <a class="active" href="places.php"><i class="fa-solid fa-map-pin"></i>Places</a>
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
  <div><label>USEFUL STOPS</label><h1>Places nearby</h1></div>
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
  <div><h2>Find what you need along the way</h2><p>Search for hospitals, police stations, fuel, hotels and restaurants near your route.</p></div>
</div>

<div class="search" style="margin-bottom:16px">
  <i class="fa-solid fa-magnifying-glass"></i>
  <input id="placeSearch" placeholder="Search places, e.g. Total fuel station Thika Road">
  <button id="placeSearchBtn">Search</button>
</div>

<div class="tabs" id="placeTabs">
  <button type="button" class="tab active" data-category="all"><i class="fa-solid fa-layer-group"></i> All</button>
  <button type="button" class="tab" data-category="hospital"><i class="fa-solid fa-kit-medical"></i> Hospitals</button>
  <button type="button" class="tab" data-category="police"><i class="fa-solid fa-building-shield"></i> Police</button>
  <button type="button" class="tab" data-category="fuel"><i class="fa-solid fa-gas-pump"></i> Fuel</button>
  <button type="button" class="tab" data-category="hotel"><i class="fa-solid fa-bed"></i> Hotels</button>
  <button type="button" class="tab" data-category="restaurant"><i class="fa-solid fa-utensils"></i> Restaurants</button>
</div>

<div class="grid">
  <div class="card map-card">
    <div class="card-head"><div><label>MAP</label><h3>Places near your route</h3></div></div>
    <div id="placesMap"></div>
  </div>

  <div class="card">
    <div class="card-head"><div><label>RESULTS</label><h3 id="resultsCount">18 places found</h3></div></div>
    <div class="journey-list" id="placesList">

      <div class="journey-row" data-category="hospital" data-open-modal="placeModalHospital1">
        <div class="jicon"><i class="fa-solid fa-kit-medical"></i></div>
        <div class="jinfo"><b>Kenyatta National Hospital</b><small>Hospital &middot; 2.4 km away</small></div>
        <div class="jmeta"><strong>Open 24 hrs</strong></div>
      </div>

      <div class="journey-row" data-category="police" data-open-modal="placeModalPolice1">
        <div class="jicon"><i class="fa-solid fa-building-shield"></i></div>
        <div class="jinfo"><b>Central Police Station</b><small>Police &middot; 1.1 km away</small></div>
        <div class="jmeta"><strong>Open 24 hrs</strong></div>
      </div>

      <div class="journey-row" data-category="fuel" data-open-modal="placeModalFuel1">
        <div class="jicon"><i class="fa-solid fa-gas-pump"></i></div>
        <div class="jinfo"><b>Total Energies, Thika Road</b><small>Fuel station &middot; 3.6 km away</small></div>
        <div class="jmeta"><strong>Open 24 hrs</strong></div>
      </div>

      <div class="journey-row" data-category="hotel" data-open-modal="placeModalHotel1">
        <div class="jicon"><i class="fa-solid fa-bed"></i></div>
        <div class="jinfo"><b>Nyeri Green Hills Hotel</b><small>Hotel &middot; 148 km away</small></div>
        <div class="jmeta"><strong>From KSh 4,500</strong></div>
      </div>

      <div class="journey-row" data-category="restaurant" data-open-modal="placeModalRestaurant1">
        <div class="jicon"><i class="fa-solid fa-utensils"></i></div>
        <div class="jinfo"><b>Java House, Karen</b><small>Restaurant &middot; 5.2 km away</small></div>
        <div class="jmeta"><strong>Open till 10 PM</strong></div>
      </div>

      <div class="journey-row" data-category="fuel" data-open-modal="placeModalFuel2">
        <div class="jicon"><i class="fa-solid fa-gas-pump"></i></div>
        <div class="jinfo"><b>Shell, Muranga Road</b><small>Fuel station &middot; 62 km away</small></div>
        <div class="jmeta"><strong>Open 24 hrs</strong></div>
      </div>

      <div class="journey-row" data-category="hospital" data-open-modal="placeModalHospital2">
        <div class="jicon"><i class="fa-solid fa-kit-medical"></i></div>
        <div class="jinfo"><b>Nyeri County Referral Hospital</b><small>Hospital &middot; 149 km away</small></div>
        <div class="jmeta"><strong>Open 24 hrs</strong></div>
      </div>

      <div class="journey-row" data-category="restaurant" data-open-modal="placeModalRestaurant2">
        <div class="jicon"><i class="fa-solid fa-utensils"></i></div>
        <div class="jinfo"><b>Trout Tree Restaurant</b><small>Restaurant &middot; 141 km away</small></div>
        <div class="jmeta"><strong>Open till 9 PM</strong></div>
      </div>

    </div>
    <p class="hint" id="placesEmptyState" style="display:none;padding:0 21px 21px;color:var(--muted);font-size:11px">No places found in this category yet.</p>
  </div>
</div>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<div class="modal-overlay" id="placeModalHospital1">
  <div class="modal">
    <div class="modal-head"><div><h3>Kenyatta National Hospital</h3><p>Hospital &middot; 2.4 km away</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>Kenya's largest public referral hospital, with a 24 hour emergency department.</p><p style="margin-top:8px"><b>Address:</b> Hospital Road, Upper Hill, Nairobi</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button><button type="button" class="primary" onclick="alert('Once the backend is connected, this will show directions to Kenyatta National Hospital.')">Get directions</button></div>
  </div>
</div>

<div class="modal-overlay" id="placeModalPolice1">
  <div class="modal">
    <div class="modal-head"><div><h3>Central Police Station</h3><p>Police &middot; 1.1 km away</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>Main police station serving the central business district, open around the clock.</p><p style="margin-top:8px"><b>Address:</b> University Way, Nairobi</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button><button type="button" class="primary" onclick="alert('Once the backend is connected, this will show directions to Central Police Station.')">Get directions</button></div>
  </div>
</div>

<div class="modal-overlay" id="placeModalFuel1">
  <div class="modal">
    <div class="modal-head"><div><h3>Total Energies, Thika Road</h3><p>Fuel station &middot; 3.6 km away</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>Fuel station with a small shop and clean restrooms, right off Thika Road.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button><button type="button" class="primary" onclick="alert('Once the backend is connected, this will show directions to this fuel station.')">Get directions</button></div>
  </div>
</div>

<div class="modal-overlay" id="placeModalFuel2">
  <div class="modal">
    <div class="modal-head"><div><h3>Shell, Muranga Road</h3><p>Fuel station &middot; 62 km away</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>Roadside fuel stop along Muranga Road, a good halfway point on the way to Nyeri.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button><button type="button" class="primary" onclick="alert('Once the backend is connected, this will show directions to this fuel station.')">Get directions</button></div>
  </div>
</div>

<div class="modal-overlay" id="placeModalHotel1">
  <div class="modal">
    <div class="modal-head"><div><h3>Nyeri Green Hills Hotel</h3><p>Hotel &middot; 148 km away</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>Comfortable stop near Nyeri town with parking and a restaurant on site.</p><p style="margin-top:8px"><b>From:</b> KSh 4,500 per night</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button><button type="button" class="primary" onclick="alert('Once the backend is connected, this will show directions to this hotel.')">Get directions</button></div>
  </div>
</div>

<div class="modal-overlay" id="placeModalRestaurant1">
  <div class="modal">
    <div class="modal-head"><div><h3>Java House, Karen</h3><p>Restaurant &middot; 5.2 km away</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>Familiar coffee house and restaurant menu, open until 10 PM.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button><button type="button" class="primary" onclick="alert('Once the backend is connected, this will show directions to Java House.')">Get directions</button></div>
  </div>
</div>

<div class="modal-overlay" id="placeModalRestaurant2">
  <div class="modal">
    <div class="modal-head"><div><h3>Trout Tree Restaurant</h3><p>Restaurant &middot; 141 km away</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>Open air restaurant built around a fig tree, known for fresh trout dishes.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button><button type="button" class="primary" onclick="alert('Once the backend is connected, this will show directions to Trout Tree Restaurant.')">Get directions</button></div>
  </div>
</div>

<div class="modal-overlay" id="placeModalHospital2">
  <div class="modal">
    <div class="modal-head"><div><h3>Nyeri County Referral Hospital</h3><p>Hospital &middot; 149 km away</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>Main public hospital serving Nyeri County, with a 24 hour emergency department.</p></div>
    <div class="modal-actions"><button type="button" class="ghost" data-close-modal>Close</button><button type="button" class="primary" onclick="alert('Once the backend is connected, this will show directions to this hospital.')">Get directions</button></div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="dashboard.js"></script>
<script src="notifications-widget.js"></script>
<script src="places.js"></script>
</body>
</html>