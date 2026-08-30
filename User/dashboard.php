<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}
if (($_SESSION['role'] ?? 'user') === 'admin') {
    header('Location: ../admin.php');
    exit;
}

require_once '../Includes/db_connection.php';

$userID    = intval($_SESSION['user_id']);
$firstName = htmlspecialchars($_SESSION['first_name'] ?? 'User');
$lastName  = htmlspecialchars($_SESSION['last_name']  ?? '');
$fullName  = trim($firstName . ' ' . $lastName);
$initials  = strtoupper(substr($firstName,0,1) . substr($lastName,0,1));

// ── Check for completed assessment ───────────────────────────
$latestStmt = mysqli_prepare($conn,
    "SELECT AssessmentID FROM Assessments
     WHERE UserID = ? AND Status = 'completed'
     ORDER BY AssessmentID DESC LIMIT 1"
);
mysqli_stmt_bind_param($latestStmt, 'i', $userID);
mysqli_stmt_execute($latestStmt);
$latest = mysqli_stmt_get_result($latestStmt)->fetch_assoc();
mysqli_stmt_close($latestStmt);

$assessed     = !empty($latest);
$assessmentID = $assessed ? intval($latest['AssessmentID']) : 0;
$recommendations = [];
$topCareerID = 0;

if ($assessed) {
    $rStmt = mysqli_prepare($conn,
        "SELECT c.Title, r.MatchScore, r.CareerID
         FROM Recommendations r
         JOIN Careers c ON c.CareerID = r.CareerID
         WHERE r.AssessmentID = ?
         ORDER BY r.MatchScore DESC LIMIT 3"
    );
    mysqli_stmt_bind_param($rStmt, 'i', $assessmentID);
    mysqli_stmt_execute($rStmt);
    $recommendations = mysqli_fetch_all(mysqli_stmt_get_result($rStmt), MYSQLI_ASSOC);
    mysqli_stmt_close($rStmt);
    
    $topCareerID = $recommendations[0]['CareerID'] ?? 0;
}

$topCareer  = $recommendations[0]['Title'] ?? null;

// ── Check if roadmap exists for the top career ──────────────
$roadmapExists = false;
if ($topCareerID > 0) {
    $roadmapStmt = mysqli_prepare($conn,
        "SELECT COUNT(*) as cnt FROM Roadmap WHERE CareerID = ?"
    );
    mysqli_stmt_bind_param($roadmapStmt, 'i', $topCareerID);
    mysqli_stmt_execute($roadmapStmt);
    $roadmapResult = mysqli_stmt_get_result($roadmapStmt);
    $roadmapRow = mysqli_fetch_assoc($roadmapResult);
    $roadmapExists = ($roadmapRow['cnt'] > 0);
    mysqli_stmt_close($roadmapStmt);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Future Finder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
    :root {
        --bg:       #0e1057;
        --sidebar:  #111466;
        --card:     #1a1f7a;
        --card-lt:  #1e2480;
        --primary:  #36ada3;
        --primary-dk:#2d9a90;
        --text:     #ffffff;
        --muted:    rgba(255,255,255,0.65);
        --border:   rgba(255,255,255,0.12);
        --radius:   14px;
        --nav-h:    90px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
    }

    .dash-layout {
        display: flex;
        min-height: calc(100vh - var(--nav-h));
        padding-top: 12px;
    }

    .dash-sidebar {
        width: 220px;
        flex-shrink: 0;
        background: var(--sidebar);
        border-radius: 0 18px 18px 0;
        padding: 28px 14px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
    }

    .sidebar-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.5);
        padding: 0 8px 12px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 8px;
    }

    .sidebar-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 16px;
        border-radius: 30px;
        border: 2px solid rgba(54,173,163,0.5);
        background: transparent;
        color: var(--text);
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s;
        cursor: pointer;
    }
    .sidebar-item:hover,
    .sidebar-item.active {
        background: rgba(54,173,163,0.18);
        border-color: var(--primary);
    }
    .sidebar-item svg {
        width: 16px; height: 16px;
        stroke: var(--primary);
        flex-shrink: 0;
    }

    .sidebar-item.profile-item {
        background: rgba(54,173,163,0.2);
        border-color: var(--primary);
        margin-top: 40px;
    }
    .sidebar-item.profile-item .s-avatar {
        width: 22px; height: 22px; border-radius: 50%;
        background: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 10px; font-weight: 800; color: #fff;
    }

    .dash-main {
        flex: 1;
        padding: 32px 36px 80px;
        overflow-y: auto;
    }

    .welcome-section { margin-bottom: 28px; }
    .welcome-section h1 {
        font-size: clamp(1.8rem, 3.5vw, 2.6rem);
        font-weight: 900;
        color: #fff;
        line-height: 1.1;
        margin-bottom: 6px;
    }
    .welcome-section h1 span { color: var(--primary); }
    .welcome-section p {
        font-size: 1rem;
        color: var(--primary);
        font-weight: 500;
    }

    .info-card {
        background: var(--card);
        border-radius: var(--radius);
        padding: 22px 28px;
        margin-bottom: 16px;
    }

    .info-card .card-label {
        font-size: 0.85rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #fff;
        margin-bottom: 6px;
    }

    .info-card .card-value {
        font-size: 1.1rem;
        font-weight: 700;
    }
    .info-card .card-value.pending   { color: var(--primary); }
    .info-card .card-value.completed { color: var(--primary); }
    .info-card .card-value.no        { color: #ef4444; }
    .info-card .card-value.soon      { color: var(--primary); }
    .info-card .card-value.yes       { color: var(--primary); }

    .info-card .card-sub {
        font-size: 0.9rem;
        font-weight: 600;
        color: rgba(255,255,255,0.75);
        margin-top: 4px;
    }
    .info-card .card-sub a {
        color: var(--primary);
        text-decoration: underline;
    }

    .status-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    .btn-teal {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 13px 32px;
        background: var(--primary);
        color: #fff;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s, transform 0.15s;
        white-space: nowrap;
    }
    .btn-teal:hover { background: var(--primary-dk); transform: translateY(-1px); }
    .btn-teal:active { transform: scale(0.97); }

    /* ── Donut chart section ── */
    .overview-card {
        background: var(--card);
        border-radius: var(--radius);
        padding: 28px;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .overview-label {
        font-size: 1rem;
        font-weight: 900;
        color: #fff;
        line-height: 1.25;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        min-width: 90px;
        flex-shrink: 0;
    }

    .donuts-row {
        display: flex;
        flex-wrap: nowrap;        
        gap: 30px;
        align-items: center;
        flex: 1;
        justify-content: center;  
    }

    .donut-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        flex: 0 0 auto;
        width: 95px;             
    }

    .donut-wrap {
        position: relative;
        width: 90px;
        height: 90px;
    }
    .donut-wrap svg {
        width: 90px;
        height: 90px;
        transform: rotate(-90deg);
    }

    .donut-track {
        fill: none;
        stroke: rgba(255,255,255,0.12);
        stroke-width: 10;
    }

    .donut-progress {
        fill: none;
        stroke: var(--primary);
        stroke-width: 10;
        stroke-linecap: round;
        transition: stroke-dashoffset 1.4s cubic-bezier(.4,0,.2,1);
    }

    .donut-text {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1rem;
        font-weight: 800;
        color: #fff;
        text-align: center;
        line-height: 1;
        pointer-events: none;
    }

    .donut-name {
        font-size: 0.7rem;
        font-weight: 700;
        color: rgba(255,255,255,0.85);
        text-align: center;
        line-height: 1.2;
        word-break: break-word;
        max-width: 100%;
    }

    .retake-row {
        display: flex;
        justify-content: center;
        margin-top: 24px;
    }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .dash-sidebar { display: none; }
        .dash-main { padding: 20px 16px 80px; }
        .two-col { grid-template-columns: 1fr; }
        .donut-item { width: 80px; }
        .donut-wrap, .donut-wrap svg { width: 80px; height: 80px; }
    }
    @media (max-width: 600px) {
        .overview-card { flex-direction: column; align-items: flex-start; }
        .donuts-row { gap: 8px; justify-content: center; width: 100%; }
        .donut-item { width: 70px; }
        .donut-wrap, .donut-wrap svg { width: 70px; height: 70px; }
        .welcome-section h1 { font-size: 1.6rem; }
    }
    </style>
</head>
<body>

<?php
    $currentPage = 'dashboard.php';
    require_once __DIR__ . '/../shared/navbaroptional.php';
?>

<div class="dash-layout">

    <aside class="dash-sidebar">
        <div class="sidebar-label">Navigation</div>

        <a href="/future_finder/index.php" class="sidebar-item">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round">
                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Home
        </a>

        <a href="dashboard.php"  class="sidebar-item active">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            </svg>
            Dashboard
        </a>

        <a href="/future_finder/User/before_assessment.php" class="sidebar-item">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            Take Assessment
        </a>

        <a href="results.php" class="sidebar-item">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            My Results
        </a>

        <a href="compare.php" class="sidebar-item">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round">
                <line x1="18" y1="20" x2="18" y2="10"/>
                <line x1="12" y1="20" x2="12" y2="4"/>
                <line x1="6"  y1="20" x2="6"  y2="14"/>
            </svg>
            Compare Careers
        </a>

        <a href="roadmap.php" class="sidebar-item">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round">
                <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/>
            </svg>
            Career Roadmap
        </a>

        <a href="cv.php" class="sidebar-item">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            CV Generator
        </a>

        <a href="profile.php" class="sidebar-item profile-item">
            <div class="s-avatar"><?= $initials ?: 'U' ?></div>
            My Profile
        </a>
    </aside>

    <main class="dash-main">

        <div class="welcome-section">
            <h1>Welcome Back, <span><?= $firstName ?></span> !</h1>
            <p>Here's your career guidance overview and progress.</p>
        </div>

        <?php if (!$assessed): ?>

        <div class="info-card" style="margin-bottom:16px;">
            <div class="status-card">
                <div>
                    <div class="card-label">Your Assessment Status</div>
                    <div class="card-value pending">PENDING</div>
                    <div class="card-sub">Start your assessment now 👉</div>
                </div>
                <a href="assessment.php" class="btn-teal">Take Assessment</a>
            </div>
        </div>

        <div class="two-col">
            <div class="info-card">
                <div class="card-label">Top Career Match</div>
                <div class="card-value" style="color:rgba(255,255,255,0.4);font-size:2rem;">-</div>
                <div class="card-sub">Complete Assessment</div>
            </div>
            <div class="info-card">
                <div class="card-label">Roadmap Available</div>
                <div class="card-value no">NO</div>
                <div class="card-sub">Complete Assessment</div>
            </div>
        </div>

        <?php else: ?>

        <div class="info-card" style="margin-bottom:16px;">
            <div class="card-label">Your Assessment Status</div>
            <div class="card-value completed">COMPLETED</div>
        </div>

        <div class="two-col">
            <div class="info-card">
                <div class="card-label">Top Career Match</div>
                <div class="card-value" style="color:var(--primary);font-size:1.05rem;">
                    <?= htmlspecialchars($topCareer ?? '—') ?>
                </div>
            </div>
            <div class="info-card">
                <div class="card-label">Roadmap Available</div>
                <?php if ($roadmapExists): ?>
                    <div class="card-value yes">YES</div>
                    <div class="card-sub"><a href="roadmap.php">View Roadmap →</a></div>
                <?php else: ?>
                    <div class="card-value no">NO</div>
                    <div class="card-sub">No roadmap yet</div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($recommendations)): ?>
        <div class="overview-card" id="overviewCard">
            <div class="overview-label">Career<br>Match<br>Overview</div>
            <div class="donuts-row" id="donutsRow">
                <?php foreach ($recommendations as $i => $rec): ?>
                <div class="donut-item">
                    <div class="donut-wrap">
                        <svg viewBox="0 0 90 90">
                            <circle class="donut-track" cx="45" cy="45" r="35"/>
                            <circle class="donut-progress"
                                    cx="45" cy="45" r="35"
                                    data-percent="<?= $rec['MatchScore'] ?>"
                                    style="stroke-dasharray: <?= 2 * M_PI * 35 ?>;
                                           stroke-dashoffset: <?= 2 * M_PI * 35 ?>;"/>
                        </svg>
                        <div class="donut-text" id="donut-text-<?= $i ?>">0%</div>
                    </div>
                    <div class="donut-name"><?= htmlspecialchars($rec['Title']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="retake-row">
            <a href="assessment.php" class="btn-teal">RE-TAKE ASSESSMENT</a>
        </div>

        <?php endif; ?>

    </main>
</div>

<?php require_once __DIR__ . '/../shared/footer.php'; ?>

<script>
(function () {
    const circles = document.querySelectorAll('.donut-progress');
    if (!circles.length) return;
    const CIRC = 2 * Math.PI * 35;

    function animateDonut(circle, textEl, targetPct) {
        const duration = 1400;
        const start = performance.now();
        function step(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = targetPct * eased;
            circle.style.strokeDashoffset = CIRC - (CIRC * current / 100);
            if (textEl) textEl.textContent = Math.round(current) + '%';
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            observer.unobserve(entry.target);
            circles.forEach((circle, i) => {
                const pct = parseFloat(circle.dataset.percent) || 0;
                const textEl = document.getElementById('donut-text-' + i);
                setTimeout(() => animateDonut(circle, textEl, pct), i * 180);
            });
        });
    }, { threshold: 0.3 });

    const card = document.getElementById('overviewCard');
    if (card) observer.observe(card);
})();
</script>

</body>
</html>