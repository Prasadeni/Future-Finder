<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/Includes/db_connection.php';

// ---------- Handle AJAX login POST ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $action   = $_POST['action']   ?? 'login';

    // ── REGISTER ─────────────────────────────────────────
    if ($action === 'register') {
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName  = trim($_POST['lastName']  ?? '');
        $errors    = [];

        if (!$firstName || !$lastName)                      $errors['name']     = 'First and last name are required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))     $errors['email']    = 'Valid email is required.';
        if (strlen($password) < 6)                          $errors['password'] = 'Password must be at least 6 characters.';

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }

        // Check email not already used
        $chk = mysqli_prepare($conn, "SELECT id FROM Users WHERE email = ?");
        mysqli_stmt_bind_param($chk, 's', $email);
        mysqli_stmt_execute($chk);
        mysqli_stmt_store_result($chk);
        if (mysqli_stmt_num_rows($chk) > 0) {
            http_response_code(409);
            echo json_encode(['success' => false, 'errors' => ['email' => 'An account with this email already exists.']]);
            exit;
        }
        mysqli_stmt_close($chk);

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ins  = mysqli_prepare($conn,
            "INSERT INTO Users (first_name, last_name, email, password, role) VALUES (?,?,?,?,'user')");
        mysqli_stmt_bind_param($ins, 'ssss', $firstName, $lastName, $email, $hash);

        if (mysqli_stmt_execute($ins)) {
            mysqli_stmt_close($ins);
            echo json_encode(['success' => true, 'message' => 'Account created! Please log in.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Could not create account. Please try again.']);
        }
        exit;
    }

    // ── LOGIN ─────────────────────────────────────────────
    $errors = [];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email']    = 'Valid email is required.';
    if (strlen($password) < 6)                      $errors['password'] = 'Password must be at least 6 characters.';

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    $stmt = mysqli_prepare($conn,
        "SELECT id, first_name, last_name, password, role FROM Users WHERE email = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
        exit;
    }

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Incorrect email or password.']);
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name']  = $user['last_name'];
    $_SESSION['role']       = $user['role'];

    $redirect = ($user['role'] === 'admin') ? 'Admin/admin.php' : 'User/dashboard.php';
    echo json_encode(['success' => true, 'redirect' => $redirect]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Future Finder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- home-style.css and home-responsive.css kept for navbar/footer compatibility -->
    <link rel="stylesheet" href="CSS/home-style.css">
    <link rel="stylesheet" href="CSS/home-responsive.css">
    <link rel="stylesheet" href="CSS/login.css">

    <style>
    /* ============================================================
       LOGIN PAGE LAYOUT FIX
       The shared navbar.php injects a .ff-navbar pill with
       margin: 24px auto 0 and position: relative (not fixed),
       so the body just needs to flow normally.
       We remove the conflicting body flex-centering from login.css
       and use .card-wrap with padding-top to centre the card
       in the remaining viewport height below the navbar.
       ============================================================ */

    /* Override the body rules from login.css that break layout */
    body {
        display: block !important;          /* remove flex that breaks navbar */
        align-items: unset !important;
        justify-content: unset !important;
        min-height: 100vh;
        background: #0d0e3a;
        padding: 0;
        margin: 0;
    }

    /* Wrapper that centres the card vertically in remaining space */
    .card-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        /* Account for navbar height (76px pill + 24px margin top + 16px gap) */
        min-height: calc(100vh - 116px);
        padding: 40px 20px;
    }

    /* ── Logo fix in side panel ──────────────────────────── */
    /* The shared footer uses ../images/logo.png which breaks
       from root level. We patch the footer logo via CSS
       to hide the broken img tag — the footer.php text
       "Future Finder" still shows correctly. */
    
    </style>
</head>
<body>

    <!-- Shared Navbar (from shared/navbar.php) -->
    <?php
        $currentPage = 'login.php';
        require_once __DIR__ . '/shared/navbar.php';
    ?>

    <!-- Login / Register Card -->
    <div class="card-wrap">
        <div class="card">

            <!-- Left teal panel -->
            <div class="side-panel">
                <div class="side-content">
                    <h1 id="sideTitle">Hello, Welcome!</h1>
                    <p id="sideSubtitle">Don't have an account?</p>
                    <button class="ghost-btn" id="toggleBtn">Register</button>
                </div>
            </div>

            <!-- Right form panel -->
            <div class="form-panel">

                <!-- ── LOGIN FORM ── -->
                <form class="form login-form active" id="loginForm" novalidate>
                    <h2>Login</h2>

                    <div class="field">
                        <input type="email" id="loginEmail" placeholder="Email" required>
                        <svg class="icon" viewBox="0 0 24 24" fill="none">
                            <path d="M3 6.5C3 5.67 3.67 5 4.5 5h15c.83 0 1.5.67 1.5 1.5v11c0 .83-.67 1.5-1.5 1.5h-15c-.83 0-1.5-.67-1.5-1.5v-11Z"
                                  stroke="currentColor" stroke-width="1.6"/>
                            <path d="m4 6.5 8 6 8-6" stroke="currentColor" stroke-width="1.6"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="error-msg" id="loginEmailError"></span>

                    <div class="field">
                        <input type="password" id="loginPassword" placeholder="Password" required>
                        <svg class="icon" viewBox="0 0 24 24" fill="none">
                            <rect x="5" y="11" width="14" height="9" rx="1.6"
                                  stroke="currentColor" stroke-width="1.6"/>
                            <path d="M8 11V7.5a4 4 0 0 1 8 0V11"
                                  stroke="currentColor" stroke-width="1.6"/>
                        </svg>
                    </div>
                    <span class="error-msg" id="loginPasswordError"></span>

                    <!-- General error (wrong password etc.) -->
                    <span class="error-msg" id="loginGeneralError"></span>

                    <button type="submit" class="submit-btn">Login</button>
                </form>

                <!-- ── REGISTER FORM ── -->
                <form class="form register-form" id="registerForm" novalidate>
                    <h2>Create Account</h2>

                    <div class="name-row">
                        <div class="field half">
                            <input type="text" id="firstName" placeholder="First Name" required>
                        </div>
                        <div class="field half">
                            <input type="text" id="lastName"  placeholder="Last Name"  required>
                        </div>
                    </div>
                    <span class="error-msg" id="nameError"></span>

                    <div class="field">
                        <input type="email" id="registerEmail" placeholder="Email" required>
                        <svg class="icon" viewBox="0 0 24 24" fill="none">
                            <path d="M3 6.5C3 5.67 3.67 5 4.5 5h15c.83 0 1.5.67 1.5 1.5v11c0 .83-.67 1.5-1.5 1.5h-15c-.83 0-1.5-.67-1.5-1.5v-11Z"
                                  stroke="currentColor" stroke-width="1.6"/>
                            <path d="m4 6.5 8 6 8-6" stroke="currentColor" stroke-width="1.6"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="error-msg" id="registerEmailError"></span>

                    <div class="field">
                        <input type="password" id="registerPassword"
                               placeholder="Password (min 6 characters)" required>
                        <svg class="icon" viewBox="0 0 24 24" fill="none">
                            <rect x="5" y="11" width="14" height="9" rx="1.6"
                                  stroke="currentColor" stroke-width="1.6"/>
                            <path d="M8 11V7.5a4 4 0 0 1 8 0V11"
                                  stroke="currentColor" stroke-width="1.6"/>
                        </svg>
                    </div>
                    <span class="error-msg" id="registerPasswordError"></span>

                    <button type="submit" class="submit-btn">Create Account</button>
                </form>

                <p class="success-msg" id="successMsg"></p>

            </div><!-- /form-panel -->
        </div><!-- /card -->
    </div><!-- /card-wrap -->

    <!-- Shared Footer -->
    <?php require_once __DIR__ . '/shared/footer.php'; ?>

    <!-- Login / Register JS -->
    <script>
    // ============================================================
    // login.js logic — inline here so no separate file needed
    // Handles toggle, login POST, register POST
    // ============================================================

    const toggleBtn    = document.getElementById('toggleBtn');
    const loginForm    = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const sideTitle    = document.getElementById('sideTitle');
    const sideSubtitle = document.getElementById('sideSubtitle');
    const successMsg   = document.getElementById('successMsg');
    let showingLogin   = true;

    // ── Toggle between Login and Register ──────────────────
    toggleBtn.addEventListener('click', () => {
        showingLogin = !showingLogin;
        clearErrors();

        if (showingLogin) {
            registerForm.classList.remove('active');
            loginForm.classList.add('active');
            sideTitle.textContent    = 'Hello, Welcome!';
            sideSubtitle.textContent = "Don't have an account?";
            toggleBtn.textContent    = 'Register';
        } else {
            loginForm.classList.remove('active');
            registerForm.classList.add('active');
            sideTitle.textContent    = 'Welcome Back!';
            sideSubtitle.textContent = 'Already have an account?';
            toggleBtn.textContent    = 'Login';
        }
    });

    function isValidEmail(e) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e); }

    function setError(input, errorEl, msg) {
        if (errorEl) errorEl.textContent = msg || '';
        if (input)   input.classList.toggle('invalid', !!msg);
    }

    function clearErrors() {
        document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
        document.querySelectorAll('input').forEach(el => el.classList.remove('invalid'));
    }

    function flashSuccess(text) {
        successMsg.textContent = text;
        successMsg.classList.add('show');
        setTimeout(() => { successMsg.classList.remove('show'); successMsg.textContent = ''; }, 3000);
    }

    // ── LOGIN submit ───────────────────────────────────────
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();

        const email    = document.getElementById('loginEmail');
        const password = document.getElementById('loginPassword');
        const emailErr = document.getElementById('loginEmailError');
        const passErr  = document.getElementById('loginPasswordError');
        const genErr   = document.getElementById('loginGeneralError');

        let valid = true;
        if (!isValidEmail(email.value.trim())) { setError(email, emailErr, 'Enter a valid email address'); valid = false; }
        if (password.value.length < 6)         { setError(password, passErr, 'Password must be at least 6 characters'); valid = false; }
        if (!valid) return;

        const btn = loginForm.querySelector('.submit-btn');
        btn.disabled = true; btn.textContent = 'Logging in...';

        try {
            const fd = new FormData();
            fd.append('action',   'login');
            fd.append('email',    email.value.trim());
            fd.append('password', password.value);

            const res  = await fetch('login.php', { method: 'POST', body: fd });
            const data = await res.json();

            if (data.success) {
                flashSuccess('Logged in! Redirecting...');
                setTimeout(() => { window.location.href = data.redirect; }, 600);
            } else if (data.errors) {
                if (data.errors.email)    setError(email,    emailErr, data.errors.email);
                if (data.errors.password) setError(password, passErr,  data.errors.password);
            } else {
                // Wrong credentials — show under password field
                setError(password, genErr, data.message || 'Login failed. Please try again.');
            }
        } catch {
            setError(password, genErr, 'Cannot reach server. Is XAMPP running?');
        }

        btn.disabled = false; btn.textContent = 'Login';
    });

    // ── REGISTER submit ────────────────────────────────────
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();

        const firstName = document.getElementById('firstName');
        const lastName  = document.getElementById('lastName');
        const email     = document.getElementById('registerEmail');
        const password  = document.getElementById('registerPassword');
        const nameErr   = document.getElementById('nameError');
        const emailErr  = document.getElementById('registerEmailError');
        const passErr   = document.getElementById('registerPasswordError');

        let valid = true;
        if (!firstName.value.trim() || !lastName.value.trim()) {
            nameErr.textContent = 'First and last name are required.';
            if (!firstName.value.trim()) firstName.classList.add('invalid');
            if (!lastName.value.trim())  lastName.classList.add('invalid');
            valid = false;
        }
        if (!isValidEmail(email.value.trim())) { setError(email, emailErr, 'Enter a valid email address'); valid = false; }
        if (password.value.length < 6)         { setError(password, passErr, 'Password must be at least 6 characters'); valid = false; }
        if (!valid) return;

        const btn = registerForm.querySelector('.submit-btn');
        btn.disabled = true; btn.textContent = 'Creating account...';

        try {
            const fd = new FormData();
            fd.append('action',    'register');
            fd.append('firstName', firstName.value.trim());
            fd.append('lastName',  lastName.value.trim());
            fd.append('email',     email.value.trim());
            fd.append('password',  password.value);

            const res  = await fetch('login.php', { method: 'POST', body: fd });
            const data = await res.json();

            if (data.success) {
                registerForm.reset();
                flashSuccess('✅ Account created! Please log in.');
                // Auto-switch back to login form after 1.5s
                setTimeout(() => { showingLogin = false; toggleBtn.click(); }, 1500);
            } else if (data.errors) {
                if (data.errors.name)     { nameErr.textContent = data.errors.name; firstName.classList.add('invalid'); }
                if (data.errors.email)    setError(email,    emailErr, data.errors.email);
                if (data.errors.password) setError(password, passErr,  data.errors.password);
            } else {
                setError(email, emailErr, data.message || 'Registration failed. Please try again.');
            }
        } catch {
            setError(email, emailErr, 'Cannot reach server. Is XAMPP running?');
        }

        btn.disabled = false; btn.textContent = 'Create Account';
    });
    </script>
</body>
</html>