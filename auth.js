function togglePassword(buttonId, inputId) {
  const button = document.getElementById(buttonId);
  const input = document.getElementById(inputId);
  if (!button || !input) return;
  button.addEventListener('click', () => {
    const icon = button.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    }
  });
}

function showError(id, show) {
  const el = document.getElementById(id);
  if (!el) return;
  el.classList.toggle('show', show);
}

togglePassword('toggleLoginPassword', 'loginPassword');
togglePassword('toggleSignupPassword', 'signupPassword');
togglePassword('toggleNewPassword', 'newPassword');
togglePassword('toggleConfirmPassword', 'confirmPassword');

const loginForm = document.getElementById('loginForm');
if (loginForm) {
  loginForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const username = document.getElementById('loginUsername');
    const password = document.getElementById('loginPassword');
    let valid = true;

    if (!username.value.trim()) {
      showError('loginUsernameError', true);
      valid = false;
    } else {
      showError('loginUsernameError', false);
    }

    if (!password.value.trim()) {
      showError('loginPasswordError', true);
      valid = false;
    } else {
      showError('loginPasswordError', false);
    }

    if (valid) {
    const formData = new FormData();
    formData.append('username', username.value.trim());
    formData.append('password', password.value.trim());

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'index.php';
        } else {
            alert('Login failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error logging in:', error);
        alert('An error occurred while logging in.');
    });
}
  });
}

const signupForm = document.getElementById('signupForm');
if (signupForm) {
  signupForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const fullName = document.getElementById('fullName');
    const username = document.getElementById('signupUsername');
    const email = document.getElementById('email');
    const password = document.getElementById('signupPassword');
    const terms = document.getElementById('terms');
    let valid = true;

    if (!fullName.value.trim()) {
      showError('fullNameError', true);
      valid = false;
    } else {
      showError('fullNameError', false);
    }

    if (!username.value.trim()) {
      showError('signupUsernameError', true);
      valid = false;
    } else {
      showError('signupUsernameError', false);
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value.trim())) {
      showError('emailError', true);
      valid = false;
    } else {
      showError('emailError', false);
    }

    if (password.value.trim().length < 6) {
      showError('signupPasswordError', true);
      valid = false;
    } else {
      showError('signupPasswordError', false);
    }

    if (!terms.checked) {
      showError('termsError', true);
      valid = false;
    } else {
      showError('termsError', false);
    }

    if (valid) {
      const formData = new FormData();
formData.append('fullName', fullName.value.trim());
formData.append('username', username.value.trim());
formData.append('email', email.value.trim());
formData.append('password', password.value.trim());

fetch('signup.php', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        alert('Account created successfully!');
        window.location.href = 'login.html';
    } else {
        alert('Error: ' + data.message);
    }
})
.catch(error => {
    console.error('Error submitting form:', error);
    alert('An error occurred while creating your account.');
});
    }
  });
}

const forgotForm = document.getElementById('forgotForm');
if (forgotForm) {
  forgotForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const contact = document.getElementById('resetContact');

    if (!contact.value.trim()) {
      showError('resetContactError', true);
      return;
    }
    showError('resetContactError', false);

    alert('Once the SafariTrak system is connected, a reset link will be sent to ' + contact.value.trim() + '.');
  });
}

const resetForm = document.getElementById('resetForm');
if (resetForm) {
  resetForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    let valid = true;

    if (newPassword.value.trim().length < 6) {
      showError('newPasswordError', true);
      valid = false;
    } else {
      showError('newPasswordError', false);
    }

    if (confirmPassword.value.trim() !== newPassword.value.trim() || !confirmPassword.value.trim()) {
      showError('confirmPasswordError', true);
      valid = false;
    } else {
      showError('confirmPasswordError', false);
    }

    if (valid) {
      alert('Once the SafariTrak system is connected, your password will be updated and you can log in with it.');
    }
  });
}
