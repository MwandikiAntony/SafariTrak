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

function showToast(message, kind = 'info') {
  const toast = document.createElement('div');
  toast.textContent = message;
  toast.style.cssText = 'position:fixed;right:20px;bottom:20px;padding:12px 18px;color:#fff;border-radius:10px;box-shadow:0 12px 30px rgba(0,0,0,.18);font-weight:600;z-index:9999;opacity:0;transform:translateY(12px);transition:opacity .18s ease,transform .18s ease;background:' + (kind === 'error' ? '#c94b4b' : '#39a859') + ';';
  document.body.appendChild(toast);
  requestAnimationFrame(() => {
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';
  });
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(12px)';
    setTimeout(() => toast.remove(), 200);
  }, 3000);
}

function getTextContent(el) {
  return el ? el.textContent : '';
}

async function createGroupJourney() {
  const titleEl = document.getElementById('groupNameInput');
  const destinationEl = document.getElementById('groupDestinationInput');
  const departureEl = document.getElementById('groupDepartureInput');
  const title = titleEl ? titleEl.value.trim() : '';
  const destination = destinationEl ? destinationEl.value.trim() : '';
  const departure = departureEl ? departureEl.value.trim() : '';
  const contactRows = document.querySelectorAll('.share-contact-row');
  const invites = [];
  contactRows.forEach(row => {
    const checkbox = row.querySelector('input[type="checkbox"]');
    if (checkbox && checkbox.checked) {
      invites.push({
        name: row.dataset.contactName || getTextContent(row.querySelector('span:nth-child(2)')),
        phone: row.dataset.contactPhone || ''
      });
    }
  });

  const createGroupButton = document.getElementById('createGroupButton');
  if (createGroupButton) {
    createGroupButton.disabled = true;
    createGroupButton.textContent = 'Creating...';
  }

  try {
    const res = await fetch('backend/api/create-group-journey.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ title, destination, departure_at: departure, invites })
    });
    const data = await res.json().catch(() => null);
    if (!res.ok || !data || !data.success) {
      throw new Error(data && data.message ? data.message : 'Failed to create group journey');
    }
    document.getElementById('createGroupModal')?.classList.remove('open');
    showToast('Group journey created successfully and saved to database');
    setTimeout(() => window.location.reload(), 1200);
  } catch (err) {
    showToast(err.message || 'Failed to create group journey', 'error');
  } finally {
    if (createGroupButton) {
      createGroupButton.disabled = false;
      createGroupButton.textContent = 'Create and invite';
    }
  }
}

window.createGroupJourney = createGroupJourney;

window.addEventListener('DOMContentLoaded', () => {
  console.log('group-travel.js loaded');
  const createGroupButton = document.getElementById('createGroupButton');
  if (!createGroupButton) {
    console.warn('Create group button not found');
    return;
  }

  createGroupButton.addEventListener('click', createGroupJourney);
});
