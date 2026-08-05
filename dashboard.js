const sidebar = document.getElementById('sidebar');
document.getElementById('menu')?.addEventListener('click', () => sidebar.classList.toggle('open'));

document.querySelectorAll('[data-open-modal]').forEach(trigger => {
  trigger.addEventListener('click', () => {
    const id = trigger.getAttribute('data-open-modal');
    document.getElementById(id)?.classList.add('open');
  });
});

document.querySelectorAll('[data-close-modal]').forEach(trigger => {
  trigger.addEventListener('click', () => {
    trigger.closest('.modal-overlay')?.classList.remove('open');
  });
});

document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', e => {
    if (e.target === overlay) overlay.classList.remove('open');
  });
});

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.open').forEach(o => o.classList.remove('open'));
  }
});

document.querySelectorAll('[data-tab-group]').forEach(group => {
  const name = group.getAttribute('data-tab-group');
  group.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
      group.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      const value = tab.getAttribute('data-tab');
      document.querySelectorAll('[data-tab-panel-group="' + name + '"]').forEach(panel => {
        panel.classList.toggle('active', panel.getAttribute('data-tab-panel') === value);
      });
    });
  });
});

const updateProfileBtn = document.getElementById('updateProfileBtn');
if (updateProfileBtn) {
    updateProfileBtn.addEventListener('click', () => {
        const username = document.getElementById('usernameInput').value.trim();
        const formData = new FormData();
        formData.append('action', 'update_username');
        formData.append('username', username);

        fetch('update-profile.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            });
    });
}

const updatePasswordBtn = document.getElementById('updatePasswordBtn');
if (updatePasswordBtn) {
    updatePasswordBtn.addEventListener('click', () => {
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;

        const formData = new FormData();
        formData.append('action', 'update_password');
        formData.append('current_password', currentPassword);
        formData.append('new_password', newPassword);

        fetch('update-profile.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    document.getElementById('currentPassword').value = '';
                    document.getElementById('newPassword').value = '';
                }
            });
    });
}
document.querySelectorAll('.notif-bell').forEach(bell => {
  bell.addEventListener('click', (e) => {
    e.stopPropagation();
    const dropdown = bell.parentElement.querySelector('.notif-dropdown');
    dropdown?.classList.toggle('open');
    document.getElementById('notifDot')?.style.setProperty('display', 'none');
  });
});

document.addEventListener('click', (e) => {
  document.querySelectorAll('.notif-dropdown.open').forEach(dropdown => {
    if (!dropdown.contains(e.target) && !dropdown.previousElementSibling?.contains(e.target)) {
      dropdown.classList.remove('open');
    }
  });
});
