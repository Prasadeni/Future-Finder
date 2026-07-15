<?php

if (session_status() === PHP_SESSION_NONE) session_start();

$isLoggedIn  = isset($_SESSION['user_id']);
$firstName   = $isLoggedIn ? htmlspecialchars($_SESSION['first_name'] ?? '') : '';
$initials    = $isLoggedIn ? strtoupper(substr($firstName, 0, 1) . substr(htmlspecialchars($_SESSION['last_name'] ?? ''), 0, 1)) : '';
$currentPage = $currentPage ?? basename($_SERVER['PHP_SELF']);

function navActive($page, $current) {
    return $page === $current ? 'class="active"' : '';
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

.ff-navbar *,
.ff-navbar *::before,
.ff-navbar *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

.ff-navbar {
    width: 150%;
    max-width: 1280px;
    margin: 24px auto 0;
    background: rgba(83, 79, 148, 0.92);      
    backdrop-filter: blur(14px) saturate(1.4);
    -webkit-backdrop-filter: blur(14px) saturate(1.4);
    border-radius: 50px;
    height: 60px;
    display: flex;
    align-items: center;
    padding: 0 8px;
    gap: 0;
    position: relative;
    z-index: 9999;
    border: 1px solid rgba(255,255,255,0.12);
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
}

.ff-navbar .ff-logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    flex-shrink: 0;
    margin-left: 4px;
}

.ff-navbar .ff-logo img {
    height: 48px;
    width: auto;
    display: block;
    mix-blend-mode: lighten;
    filter: drop-shadow(0 1px 3px rgba(0,0,0,0.25));
}

.ff-navbar .ff-links {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 48px;
    list-style: none;
    position: absolute;
    left:63%;
    top:50%;
    transform: translate(-50%, -50%);
    width: auto;
    white-space: nowrap;
}

.ff-navbar .ff-links a {
    text-decoration: none;
    color: rgba(255,255,255,0.85);
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    transition: color 0.2s;
    white-space: nowrap;
    position: relative;
    padding-bottom: 3px;
}

.ff-navbar .ff-links a.active,
.ff-navbar .ff-links a:hover {
    color: #ffffff;
}
.ff-navbar .ff-links a.active::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0; right: 0;
    height: 2px;
    border-radius: 2px;
    background: #36ada3;
}

.ff-navbar .ff-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
    margin-right: 4px;   
    margin-left: auto;
}

/* ----- button styles for logout & retake ----- */
.ff-navbar .ff-btn-logout {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 20px;
    height: 38px;
    border-radius: 30px;
    border: 2px solid rgba(255,255,255,0.25);
    background: transparent;
    color: rgba(255,255,255,0.85);
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.2s, color 0.2s, border-color 0.2s;
    white-space: nowrap;
}
.ff-navbar .ff-btn-logout:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border-color: rgba(255,255,255,0.5);
}

.ff-navbar .ff-btn-retake {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 20px;
    height: 38px;
    border-radius: 30px;
    background: #36ada3;
    border: none;
    color: #ffffff;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s;
    white-space: nowrap;
}
.ff-navbar .ff-btn-retake:hover {
    background: #2d9992;
    transform: translateY(-1px);
}
.ff-navbar .ff-btn-retake:active {
    transform: scale(0.97);
}

.ff-navbar .ff-hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    background: none;
    border: none;
    padding: 6px;
    margin-left: auto;
}
.ff-navbar .ff-hamburger span {
    display: block;
    width: 24px;
    height: 2.5px;
    background: rgba(255,255,255,0.85);
    border-radius: 3px;
    transition: all 0.22s;
}

.ff-navbar .ff-hamburger.ff-open span:nth-child(1) {
    transform: translateY(7.5px) rotate(45deg);
}
.ff-navbar .ff-hamburger.ff-open span:nth-child(2) {
    opacity: 0;
}
.ff-navbar .ff-hamburger.ff-open span:nth-child(3) {
    transform: translateY(-7.5px) rotate(-45deg);
}

/* ----- mobile menu ----- */
.ff-mobile-menu {
    display: none;
    flex-direction: column;
    gap: 4px;
    width: 94%;
    max-width: 1280px;
    margin: 8px auto 0;
    background: rgba(63, 59, 120, 0.97);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.12);
    padding: 16px 20px 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    z-index: 9998;
    position: relative;
}

.ff-mobile-menu .ff-mobile-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 4px;
}

.ff-mobile-menu.ff-open {
    display: flex;
}
.ff-mobile-menu a {
    padding: 12px 16px;
    border-radius: 12px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: background 0.18s, color 0.18s;
}
.ff-mobile-menu a:hover {
    background: rgba(255,255,255,0.08);
    color: #fff;
}
.ff-mobile-menu a.active {
    color: #36ada3;
    background: rgba(54,173,163,0.1);
}
.ff-mobile-menu .ff-mobile-divider {
    height: 1px;
    background: rgba(255,255,255,0.1);
    margin: 6px 0;
}
.ff-mobile-menu .ff-btn-logout-mobile,
.ff-mobile-menu .ff-btn-retake-mobile {
    display: block;
    text-align: center;
    padding: 12px;
    border-radius: 30px;
    font-weight: 700;
    text-transform: uppercase;
    text-decoration: none;
}
.ff-mobile-menu .ff-btn-logout-mobile {
    border: 2px solid rgba(255,255,255,0.25);
    color: rgba(255,255,255,0.85);
    background: transparent;
}
.ff-mobile-menu .ff-btn-logout-mobile:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
}
.ff-mobile-menu .ff-btn-retake-mobile {
    background: #36ada3;
    color: #fff;
}
.ff-mobile-menu .ff-btn-retake-mobile:hover {
    background: #2d9992;
}

@media (max-width: 1024px) {
    .ff-navbar .ff-links {
        gap: 28px;
    }
    .ff-navbar .ff-links a {
        font-size: 14px;
    }
}

@media (max-width: 768px) {
    .ff-navbar .ff-links,
    .ff-navbar .ff-actions {
        display: none;
    }
    .ff-navbar .ff-hamburger {
        display: flex;
    }
    .ff-navbar {
        height: 56px;
        padding: 0 12px 0 18px;
    }
    .ff-navbar .ff-logo img {
        height: 40px;
    }
}

@media (max-width: 480px) {
    .ff-navbar {
        width: 92%;
        margin: 14px auto 0;
    }
    .ff-navbar .ff-logo img {
        height: 46px;
    }
}
</style>

<nav class="ff-navbar" role="navigation" aria-label="Main navigation">

    <!-- Logo -->
    <a href="index.php" class="ff-logo" aria-label="Future Finder home">
        <img src="../Images/logo.png" alt="Future Finder Logo">
    </a>

    <!-- Central links (visible on desktop) -->
    <ul class="ff-links">
        <?php if ($isLoggedIn): ?>
            <!-- For logged‑in users: Home + Dashboard -->
            <li><a href="index.php"      <?= navActive('index.php',     $currentPage) ?>>Home</a></li>
            <li><a href="dashboard.php"  <?= navActive('dashboard.php', $currentPage) ?>>Dashboard</a></li>
        <?php else: ?>
            <!-- For guests: Home + About Us + Careers -->
            <li><a href="index.php"    <?= navActive('index.php',    $currentPage) ?>>Home</a></li>
            <li><a href="about.php"    <?= navActive('about.php',    $currentPage) ?>>About Us</a></li>
            <li><a href="careers.php"  <?= navActive('careers.php',  $currentPage) ?>>Careers</a></li>
        <?php endif; ?>
    </ul>

    <!-- Right‑side actions (desktop) -->
    <div class="ff-actions">
        <?php if ($isLoggedIn): ?>
            <!-- Logged in: Logout (outline) + Retake Assessment (solid) -->
            <a href="../logout.php" class="ff-btn-logout">Logout</a>
            <a href="../User/assessment.php" class="ff-btn-retake">Retake Assessment</a>
        <?php else: ?>
            <!-- Guest: Login + Start Assessment -->
            <a href="../login.html" class="ff-btn-logout" style="border-color:#36ada3; color:#fff;">Login</a>
            <a href="../User/before_assessment.php" class="ff-btn-retake">Start Assessment</a>
        <?php endif; ?>
    </div>

    <!-- Hamburger button (mobile) -->
    <button class="ff-hamburger" id="ff-hamburger"
            aria-label="Toggle navigation" aria-expanded="false"
            aria-controls="ff-mobile-menu">
        <span></span><span></span><span></span>
    </button>

</nav>

<!-- Mobile Menu (hidden by default, toggled by hamburger) -->
<div class="ff-mobile-menu" id="ff-mobile-menu" role="navigation" aria-label="Mobile navigation">
    <?php if ($isLoggedIn): ?>
        <a href="index.php"      <?= navActive('index.php',     $currentPage) ?>>Home</a>
        <a href="dashboard.php"  <?= navActive('dashboard.php', $currentPage) ?>>Dashboard</a>
        <div class="ff-mobile-divider"></div>
        <div class="ff-mobile-actions">
            <a href="../logout.php" class="ff-btn-logout-mobile">Logout</a>
            <a href="../User/assessment.php" class="ff-btn-retake-mobile">Retake Assessment</a>
        </div>
    <?php else: ?>
        <a href="index.php"    <?= navActive('index.php',    $currentPage) ?>>Home</a>
        <a href="about.php"    <?= navActive('about.php',    $currentPage) ?>>About Us</a>
        <a href="careers.php"  <?= navActive('careers.php',  $currentPage) ?>>Careers</a>
        <div class="ff-mobile-divider"></div>
        <div class="ff-mobile-actions">
            <a href="../login.html" class="ff-btn-logout-mobile">Login</a>
            <a href="../User/before_assessment.php" class="ff-btn-retake-mobile">Start Assessment</a>
        </div>
    <?php endif; ?>
</div>

<!-- JavaScript for hamburger toggle -->
<script>
(function () {
    const hamburger  = document.getElementById('ff-hamburger');
    const mobileMenu = document.getElementById('ff-mobile-menu');
    if (!hamburger || !mobileMenu) return;

    hamburger.addEventListener('click', function (e) {
        e.stopPropagation();
        const isOpen = mobileMenu.classList.toggle('ff-open');
        hamburger.classList.toggle('ff-open', isOpen);
        hamburger.setAttribute('aria-expanded', isOpen);
    });

    mobileMenu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
            mobileMenu.classList.remove('ff-open');
            hamburger.classList.remove('ff-open');
            hamburger.setAttribute('aria-expanded', 'false');
        });
    });

    document.addEventListener('click', function (e) {
        const navbar = document.querySelector('.ff-navbar');
        if (!navbar.contains(e.target) && !mobileMenu.contains(e.target)) {
            mobileMenu.classList.remove('ff-open');
            hamburger.classList.remove('ff-open');
            hamburger.setAttribute('aria-expanded', 'false');
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            mobileMenu.classList.remove('ff-open');
            hamburger.classList.remove('ff-open');
            hamburger.setAttribute('aria-expanded', 'false');
        }
    });
})();
</script>