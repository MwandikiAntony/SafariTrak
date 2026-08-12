async function postJson(url, body) {
  const response = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body),
  });
  return response.json();
}

document.getElementById('createGroupBtn')?.addEventListener('click', async () => {
  const title = document.getElementById('groupTitle').value.trim();
  const destination = document.getElementById('groupDestination').value.trim();
  const departure = document.getElementById('groupDeparture').value;
  const errorEl = document.getElementById('createGroupError');
  const btn = document.getElementById('createGroupBtn');

  errorEl.style.display = 'none';

  if (!title || !destination) {
    errorEl.textContent = 'Please enter a trip name and destination.';
    errorEl.style.display = 'block';
    return;
  }

  const inviteIds = Array.from(document.querySelectorAll('.invite-checkbox:checked')).map(el => parseInt(el.value, 10));

  btn.disabled = true;
  btn.textContent = 'Creating...';

  try {
    const data = await postJson('backend/api/group/create.php', {
      title,
      destination_label: destination,
      departure_at: departure || null,
      invite_contact_ids: inviteIds,
    });

    if (!data.success) {
      errorEl.textContent = data.message || 'That group journey could not be created.';
      errorEl.style.display = 'block';
      return;
    }

    location.reload();
  } catch (err) {
    errorEl.textContent = 'Something went wrong. Please try again.';
    errorEl.style.display = 'block';
  } finally {
    btn.disabled = false;
    btn.textContent = 'Create and invite';
  }
});

document.querySelectorAll('.respond-btn').forEach(btn => {
  btn.addEventListener('click', async () => {
    const id = btn.getAttribute('data-id');
    const action = btn.getAttribute('data-action');

    if (action === 'leave' && !confirm('Leave this group journey?')) return;

    btn.disabled = true;
    try {
      const data = await postJson('backend/api/group/respond.php', { member_id: id, action });
      if (!data.success) {
        alert(data.message || 'That could not be completed.');
        btn.disabled = false;
        return;
      }
      location.reload();
    } catch (err) {
      alert('Something went wrong. Please try again.');
      btn.disabled = false;
    }
  });
});

document.querySelectorAll('.remove-member-btn').forEach(btn => {
  btn.addEventListener('click', async () => {
    if (!confirm('Remove this person from the group?')) return;
    btn.disabled = true;

    try {
      const data = await postJson('backend/api/group/remove-member.php', { member_id: btn.getAttribute('data-member-id') });
      if (!data.success) {
        alert(data.message || 'That member could not be removed.');
        btn.disabled = false;
        return;
      }
      location.reload();
    } catch (err) {
      alert('Something went wrong. Please try again.');
      btn.disabled = false;
    }
  });
});

document.querySelectorAll('.group-action-btn').forEach(btn => {
  btn.addEventListener('click', async () => {
    const action = btn.getAttribute('data-action');
    const groupId = btn.getAttribute('data-group-id');

    if (action === 'cancel' && !confirm('Cancel this group journey? Everyone will be notified.')) return;

    btn.disabled = true;
    try {
      const data = await postJson('backend/api/group/' + action + '.php', { group_id: groupId });
      if (!data.success) {
        alert(data.message || 'That could not be completed.');
        btn.disabled = false;
        return;
      }
      location.reload();
    } catch (err) {
      alert('Something went wrong. Please try again.');
      btn.disabled = false;
    }
  });
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
if (window.ACTIVE_GROUP_ID) {
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

  const groupMap = L.map('groupMap').setView([-1.286389, 36.817223], 6);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap contributors'
  }).addTo(groupMap);

  const bounds = [];
  const memberMarkers = {};

  window.ACTIVE_GROUP_MEMBERS.forEach(m => {
    if (m.status !== 'confirmed' || m.lat === null || m.lng === null) return;
    const label = m.user_id === window.MY_USER_ID ? m.name + ' (you)' : m.name;
    const marker = L.marker([m.lat, m.lng], { icon: memberIcon }).addTo(groupMap).bindPopup('<b>' + label + '</b>');
    memberMarkers[m.user_id] = marker;
    bounds.push([m.lat, m.lng]);
  });

  if (window.ACTIVE_GROUP_DEST_LAT !== null && window.ACTIVE_GROUP_DEST_LNG !== null) {
    L.marker([window.ACTIVE_GROUP_DEST_LAT, window.ACTIVE_GROUP_DEST_LNG], { icon: destinationIcon }).addTo(groupMap).bindPopup('<b>Destination</b>');
    bounds.push([window.ACTIVE_GROUP_DEST_LAT, window.ACTIVE_GROUP_DEST_LNG]);
  }

  if (bounds.length > 0) {
    groupMap.fitBounds(bounds, { padding: [40, 40] });
  }

  const amIConfirmedMember = window.ACTIVE_GROUP_MEMBERS.some(m => m.user_id === window.MY_USER_ID && m.status === 'confirmed');

  if (amIConfirmedMember && navigator.geolocation) {
    let lastPush = 0;
    navigator.geolocation.watchPosition((position) => {
      const lat = position.coords.latitude;
      const lng = position.coords.longitude;

      if (memberMarkers[window.MY_USER_ID]) {
        memberMarkers[window.MY_USER_ID].setLatLng([lat, lng]);
      } else {
        memberMarkers[window.MY_USER_ID] = L.marker([lat, lng], { icon: memberIcon }).addTo(groupMap).bindPopup('<b>You</b>');
      }

      const now = Date.now();
      if (now - lastPush > 15000) {
        lastPush = now;
        postJson('backend/api/group/update-position.php', {
          group_id: window.ACTIVE_GROUP_ID,
          lat,
          lng,
        }).catch(() => {});
      }
    }, () => {}, { enableHighAccuracy: true, maximumAge: 10000 });
  }
}
