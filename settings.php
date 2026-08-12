<?php
require __DIR__ . '/backend/includes/auth-guard.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SafariTrak | Settings</title>
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
    <a href="messages.php"><i class="fa-regular fa-message"></i>Messages <em>3</em></a>
    <a href="trusted-contacts.php"><i class="fa-solid fa-user-group"></i>Trusted Contacts</a>
    <a href="safety.php"><i class="fa-solid fa-shield-halved"></i>Safety</a>
  </nav>
  <div class="bottom">
    <a class="active" href="settings.php"><i class="fa-solid fa-gear"></i>Settings</a>
    <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
    <div class="account"><span><?= htmlspecialchars(strtoupper(substr($userName, 0, 1))) ?></span><div><b><?= htmlspecialchars($userName) ?></b><small>Traveler</small></div></div>
  </div>
</aside>

<main>
<header>
  <button class="menu" id="menu"><i class="fa-solid fa-bars"></i></button>
  <div><label>YOUR ACCOUNT</label><h1>Settings</h1></div>
  <div class="head-actions">
    <div class="notif-wrap">
      <button type="button" class="notif-bell" id="notifBell"><i class="fa-regular fa-bell"></i><span class="notif-dot" id="notifDot"></span></button>
      <div class="notif-dropdown" id="notifDropdown">
        <div class="notif-dropdown-head"><b>Notifications</b><a href="notifications.php">View all</a></div>
        <div class="notif-list">
          <div class="notif-item unread"><i class="fa-solid fa-route"></i><div><b>Journey started</b><small>Nairobi to Nyeri &middot; 8:40 AM</small></div></div>
          <div class="notif-item unread"><i class="fa-regular fa-message"></i><div><b>New message from Mary Wanjiku</b><small>Let me know when you arrive &middot; 10 min ago</small></div></div>
          <div class="notif-item"><i class="fa-solid fa-location-arrow"></i><div><b>John Mwangi is now watching your journey</b><small>Yesterday</small></div></div>
          <div class="notif-item"><i class="fa-solid fa-flag-checkered"></i><div><b>Journey completed</b><small>Nairobi to Meru &middot; 2 days ago</small></div></div>
        </div>
      </div>
    </div>
    <div class="avatar"><?= htmlspecialchars(strtoupper(substr($userName, 0, 1))) ?></div>
  </div>
</header>

<div class="content">

<div class="card">
  <div class="settings-tabs" data-tab-group="settings">
    <button type="button" class="tab active" data-tab="profile">Profile</button>
    <button type="button" class="tab" data-tab="notifications">Notifications</button>
    <button type="button" class="tab" data-tab="privacy">Privacy</button>
    <button type="button" class="tab" data-tab="account">Account</button>
  </div>

  <div class="settings-panel active" data-tab-panel-group="settings" data-tab-panel="profile">
    <div class="avatar-row">
      <?php if (!empty($avatarPath)): ?>
        <div class="big-avatar" id="bigAvatar" style="background-image:url('<?= htmlspecialchars($avatarPath) ?>')"></div>
      <?php else: ?>
        <div class="big-avatar" id="bigAvatar"><?= strtoupper(substr($userName, 0, 1)) ?></div>
      <?php endif; ?>
      <div>
        <button type="button" class="btn-ghost" id="changePhotoBtn">Change photo</button>
        <input type="file" id="avatarInput" accept="image/*" style="display:none">
      </div>
    </div>
    <div class="form-grid" style="padding:0">
      <div class="form-field"><label>Full name</label><input id="profileFullName" type="text" value="<?= htmlspecialchars($userName) ?>"></div>
      <div class="form-field"><label>Phone number</label><input id="profilePhone" type="tel" value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>" placeholder="0712 345 678"></div>
      <div class="form-field full"><label>Email address</label><input id="profileEmail" type="email" value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" placeholder="you@example.com"></div>
      <div class="form-field full"><label>Home address</label><input id="profileAddress" type="text" value="<?= htmlspecialchars($currentUser['home_address'] ?? '') ?>" placeholder="Used to suggest your usual routes"></div>
    </div>
    <div class="form-actions" style="padding-left:0;padding-right:0">
      <button type="button" class="btn-primary" id="saveProfileBtn">Save changes</button>
    </div>
  </div>

  <div class="settings-panel" data-tab-panel-group="settings" data-tab-panel="notifications">
    <div class="toggle-row"><span><b>Journey started and completed</b><small>Get notified when your journeys begin and end</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="toggle-row"><span><b>New messages</b><small>Get notified when a trusted contact sends you a message</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="toggle-row"><span><b>Route deviation</b><small>Get notified if you move off your planned route</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="toggle-row"><span><b>SOS alerts from contacts</b><small>Get notified if someone you are watching sends an SOS</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="toggle-row"><span><b>Product updates</b><small>Occasional emails about new SafariTrak features</small></span><label class="toggle"><input type="checkbox"><span></span></label></div>
  </div>

  <div class="settings-panel" data-tab-panel-group="settings" data-tab-panel="privacy">
    <div class="toggle-row"><span><b>Show my journey history to trusted contacts</b><small>They can see your past trips, not just live journeys</small></span><label class="toggle"><input type="checkbox"><span></span></label></div>
    <div class="toggle-row"><span><b>Allow group journeys</b><small>Let organizers add you to group trips</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
    <div class="toggle-row"><span><b>Discoverable by phone number</b><small>Let people find and add you as a trusted contact using your number</small></span><label class="toggle"><input type="checkbox" checked><span></span></label></div>
  </div>

  <div class="settings-panel" data-tab-panel-group="settings" data-tab-panel="account">
    <div class="panel-section" style="margin-bottom:24px;">
      <h3>Account details</h3>
      <div class="form-grid" style="padding:0">
        <div class="form-field full"><label>Username</label><input type="text" value="<?= htmlspecialchars($currentUser['username'] ?? '') ?>" readonly></div>
        <div class="form-field full"><label>Email address</label><input type="email" value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" readonly></div>
        <div class="form-field full"><label>Phone number</label><input type="tel" value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>" readonly></div>
      </div>
    </div>

    <div class="panel-section" style="margin-bottom:24px;">
      <h3>Change password</h3>
      <div class="form-field" style="max-width:360px;margin-bottom:14px"><input type="password" id="accountCurrentPassword" placeholder="Enter current password"></div>
      <p class="field-error" id="accountCurrentPasswordError">Enter your current password</p>
      <div class="form-field" style="max-width:360px;margin-bottom:14px"><input type="password" id="accountNewPassword" placeholder="Enter new password"></div>
      <p class="field-error" id="accountNewPasswordError">New password must be at least 6 characters</p>
      <div class="form-field" style="max-width:360px;margin-bottom:14px"><input type="password" id="accountConfirmPassword" placeholder="Confirm new password"></div>
      <p class="field-error" id="accountConfirmPasswordError">Passwords must match</p>
      <button type="button" class="btn-primary" id="updatePasswordBtn">Update password</button>
    </div>

    <hr style="border:0;border-top:1px solid var(--line);margin:22px 0">
    <button type="button" class="btn-ghost" style="color:#c94b4b;border-color:#f3d4d4" data-open-modal="deleteAccountModal">Delete my account</button>
    <hr style="border:0;border-top:1px solid var(--line);margin:22px 0">
    <a class="btn-ghost" href="admin-dashboard.php"><i class="fa-solid fa-building"></i>Manage an organization</a>
  </div>

</div>

</div>
<footer>&copy; <?= date('Y') ?> SafariTrak <span>Navigate. Track. Share. Connect. Stay Safe.</span></footer>
</main>
</div>

<div class="modal-overlay" id="deleteAccountModal">
  <div class="modal">
    <div class="modal-head"><div><h3>Delete your account?</h3><p>This removes all your journeys, contacts and messages.</p></div><button class="modal-close" type="button" data-close-modal><i class="fa-solid fa-xmark"></i></button></div>
    <div class="modal-body"><p>This cannot be undone. We recommend downloading your journey history first once that feature is available.</p></div>
    <div class="modal-actions">
      <button type="button" class="ghost" data-close-modal>Cancel</button>
      <button type="button" class="danger" id="deleteAccountConfirmBtn">Delete account</button>
    </div>
  </div>
</div>

<script>
  (function(){
    const changeBtn = document.getElementById('changePhotoBtn');
    const input = document.getElementById('avatarInput');
    const bigAvatar = document.getElementById('bigAvatar');

    async function uploadAvatar(file) {
      try {
        const fd = new FormData();
        fd.append('avatar', file);

        const res = await fetch('backend/api/upload-avatar.php', {
          method: 'POST',
          body: fd
        });
        const data = await res.json().catch(() => null);
        if (!res.ok || !data || !data.success) {
          throw new Error(data && data.message ? data.message : 'Upload failed');
        }
        return data;
      } catch (err) {
        throw err;
      }
    }

    function showToast(message, kind = 'info') {
      const el = document.createElement('div');
      el.textContent = message;
      el.setAttribute('role', 'status');
      const bg = kind === 'error' ? '#c94b4b' : '#39a859';
      el.style.cssText = 'position:fixed;right:20px;bottom:20px;padding:12px 16px;color:#fff;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.15);font-weight:600;z-index:9999;background:'+bg+';opacity:0;transform:translateY(8px);transition:opacity .18s,transform .18s';
      document.body.appendChild(el);
      requestAnimationFrame(()=>{ el.style.opacity = '1'; el.style.transform = 'translateY(0)'; });
      setTimeout(()=>{ el.style.opacity = '0'; el.style.transform = 'translateY(8px)'; setTimeout(()=>el.remove(),200); }, 3000);
    }

    if (changeBtn && input) {
      changeBtn.addEventListener('click', () => input.click());

      input.addEventListener('change', async (e) => {
        const file = e.target.files && e.target.files[0];
        if (!file) return;
        if (!file.type.startsWith('image/')) {
          alert('Please choose an image file.');
          return;
        }

        // show local preview immediately
        const reader = new FileReader();
        reader.onload = function(ev) {
          bigAvatar.style.backgroundImage = `url(${ev.target.result})`;
          bigAvatar.textContent = '';
          bigAvatar.classList.add('has-image');
        };
        reader.readAsDataURL(file);

        // upload in background
        changeBtn.disabled = true;
        changeBtn.textContent = 'Uploading...';
        try {
          const result = await uploadAvatar(file);
          // if server returns a final URL, use it
          if (result.url) {
            bigAvatar.style.backgroundImage = `url(${result.url})`;
            bigAvatar.textContent = '';
          }
          // also update header avatar if present
          const headerAvatar = document.querySelector('.avatar');
          if (headerAvatar) {
            headerAvatar.style.backgroundImage = `url(${result.url})`;
            headerAvatar.textContent = '';
          }
        } catch (err) {
          alert(err.message || 'Upload failed');
        } finally {
          changeBtn.disabled = false;
          changeBtn.textContent = 'Change photo';
        }
      });
    }
    
    // Save profile handler
    const saveBtn = document.getElementById('saveProfileBtn');
    if (saveBtn) {
      saveBtn.addEventListener('click', async () => {
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';
        const payload = {
          full_name: document.getElementById('profileFullName').value.trim(),
          phone: document.getElementById('profilePhone').value.trim(),
          email: document.getElementById('profileEmail').value.trim(),
          home_address: document.getElementById('profileAddress').value.trim()
        };
        try {
          const res = await fetch('backend/api/update-profile.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          });
          const data = await res.json().catch(() => null);
          if (!res.ok || !data || !data.success) {
            throw new Error(data && data.message ? data.message : 'Save failed');
          }
          // update UI name and greeting
          const headerNameEl = document.querySelector('.account b');
          if (headerNameEl) headerNameEl.textContent = payload.full_name;
          const greetingEl = document.getElementById('greeting');
          if (greetingEl) greetingEl.textContent = greetingEl.textContent.split(',')[0] + ', ' + payload.full_name;
          showToast('Changes saved');
        } catch (err) {
          showToast(err.message || 'Failed to save profile', 'error');
        } finally {
          saveBtn.disabled = false;
          saveBtn.textContent = 'Save changes';
        }
      });
    }

    const updatePasswordBtn = document.getElementById('updatePasswordBtn');
    if (updatePasswordBtn) {
      const currentInput = document.getElementById('accountCurrentPassword');
      const newInput = document.getElementById('accountNewPassword');
      const confirmInput = document.getElementById('accountConfirmPassword');
      const currentError = document.getElementById('accountCurrentPasswordError');
      const newError = document.getElementById('accountNewPasswordError');
      const confirmError = document.getElementById('accountConfirmPasswordError');

      const clearPasswordErrors = () => {
        if (currentError) currentError.classList.remove('show');
        if (newError) newError.classList.remove('show');
        if (confirmError) confirmError.classList.remove('show');
      };

      updatePasswordBtn.addEventListener('click', async () => {
        clearPasswordErrors();
        let valid = true;

        if (!currentInput.value.trim()) {
          currentError.textContent = 'Enter your current password';
          currentError.classList.add('show');
          valid = false;
        }
        if (newInput.value.trim().length < 6) {
          newError.textContent = 'New password must be at least 6 characters';
          newError.classList.add('show');
          valid = false;
        }
        if (confirmInput.value.trim() !== newInput.value.trim()) {
          confirmError.textContent = 'Passwords must match';
          confirmError.classList.add('show');
          valid = false;
        }
        if (!valid) return;

        updatePasswordBtn.disabled = true;
        updatePasswordBtn.textContent = 'Updating...';

        try {
          const res = await fetch('backend/api/change-password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              current_password: currentInput.value,
              new_password: newInput.value,
              confirm_password: confirmInput.value
            })
          });
          const data = await res.json().catch(() => null);
          if (!res.ok || !data || !data.success) {
            const message = data && data.message ? data.message : 'Unable to update password';
            if (data && data.errors) {
              if (data.errors.current_password) {
                currentError.textContent = data.errors.current_password;
                currentError.classList.add('show');
              }
              if (data.errors.new_password) {
                newError.textContent = data.errors.new_password;
                newError.classList.add('show');
              }
              if (data.errors.confirm_password) {
                confirmError.textContent = data.errors.confirm_password;
                confirmError.classList.add('show');
              }
            }
            throw new Error(message);
          }

          currentInput.value = '';
          newInput.value = '';
          confirmInput.value = '';
          showToast('Password updated successfully');
        } catch (err) {
          showToast(err.message || 'Password update failed', 'error');
        } finally {
          updatePasswordBtn.disabled = false;
          updatePasswordBtn.textContent = 'Update password';
        }
      });
    }

  const deleteAccountBtn = document.getElementById('deleteAccountConfirmBtn');
  if (deleteAccountBtn) {
    deleteAccountBtn.addEventListener('click', async () => {
      if (!confirm('Are you sure you want to permanently delete your account? This cannot be undone.')) {
        return;
      }

      deleteAccountBtn.disabled = true;
      deleteAccountBtn.textContent = 'Deleting...';

      try {
        const res = await fetch('backend/api/delete-account.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' }
        });
        const data = await res.json().catch(() => null);
        if (!res.ok || !data || !data.success) {
          throw new Error(data && data.message ? data.message : 'Failed to delete account');
        }
        window.location.href = data.redirect || 'login.html';
      } catch (err) {
        showToast(err.message || 'Account deletion failed', 'error');
      } finally {
        deleteAccountBtn.disabled = false;
        deleteAccountBtn.textContent = 'Delete account';
      }
    });
  }
  })();
</script>
<script src="dashboard.js"></script>
</body>
</html>
