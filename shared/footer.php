<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<style>

.ff-footer {
    background: #12153d;
    border-top: 1px solid rgba(255,255,255,0.08);
    font-family: 'Poppins', sans-serif;
    color: rgba(255,255,255,0.65);
    margin-top: 80px;
}
.ff-footer-inner {
    max-width: 1280px;
    width: 94%;
    margin: 0 auto;
    padding: 56px 0 32px;
}
.ff-footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr auto;
    gap: 64px;
    margin-bottom: 44px;
}
.ff-footer-brand img {
    height: 52px;
    width: auto;
    display: block;
    mix-blend-mode: lighten;
    margin-bottom: 14px;
    filter: drop-shadow(0 1px 4px rgba(0,0,0,0.3));
}
.ff-footer-brand h3 {
    font-size: 18px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 4px;
}
.ff-footer-brand .ff-tagline {
    font-size: 12px;
    color: #36ada3;
    font-weight: 600;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    margin-bottom: 14px;
}
.ff-footer-brand p {
    font-size: 13.5px;
    line-height: 1.7;
    color: rgba(255,255,255,0.55);
    max-width: 260px;
}
.ff-footer-col h4 {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #ffffff;
    margin-bottom: 18px;
}
.ff-footer-col a {
    display: block;
    font-size: 13.5px;
    color: rgba(255,255,255,0.55);
    text-decoration: none;
    margin-bottom: 11px;
    transition: color 0.18s, padding-left 0.18s;
}
.ff-footer-col a:hover {
    color: #36ada3;
    padding-left: 4px;
}
.ff-footer-divider {
    border: none;
    border-top: 1px solid rgba(255,255,255,0.08);
    margin-bottom: 24px;
}
.ff-footer-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
}
.ff-footer-bottom p {
    font-size: 12.5px;
    color: rgba(255,255,255,0.35);
}
.ff-footer-bottom .ff-footer-right {
    display: flex;
    align-items: center;
    gap: 20px;
}
.ff-footer-bottom .ff-footer-right a {
    font-size: 12px;
    color: rgba(255,255,255,0.35);
    text-decoration: none;
    transition: color 0.18s;
}
.ff-footer-bottom .ff-footer-right a:hover {
    color: #36ada3;
}
@media (max-width: 900px) {
    .ff-footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }
    .ff-footer-brand {
        grid-column: 1 / -1;
    }
    .ff-footer-brand p {
        max-width: 100%;
    }
}
@media (max-width: 560px) {
    .ff-footer-grid {
        grid-template-columns: 1fr;
        gap: 28px;
    }
    .ff-footer-brand {
        grid-column: unset;
    }
    .ff-footer-bottom {
        flex-direction: column;
        align-items: flex-start;
    }
    .ff-footer-inner {
        padding: 40px 0 24px;
    }
}
</style>

<footer class="ff-footer" role="contentinfo">
    <div class="ff-footer-inner">

        <div class="ff-footer-grid">

            <!-- Brand -->
            <div class="ff-footer-brand">
                <img src="/future_finder/Images/logo.png" alt="Future Finder Logo">
                <h3>Future Finder</h3>
                <div class="ff-tagline">Smart Career Guidance System</div>
                <p>Helping university students discover their ideal career paths through intelligent assessments, personalised roadmaps, and curated learning resources.</p>
            </div>

            <!-- Quick Links (always visible) -->
            <div class="ff-footer-col">
                <h4>Quick Links</h4>
                <a href="/future_finder/index.php">Home</a>
                <a href="/future_finder/about.php">About Us</a>
                <a href="/future_finder/careers.php">Explore Careers</a>
                <a href="/future_finder/login.php">Login / Register</a>
            </div>

            <!-- Features (conditional for logged‑in users) -->
            <div class="ff-footer-col">
                <h4>Features</h4>

                <?php if ($isLoggedIn): ?>
                    <!-- Logged in: point to actual pages -->
                    <a href="/future_finder/User/before_assessment.php">Career Assessment</a>
                    <a href="/future_finder/User/results.php">Recommendations</a>
                    <a href="/future_finder/User/roadmap.php">Career Roadmap</a>
                    <a href="/future_finder/User/cv.php">CV Generator</a>
                <?php else: ?>
                    <!-- Guest: point to the restricted page -->
                    <a href="/future_finder/restricted.php">Career Assessment</a>
                    <a href="/future_finder/restricted.php">Recommendations</a>
                    <a href="/future_finder/restricted.php">Career Roadmap</a>
                    <a href="/future_finder/restricted.php">CV Generator</a>
                <?php endif; ?>

            </div>

        </div>

        <hr class="ff-footer-divider">

        <div class="ff-footer-bottom">
            <p>© 2026 Future Finder. All rights reserved.</p>
            <div class="ff-footer-right">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Use</a>
            </div>
        </div>

    </div>
</footer>