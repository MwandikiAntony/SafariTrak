const groupMap = L.map('groupMap').setView([-2.5, 37.3], 7);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '© OpenStreetMap contributors'
}).addTo(groupMap);

const memberIcon = L.divIcon({
  className: 'st-marker',
  html: '<div style="width:16px;height:16px;background:#176b5b;border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,.25)"></div>',
  iconSize: [16, 16],
  iconAnchor: [8, 8]
});

const destinationIcon = L.divIcon({
  className: 'st-marker',
  html: '<div style="width:16px;height:16px;background:#d69b2d;border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,.25)"></div>',
  iconSize: [16, 16],
  iconAnchor: [8, 8]
});

const members = [
  { name: 'John Mwangi', coords: [-1.286389, 36.817223] },
  { name: 'Mary Wanjiku', coords: [-1.3, 36.82] },
  { name: 'You (organizer)', coords: [-1.29, 36.81] }
];

members.forEach(m => {
  L.marker(m.coords, { icon: memberIcon }).addTo(groupMap).bindPopup('<b>' + m.name + '</b>');
});

L.marker([-4.2833, 39.5833], { icon: destinationIcon }).addTo(groupMap).bindPopup('<b>Destination</b><br>Diani Beach');
