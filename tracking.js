const map = L.map('map').setView([-1.05, 36.95], 10);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

const currentIcon = L.divIcon({
  className: 'st-marker',
  html: '<div style="width:18px;height:18px;background:#176b5b;border:4px solid white;border-radius:50%;box-shadow:0 2px 10px rgba(0,0,0,.25)"></div>',
  iconSize: [18, 18],
  iconAnchor: [9, 9]
});

const destinationIcon = L.divIcon({
  className: 'st-marker',
  html: '<div style="width:18px;height:18px;background:#d69b2d;border:4px solid white;border-radius:50%;box-shadow:0 2px 10px rgba(0,0,0,.25)"></div>',
  iconSize: [18, 18],
  iconAnchor: [9, 9]
});

const start = [-1.286389, 36.817223];
const destination = [-0.4197, 36.9483];

const marker = L.marker(start, { icon: currentIcon }).addTo(map).bindPopup('<b>You are here</b><br>Along Nairobi to Nyeri road');
L.marker(destination, { icon: destinationIcon }).addTo(map).bindPopup('<b>Destination</b><br>Nyeri Town');
L.polyline([start, destination], { color: '#10b981', weight: 3, dashArray: '6 8' }).addTo(map);
map.fitBounds([start, destination], { padding: [40, 40] });

function locate() {
  if (!navigator.geolocation) return alert('Location is not supported by this browser.');
  navigator.geolocation.getCurrentPosition(p => {
    const x = [p.coords.latitude, p.coords.longitude];
    marker.setLatLng(x);
    map.setView(x, 13);
    marker.openPopup();
  }, () => alert('Please allow SafariTrak to access your location.'), { enableHighAccuracy: true, timeout: 10000 });
}

document.getElementById('myLocation')?.addEventListener('click', locate);
