const avatarFileInput = document.getElementById('avatarFileInput');
const changePhotoBtn = document.getElementById('changePhotoBtn');
const removePhotoBtn = document.getElementById('removePhotoBtn');

function updateAvatarEverywhere(avatarPath, initial) {
  const html = avatarPath
    ? '<img src="' + avatarPath + '?v=' + Date.now() + '" class="avatar-img" alt="">'
    : initial;

  document.getElementById('profileAvatarPreview').innerHTML = html;
  document.getElementById('sidebarAvatar').innerHTML = html;
  document.getElementById('headerAvatar').innerHTML = html;
}

changePhotoBtn?.addEventListener('click', () => {
  avatarFileInput.click();
});

avatarFileInput?.addEventListener('change', async () => {
  const file = avatarFileInput.files[0];
  if (!file) return;

  if (file.size > 4 * 1024 * 1024) {
    alert('Please choose an image smaller than 4 MB.');
    avatarFileInput.value = '';
    return;
  }

  const formData = new FormData();
  formData.append('avatar', file);

  changePhotoBtn.textContent = 'Uploading...';
  changePhotoBtn.disabled = true;

  try {
    const response = await fetch('backend/api/upload-avatar.php', {
      method: 'POST',
      body: formData,
    });
    const data = await response.json();

    if (!data.success) {
      alert(data.message || 'That photo could not be uploaded.');
      return;
    }

    updateAvatarEverywhere(data.avatar_path, '');
    if (!removePhotoBtn) {
      location.reload();
    }
  } catch (err) {
    alert('Something went wrong uploading that photo. Please try again.');
  } finally {
    changePhotoBtn.textContent = 'Change photo';
    changePhotoBtn.disabled = false;
    avatarFileInput.value = '';
  }
});

removePhotoBtn?.addEventListener('click', async () => {
  if (!confirm('Remove your profile photo?')) return;

  const formData = new FormData();
  formData.append('remove', '1');

  try {
    const response = await fetch('backend/api/upload-avatar.php', {
      method: 'POST',
      body: formData,
    });
    const data = await response.json();

    if (!data.success) {
      alert(data.message || 'That photo could not be removed.');
      return;
    }

    location.reload();
  } catch (err) {
    alert('Something went wrong removing that photo. Please try again.');
  }
});
