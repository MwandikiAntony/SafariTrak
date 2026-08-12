<?php
require __DIR__ . '/backend/includes/auth-guard.php';

$contactsStmt = safaritrak_db()->prepare(
    'SELECT tc.id, tc.invite_name, tc.invite_phone, tc.relationship, tc.status, u.full_name AS user_full_name, u.phone AS user_phone FROM trusted_contacts tc LEFT JOIN users u ON tc.contact_user_id = u.id WHERE tc.owner_id = ? ORDER BY tc.created_at DESC'
);
$contactsStmt->execute([$safaritrakUserId]);
$trustedContacts = $contactsStmt->fetchAll();
$db = safaritrak_db();

$contactsStmt = $db->prepare(
    'SELECT tc.id, tc.invite_name, tc.invite_phone, tc.relationship, tc.status,
            tc.share_live_location, tc.journey_alerts, tc.sos_alerts, tc.contact_user_id,
            u.full_name AS linked_name
     FROM trusted_contacts tc
     LEFT JOIN users u ON u.id = tc.contact_user_id
     WHERE tc.owner_id = ? AND tc.status != "declined"
     ORDER BY FIELD(tc.status, "confirmed", "pending"), tc.created_at DESC'
);
$contactsStmt->execute([$currentUser['id']]);
$contacts = $contactsStmt->fetchAll();

$incomingStmt = $db->prepare(
    'SELECT tc.id, tc.relationship, u.full_name AS owner_name
     FROM trusted_contacts tc
     JOIN users u ON u.id = tc.owner_id
     WHERE tc.contact_user_id = ? AND tc.status = "pending"
     ORDER BY tc.created_at DESC'
);
$incomingStmt->execute([$currentUser['id']]);
$incoming = $incomingStmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Trusted Contacts</title>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div class="app">
<aside class="sidebar" id="sidebar">
  <div class="brand"><div class="logo"><i class="fa-solid fa-route"></i></div><div><b>SafariTrak</b><small>Travel smarter</small></div></div>
  <nav>
    <a href="index.php"><i class="fa-solid fa-grid-2"></i>Dashboard</a>
    <a href="my-journeys.php"><i class="fa-solid fa-map-location-dot"></i>My Journeys</a>
    <a href="live-tracking.php"><i class="fa-solid fa-location-crosshairs"></i>Live Tracking</a>
    <a href="places.php"><i class="fa-solid fa-map-pin"></i>Places</a>
    <a href="messages.php"><i class="fa-regular fa-message"></i>Messages<?= $unreadConversationCount > 0 ? " <em>" . $unreadConversationCount . "</em>" : "" ?></a>
    <a class="active" href="trusted-contacts.php"><i class="fa-solid fa-user-group"></i>Trusted Contacts</a>
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
  <div><label>PEOPLE YOU TRUST</label><h1>Trusted Contacts</h1></div>
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
  <div><h2>Who can keep an eye on you</h2><p>Manage who can see your journeys and get notified if something goes wrong.</p></div>
  <button type="button" class="btn-primary" data-open-modal="addContactModal"><i class="fa-solid fa-user-plus"></i>Add trusted contact</button>
</div>

<div class="contacts-grid">
  <?php if (empty($trustedContacts)): ?>
    <div class="empty" style="padding: 40px 24px; text-align:center;">
      <i class="fa-solid fa-user-group" style="font-size: 28px; color: #6c6c7a;"></i>
      <p style="margin-top: 14px; color: var(--muted);">You don't have any trusted contacts yet.</p>
    </div>
  <?php else: ?>
    <?php foreach ($trustedContacts as $contact): ?>
      <?php
        $initials = implode('', array_map(fn($part) => strtoupper($part[0] ?? ''), explode(' ', trim($contact['invite_name'] ?? $contact['user_full_name'] ?? ''))));
        if ($initials === '') {
          $initials = strtoupper(substr($contact['invite_phone'] ?? '??', 0, 2));
        }
        $statusLabel = $contact['status'] === 'confirmed' ? 'Available' : ($contact['status'] === 'declined' ? 'Offline' : 'Invited');
      ?>
      <div class="contact-card">
        <div class="top"><span class="person"><?= htmlspecialchars($initials) ?></span><div><b><?= htmlspecialchars($contact['invite_name'] ?: $contact['user_full_name']) ?></b><small><?= htmlspecialchars($contact['relationship'] ?? 'Trusted contact') ?> &middot; &#9679; <?= htmlspecialchars($statusLabel) ?></small></div></div>
        <div class="permission-row"><span>See my live location</span><label class="toggle"><input type="checkbox" <?= $contact['status'] === 'confirmed' ? 'checked' : '' ?>><span></span></label></div>
        <div class="permission-row"><span>Journey start and end alerts</span><label class="toggle"><input type="checkbox" <?= $contact['status'] === 'confirmed' ? 'checked' : '' ?>><span></span></label></div>
        <div class="permission-row"><span>SOS alerts</span><label class="toggle"><input type="checkbox" <?= $contact['status'] === 'confirmed' ? 'checked' : '' ?>><span></span></label></div>
        <div class="card-buttons"><a href="messages.php" class="btn-ghost">Message</a><button type="button" class="btn-ghost remove-btn" data-contact-id="<?= htmlspecialchars($contact['id']) ?>">Remove</button></div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
<?php if (!empty($incoming)): ?>
<div class="card" style="margin-bottom:18px">
  <div class="card-head"><div><label>REQUESTS</label><h3>People who want to trust you</h3></div></div>
  <div class="journey-list">
    <?php foreach ($incoming as $req): ?>
    <div class="journey-row" style="cursor:default">
      <div class="jicon"><i class="fa-solid fa-user-plus"></i></div>
      <div class="jinfo"><b><?= htmlspecialchars($req['owner_name']) ?></b><small><?= htmlspecialchars($req['relationship'] ?: 'Wants to add you as a trusted contact') ?></small></div>
      <div class="jmeta" style="display:flex;gap:8px">
        <button type="button" class="btn-ghost respond-btn" data-id="<?= (int) $req['id'] ?>" data-action="decline">Decline</button>
        <button type="button" class="btn-primary respond-btn" data-id="<?= (int) $req['id'] ?>" data-action="confirm">Accept</button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<div class="contacts-grid">

  <?php foreach ($contacts as $contact): ?>
  <?php
    $displayName = $contact['linked_name'] ?: $contact['invite_name'];
    $isLinked = $contact['contact_user_id'] !== null;
    $isConfirmed = $contact['status'] === 'confirmed';
    $statusLabel = $isConfirmed ? 'Confirmed' : ($isLinked ? 'Pending confirmation' : 'Invited, not on SafariTrak yet');
  ?>
  <div class="contact-card" data-contact-id="<?= (int) $contact['id'] ?>" data-contact-name="<?= htmlspecialchars($displayName) ?>">
    <div class="top"><span class="person"><?= htmlspecialchars(st_initials($displayName)) ?></span><div><b><?= htmlspecialchars($displayName) ?></b><small><?= htmlspecialchars($contact['relationship'] ?: 'Trusted contact') ?> &middot; <?= htmlspecialchars($statusLabel) ?></small></div></div>
    <div class="permission-row"><span>See my live location</span><label class="toggle"><input type="checkbox" class="perm-toggle" data-field="share_live_location" <?= $contact['share_live_location'] ? 'checked' : '' ?>><span></span></label></div>
    <div class="permission-row"><span>Journey start and end alerts</span><label class="toggle"><input type="checkbox" class="perm-toggle" data-field="journey_alerts" <?= $contact['journey_alerts'] ? 'checked' : '' ?>><span></span></label></div>
    <div class="permission-row"><span>SOS alerts</span><label class="toggle"><input type="checkbox" class="perm-toggle" data-field="sos_alerts" <?= $contact['sos_alerts'] ? 'checked' : '' ?>><span></span></label></div>
    <div class="card-buttons">
      <?php if ($isLinked && $isConfirmed): ?>
      <a href="messages.php?to=<?= (int) $contact['contact_user_id'] ?>" class="btn-ghost">Message</a>
      <?php else: ?>
      <button type="button" class="btn-ghost" disabled style="opacity:.5;cursor:not-allowed">Message</button>
      <?php endif; ?>
      <button type="button" class="btn-ghost remove-btn">Remove</button>
    </div>
  </div>
  <?php endforeach; ?>

  <button type="button" class="add-contact-card" data-open-modal="addContactModal">
    <i class="fa-solid fa-user-plus"></i>
    <span>Add someone new</span>
  </button>
</div>

<?php if (empty($contacts)): ?>
<p class="hint" style="color:var(--muted);font-size:11px;margin-top:12px">You have not added any trusted contacts yet. Add someone so they can watch your journeys and step in if you need help.</p>
<?php endif; ?>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<div class="modal-overlay" id="addContactModal">
  <div class="modal">
    <div class="modal-head"><div><h3>Add a trusted contact</h3><p>Invite someone to keep track of your journeys.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body">
      <div class="form-field" style="margin-bottom:12px"><label for="inviteName">Full name</label><input id="inviteName" type="text" placeholder="e.g. Grace Njeri"></div>
      <div class="form-field" style="margin-bottom:12px"><label for="invitePhone">Phone number</label><input id="invitePhone" type="tel" placeholder="e.g. 0712 345 678"></div>
      <div class="form-field"><label for="inviteRelationship">Relationship</label><input id="inviteRelationship" type="text" placeholder="e.g. Sister, Friend, Colleague"></div>
      <p id="inviteError" style="color:#b02a37; margin-top:10px; display:none;"></p>
    </div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="primary" id="sendInviteBtn">Send invite</button>
      <div class="form-field" style="margin-bottom:12px"><label>Full name</label><input type="text" id="addContactName" placeholder="e.g. Grace Njeri"></div>
      <div class="form-field" style="margin-bottom:12px"><label>Phone number</label><input type="tel" id="addContactPhone" placeholder="e.g. 0712 345 678"></div>
      <div class="form-field"><label>Relationship</label><input type="text" id="addContactRelationship" placeholder="e.g. Sister, Friend, Colleague"></div>
      <p class="hint" id="addContactError" style="color:#c94b4b;margin-top:10px;display:none"></p>
    </div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="primary" id="sendInviteBtn">Send invite</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="removeContactModal">
  <div class="modal">
    <div class="modal-head"><div><h3 id="removeContactName">Remove this contact?</h3><p>They will no longer be able to see your journeys.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="danger" id="confirmRemoveBtn">Remove</button>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const inviteName = document.getElementById('inviteName');
    const invitePhone = document.getElementById('invitePhone');
    const inviteRelationship = document.getElementById('inviteRelationship');
    const inviteError = document.getElementById('inviteError');
    const sendInviteBtn = document.getElementById('sendInviteBtn');

    const showError = message => {
      if (!inviteError) return;
      inviteError.textContent = message;
      inviteError.style.display = 'block';
    };

    const clearError = () => {
      if (!inviteError) return;
      inviteError.textContent = '';
      inviteError.style.display = 'none';
    };

    sendInviteBtn?.addEventListener('click', async () => {
      clearError();

      const name = inviteName?.value.trim() || '';
      const phone = invitePhone?.value.trim() || '';
      const relationship = inviteRelationship?.value.trim() || '';

      if (!name) {
        showError('Please enter the contact full name.');
        inviteName.focus();
        return;
      }
      if (!phone) {
        showError('Please enter the contact phone number.');
        invitePhone.focus();
        return;
      }

      sendInviteBtn.disabled = true;
      sendInviteBtn.textContent = 'Sending...';

      try {
        const response = await fetch('backend/api/add-trusted-contact.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ invite_name: name, invite_phone: phone, relationship }),
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
          showError(data.message || 'Unable to add trusted contact.');
          return;
        }

        window.location.reload();
      } catch (error) {
        showError('Unable to connect to the backend.');
        console.error(error);
      } finally {
        sendInviteBtn.disabled = false;
        sendInviteBtn.textContent = 'Send invite';
      }
    });
  });
</script>
<script src="dashboard.js"></script>
<script src="notifications-widget.js"></script>
<script src="trusted-contacts.js"></script>
</body>
</html>
