// Elements
const toggleBtn = document.getElementById('toggleBtn');
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');
const sideTitle = document.getElementById('sideTitle');
const sideSubtitle = document.getElementById('sideSubtitle');
const successMsg = document.getElementById('successMsg');

let showingLogin = true;

// Toggle between Login and Register views
toggleBtn.addEventListener('click', () => {
  showingLogin = !showingLogin;
  clearErrors();

  if (showingLogin) {
    registerForm.classList.remove('active');
    loginForm.classList.add('active');
    sideTitle.textContent = 'Hello, Welcome!';
    sideSubtitle.textContent = "Don't have an account?";
    toggleBtn.textContent = 'Register';
  } else {
    loginForm.classList.remove('active');
    registerForm.classList.add('active');
    sideTitle.textContent = 'Welcome Back!';
    sideSubtitle.textContent = 'Already have an account?';
    toggleBtn.textContent = 'Login';
  }
});

// ---------- Validation helpers ----------
function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function setError(inputEl, errorEl, message) {
  errorEl.textContent = message || '';
  inputEl.classList.toggle('invalid', !!message);
}

function clearErrors() {
  document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
  document.querySelectorAll('input').forEach(el => el.classList.remove('invalid'));
}

function flashSuccess(text) {
  successMsg.textContent = text;
  successMsg.classList.add('show');
  setTimeout(() => successMsg.classList.remove('show'), 2600);
}

// Applies a {field: message} error object returned by PHP to the right inputs
function applyServerErrors(errors, fieldMap) {
  Object.entries(fieldMap).forEach(([serverKey, els]) => {
    setError(els.input, els.errorEl, errors?.[serverKey] || '');
  });
}

// ---------- Login submit ----------
loginForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  clearErrors();

  const email = document.getElementById('loginEmail');
  const password = document.getElementById('loginPassword');
  const emailError = document.getElementById('loginEmailError');
  const passwordError = document.getElementById('loginPasswordError');

  let valid = true;

  if (!isValidEmail(email.value.trim())) {
    setError(email, emailError, 'Enter a valid email address');
    valid = false;
  }

  if (password.value.length < 6) {
    setError(password, passwordError, 'Password must be at least 6 characters');
    valid = false;
  }

  if (!valid) return;

  const submitBtn = loginForm.querySelector('.submit-btn');
  submitBtn.disabled = true;
  submitBtn.textContent = 'Logging in...';

  try {
    const formData = new FormData();
    formData.append('email', email.value.trim());
    formData.append('password', password.value);

    const res = await fetch('login.php', {
      method: 'POST',
      body: formData
    });
    const data = await res.json();

    if (data.success) {
      flashSuccess('Logged in successfully!');
      loginForm.reset();
      // Redirect to the right dashboard based on role (admin vs user)
      setTimeout(() => {
        window.location.href = data.redirect || 'dashboard.php';
      }, 800);
    } else if (data.errors) {
      applyServerErrors(data.errors, {
        email: { input: email, errorEl: emailError },
        password: { input: password, errorEl: passwordError }
      });
    } else {
      // Generic error, e.g. "Incorrect email or password"
      setError(password, passwordError, data.message || 'Login failed');
    }
  } catch (err) {
    setError(password, passwordError, 'Could not reach the server. Is XAMPP running?');
  } finally {
    submitBtn.disabled = false;
    submitBtn.textContent = 'Login';
  }
});

// ---------- Register submit ----------
registerForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  clearErrors();

  const firstName = document.getElementById('firstName');
  const lastName = document.getElementById('lastName');
  const email = document.getElementById('registerEmail');
  const password = document.getElementById('registerPassword');
  const nameError = document.getElementById('nameError');
  const emailError = document.getElementById('registerEmailError');
  const passwordError = document.getElementById('registerPasswordError');

  let valid = true;

  if (!firstName.value.trim() || !lastName.value.trim()) {
    nameError.textContent = 'First and last name are required';
    firstName.classList.toggle('invalid', !firstName.value.trim());
    lastName.classList.toggle('invalid', !lastName.value.trim());
    valid = false;
  }

  if (!isValidEmail(email.value.trim())) {
    setError(email, emailError, 'Enter a valid email address');
    valid = false;
  }

  if (password.value.length < 6) {
    setError(password, passwordError, 'Password must be at least 6 characters');
    valid = false;
  }

  if (!valid) return;

  const submitBtn = registerForm.querySelector('.submit-btn');
  submitBtn.disabled = true;
  submitBtn.textContent = 'Creating account...';

  try {
    const formData = new FormData();
    formData.append('firstName', firstName.value.trim());
    formData.append('lastName', lastName.value.trim());
    formData.append('email', email.value.trim());
    formData.append('password', password.value);

    const res = await fetch('register.php', {
      method: 'POST',
      body: formData
    });
    const data = await res.json();

    if (data.success) {
      flashSuccess('Account created! You can now log in.');
      registerForm.reset();
      setTimeout(() => {
        toggleBtn.click();
      }, 1200);
    } else if (data.errors) {
      applyServerErrors(data.errors, {
        name: { input: firstName, errorEl: nameError },
        email: { input: email, errorEl: emailError },
        password: { input: password, errorEl: passwordError }
      });
    } else {
      setError(email, emailError, data.message || 'Registration failed');
    }
  } catch (err) {
    setError(email, emailError, 'Could not reach the server. Is XAMPP running?');
  } finally {
    submitBtn.disabled = false;
    submitBtn.textContent = 'Create Account';
  }
});

