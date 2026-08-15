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
  /* ── Page wrapper ── */
  html, body {
    min-height: 100vh;
    margin: 0;
    padding: 0;
    background: #0d0e3a;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  /* ── Navbar wrapper: transparent so no box shows on scroll ── */
  .navbar-wrap {
    width: 120%;
    position: sticky;
    top: 0;
    z-index: 9999;
    background: transparent;
    padding: 0;
    display: flex;
    justify-content: center;
  }

  /* ── Navbar pill: centred and properly sized ── */
  .ff-navbar {
    width: 100% !important;
    max-width: 1500px !important;
    margin: 16px auto 0 !important;
    left: unset !important;
    transform: unset !important;
    position: relative !important;
  }

  .ff-navbar .ff-links {
    gap: 32px !important;
  }

  .ff-navbar .ff-actions {
    gap: 8px !important;
    margin-left: auto;
    margin-right: 4px;
  }

  /* ── Login card: smaller, centred with more breathing room ── */
  .card-wrap {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 120%;
    padding: 40px 24px 60px;
  }

  .card {
    max-width: 1050px !important;
    width: 94% !important;
    min-height: 480px !important;
  }

  /* ── Footer ── */
  .footer-wrap {
    width: 120%;
  }

  .ff-footer {
    margin-top: 0 !important;
  }

  /* ── Tablet ── */
  @media (max-width: 768px) {
    .ff-navbar {
      width: 94% !important;
    }
    .ff-navbar .ff-links,
    .ff-navbar .ff-actions {
      display: none !important;
    }
    .ff-navbar .ff-hamburger {
      display: flex !important;
    }
    .card {
      flex-direction: column !important;
      width: 94% !important;
      min-height: auto !important;
    }
    .side-panel {
      width: 100% !important;
      border-radius: 16px 16px 0 0 !important;
      padding: 28px 24px !important;
      min-height: 150px !important;
    }
    .form-panel {
      padding: 28px 24px !important;
    }
    .card-wrap {
      padding: 24px 12px 40px !important;
      align-items: flex-start !important;
    }
  }

  /* ── Mobile ── */
  @media (max-width: 480px) {
    .ff-navbar {
      width: 92% !important;
    }
    .card {
      width: 96% !important;
    }
    .card-wrap {
      padding: 16px 8px 32px !important;
    }
    .form-panel {
      padding: 22px 16px !important;
    }
    .side-panel {
      padding: 22px 16px !important;
      min-height: 130px !important;
    }
    .ff-footer-grid {
      grid-template-columns: 1fr !important;
      gap: 24px !important;
    }
    .ff-footer-brand {
      grid-column: unset !important;
    }
    .ff-footer-bottom {
      flex-direction: column !important;
      align-items: flex-start !important;
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