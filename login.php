<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Future Finder</title>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
<link rel="stylesheet" href="CSS/home-style.css">
<link rel="stylesheet" href="CSS/home-responsive.css">
<link rel="stylesheet" href="CSS/login.css">
<style>
  /* ── Page wrapper so navbar + card stack vertically ── */
  html, body {
    min-height: 100vh;
    margin: 0;
    padding: 0;
    background: #0d0e3a;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  /* ── Navbar wrapper: full width so the pill centres itself ── */
  .navbar-wrap {
    width: 150%;
    position: sticky;
    top: 0;
    z-index: 9999;
    background: #0d0e3a; /* same as page bg so it blends */
    padding-bottom: 8px;
  }

  /* ── Login card: centred, with breathing room top & bottom ── */
  .card-wrap {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 150%;
    padding: 32px 16px 0;
  }

  /* ── Footer: full width at the bottom ── */
  .footer-wrap {
    width: 145%;
  }

  /* ── Navbar: stretch to full width so links & buttons have room ── */
  .ff-navbar {
    width: 96% !important;
    max-width: 1400px !important;
  }

  /* ── Reduce link gap so they don't crash into the action buttons ── */
  .ff-navbar .ff-links {
    gap: 28px !important;
  }

  /* ── Give action buttons a little more space between them ── */
  .ff-navbar .ff-actions {
    gap: 8px !important;
    margin-left: auto;
    margin-right: 8px;
  }

  /* ── Widen the login card to use more of the page ── */
  .card {
    max-width: 1100px !important;
    width: 96% !important;
  }

  /* ── Footer: reduce top gap ── */
  .ff-footer {
    margin-top: 40px !important;
  }

  @media (max-width: 480px) {
    .ff-navbar {
      width: 92% !important;
    }
    .card-wrap {
      padding: 20px 12px 0;
    }
  }
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar-wrap">
  <?php
    $currentPage = 'login.php';
    require_once __DIR__ . '/shared/navbar.php';
  ?>
</div>

<!-- Login card -->
<div class="card-wrap">
  <div class="card">

    <!-- Left panel -->
    <div class="side-panel">
      <div class="side-content">
        <h1 id="sideTitle">Hello, Welcome!</h1>
        <p id="sideSubtitle">Don't have an account?</p>
        <button class="ghost-btn" id="toggleBtn">Register</button>
      </div>
    </div>

    <!-- Right panel: forms -->
    <div class="form-panel">

      <!-- LOGIN FORM -->
      <form class="form login-form active" id="loginForm" novalidate>
        <h2>Login</h2>

        <div class="field">
          <input type="email" id="loginEmail" placeholder="Email" required>
          <svg class="icon" viewBox="0 0 24 24" fill="none"><path d="M3 6.5C3 5.67 3.67 5 4.5 5h15c.83 0 1.5.67 1.5 1.5v11c0 .83-.67 1.5-1.5 1.5h-15c-.83 0-1.5-.67-1.5-1.5v-11Z" stroke="currentColor" stroke-width="1.6"/><path d="m4 6.5 8 6 8-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <span class="error-msg" id="loginEmailError"></span>

        <div class="field">
          <input type="password" id="loginPassword" placeholder="Password" required>
          <svg class="icon" viewBox="0 0 24 24" fill="none"><rect x="5" y="11" width="14" height="9" rx="1.6" stroke="currentColor" stroke-width="1.6"/><path d="M8 11V7.5a4 4 0 0 1 8 0V11" stroke="currentColor" stroke-width="1.6"/></svg>
        </div>
        <span class="error-msg" id="loginPasswordError"></span>

        <button type="submit" class="submit-btn">Login</button>
      </form>

      <!-- REGISTER FORM -->
      <form class="form register-form" id="registerForm" novalidate>
        <h2>Create Account</h2>

        <div class="name-row">
          <div class="field half">
            <input type="text" id="firstName" placeholder="First Name" required>
          </div>
          <div class="field half">
            <input type="text" id="lastName" placeholder="Last Name" required>
          </div>
        </div>
        <span class="error-msg" id="nameError"></span>

        <div class="field">
          <input type="email" id="registerEmail" placeholder="Email" required>
          <svg class="icon" viewBox="0 0 24 24" fill="none"><path d="M3 6.5C3 5.67 3.67 5 4.5 5h15c.83 0 1.5.67 1.5 1.5v11c0 .83-.67 1.5-1.5 1.5h-15c-.83 0-1.5-.67-1.5-1.5v-11Z" stroke="currentColor" stroke-width="1.6"/><path d="m4 6.5 8 6 8-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <span class="error-msg" id="registerEmailError"></span>

        <div class="field">
          <input type="password" id="registerPassword" placeholder="Password" required>
          <svg class="icon" viewBox="0 0 24 24" fill="none"><rect x="5" y="11" width="14" height="9" rx="1.6" stroke="currentColor" stroke-width="1.6"/><path d="M8 11V7.5a4 4 0 0 1 8 0V11" stroke="currentColor" stroke-width="1.6"/></svg>
        </div>
        <span class="error-msg" id="registerPasswordError"></span>

        <button type="submit" class="submit-btn">Create Account</button>
      </form>

      <p class="success-msg" id="successMsg"></p>

    </div>
  </div>
</div>

<!-- Footer -->
<div class="footer-wrap">
  <?php require_once __DIR__ . '/shared/footer.php'; ?>
</div>

<script src="JS/login.js"></script>
</body>
</html>