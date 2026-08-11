document.getElementById('useMyLocationBtn')?.addEventListener('click', () => {
  const btn = document.getElementById('useMyLocationBtn');
  const startPoint = document.getElementById('startPoint');

  if (!navigator.geolocation) {
    alert('Location is not supported by this browser.');
    return;
  }

  btn.disabled = true;
  btn.textContent = 'Getting your location...';

  navigator.geolocation.getCurrentPosition(
    async (position) => {
      const lat = position.coords.latitude;
      const lng = position.coords.longitude;

      document.getElementById('startLat').value = lat;
      document.getElementById('startLng').value = lng;

      // Optional: Auto-fill location label if empty using OpenStreetMap Nominatim
      if (startPoint && !startPoint.value.trim()) {
        try {
          const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
          const data = await res.json();
          if (data && data.display_name) {
            startPoint.value = data.display_name.split(',')[0];
          }
        } catch (e) {
          /* Fallback gracefully if lookup fails */
        }
      }

      btn.disabled = false;
      btn.innerHTML = '<i class="fa-solid fa-check"></i> Location added';
    },
    () => {
      alert('Please allow SafariTrak to access your location.');
      btn.disabled = false;
      btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> Use my current location';
    },
    { enableHighAccuracy: true, timeout: 10000 }
  );
});

document.getElementById('submitJourney')?.addEventListener('click', async () => {
  const startPoint = document.getElementById('startPoint');
  const endPoint = document.getElementById('endPoint');
  const btn = document.getElementById('submitJourney');

  if (!startPoint.value.trim() || !endPoint.value.trim()) {
    alert('Please fill in both your starting point and destination.');
    return;
  }

  const startLatVal = document.getElementById('startLat').value;
  const startLngVal = document.getElementById('startLng').value;

  const shareWith = Array.from(document.querySelectorAll('.share-checkbox:checked'))
    .map(el => parseInt(el.value, 10));

  const payload = {
    start_label: startPoint.value.trim(),
    start_lat: startLatVal ? parseFloat(startLatVal) : null,
    start_lng: startLngVal ? parseFloat(startLngVal) : null,
    end_label: endPoint.value.trim(),
    transport_mode: document.getElementById('transportMode').value,
    planned_departure_at: document.getElementById('departureTime').value || null,
    note: document.getElementById('journeyNote').value.trim(),
    route_deviation_alert: document.getElementById('deviationAlert').checked,
    share_with: shareWith,
  };

  btn.disabled = true;
  btn.textContent = 'Starting...';

  try {
    const response = await fetch('backend/api/journeys/start.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });
    const data = await response.json();

    if (!data.success) {
      alert(data.message || 'That journey could not be started.');
      btn.disabled = false;
      btn.innerHTML = '<i class="fa-solid fa-route"></i> Start journey';
      return;
    }

    window.location.href = data.redirect || 'live-tracking.php';
  } catch (err) {
    alert('Something went wrong starting that journey. Please try again.');
    btn.disabled = false;
    btn.innerHTML = '<i class="fa-solid fa-route"></i> Start journey';
  }
});