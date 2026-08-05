const placesData = [
  { id: 'placeModalHospital1', name: 'Kenyatta National Hospital', category: 'hospital', coords: [-1.3006, 36.8073] },
  { id: 'placeModalPolice1', name: 'Central Police Station', category: 'police', coords: [-1.2841, 36.8232] },
  { id: 'placeModalFuel1', name: 'Total Energies, Thika Road', category: 'fuel', coords: [-1.2205, 36.8895] },
  { id: 'placeModalHotel1', name: 'Nyeri Green Hills Hotel', category: 'hotel', coords: [-0.4197, 36.9483] },
  { id: 'placeModalRestaurant1', name: 'Java House, Karen', category: 'restaurant', coords: [-1.3186, 36.7075] },
  { id: 'placeModalFuel2', name: 'Shell, Muranga Road', category: 'fuel', coords: [-0.7167, 37.15] },
  { id: 'placeModalHospital2', name: 'Nyeri County Referral Hospital', category: 'hospital', coords: [-0.4223, 36.9515] },
  { id: 'placeModalRestaurant2', name: 'Trout Tree Restaurant', category: 'restaurant', coords: [-0.3833, 36.85] }
];

const categoryColors = {
  hospital: '#c94b4b',
  police: '#2f6fed',
  fuel: '#d69b2d',
  hotel: '#8b5cf6',
  restaurant: '#10b981'
};

const categoryIcons = {
  hospital: 'fa-kit-medical',
  police: 'fa-building-shield',
  fuel: 'fa-gas-pump',
  hotel: 'fa-bed',
  restaurant: 'fa-utensils'
};

const placesMap = L.map('placesMap').setView([-0.85, 36.9], 8);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '© OpenStreetMap contributors'
}).addTo(placesMap);

const placeMarkers = {};

placesData.forEach(place => {
  const color = categoryColors[place.category] || '#10b981';
  const icon = L.divIcon({
    className: 'st-marker',
    html: '<div style="width:16px;height:16px;background:' + color + ';border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,.25)"></div>',
    iconSize: [16, 16],
    iconAnchor: [8, 8]
  });
  const marker = L.marker(place.coords, { icon }).addTo(placesMap).bindPopup('<b>' + place.name + '</b>');
  placeMarkers[place.id] = { marker, category: place.category };
});

const placeTabs = document.querySelectorAll('#placeTabs .tab');
const placeRows = document.querySelectorAll('#placesList .journey-row');
const resultsCount = document.getElementById('resultsCount');
const emptyState = document.getElementById('placesEmptyState');

function filterPlaces(category, searchText) {
  let visible = 0;

  placeRows.forEach(row => {
    const rowCategory = row.getAttribute('data-category');
    const matchesCategory = category === 'all' || rowCategory === category;
    const matchesSearch = !searchText || row.textContent.toLowerCase().includes(searchText);
    const show = matchesCategory && matchesSearch;

    row.style.display = show ? 'flex' : 'none';
    if (show) visible++;

    const modalId = row.getAttribute('data-open-modal');
    const entry = placeMarkers[modalId];
    if (entry) {
      if (show) {
        entry.marker.addTo(placesMap);
      } else {
        placesMap.removeLayer(entry.marker);
      }
    }
  });

  if (resultsCount) resultsCount.textContent = visible + (visible === 1 ? ' place found' : ' places found');
  if (emptyState) emptyState.style.display = visible === 0 ? 'block' : 'none';
}

let activeCategory = 'all';

placeTabs.forEach(tab => {
  tab.addEventListener('click', () => {
    placeTabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    activeCategory = tab.getAttribute('data-category');
    filterPlaces(activeCategory, document.getElementById('placeSearch').value.trim().toLowerCase());
  });
});

document.getElementById('placeSearchBtn')?.addEventListener('click', () => {
  filterPlaces(activeCategory, document.getElementById('placeSearch').value.trim().toLowerCase());
});

document.getElementById('placeSearch')?.addEventListener('keydown', e => {
  if (e.key === 'Enter') {
    filterPlaces(activeCategory, e.target.value.trim().toLowerCase());
  }
});
