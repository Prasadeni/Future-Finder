<?php


session_start();

// Session guard — redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

// Admin users go to their own panel
if (($_SESSION['role'] ?? 'user') === 'admin') {
    header('Location: ../admin.php');
    exit;
}

// Read user info from session for display
$firstName = htmlspecialchars($_SESSION['first_name']);
$lastName  = htmlspecialchars($_SESSION['last_name']);
$email     = htmlspecialchars($_SESSION['email']);
$fullName  = $firstName . ' ' . $lastName;

// Get initials for avatar (e.g. "CP" for Chamodi Prasadeni)
$initials  = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Future Finder</title>
    <style>
        /* ── Fallback styles matching the screenshot UI ─────── */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #F8FAFC; color: #334155; display: flex; flex-direction: column; min-height: 100vh; }

        /* Navbar */
        .navbar { background: #fff; border-bottom: 1px solid #E2E8F0; height: 64px; display: flex; align-items: center; padding: 0 28px; gap: 16px; position: fixed; top: 0; left: 0; right: 0; z-index: 100; }
        .navbar-brand { font-size: 20px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .brand-logo-img { height: 40px; width: auto; max-width: 160px; object-fit: contain; display: block; }
        .navbar-spacer { flex: 1; }
        .nav-link { padding: 7px 14px; border-radius: 6px; font-size: 14px; font-weight: 500; color: #64748B; text-decoration: none; transition: all .15s; }
        .nav-link:hover, .nav-link.active { background: #EFF6FF; color: #1D4ED8; }
        .nav-user { display: flex; align-items: center; gap: 10px; }
        .avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg,#1D4ED8,#7C3AED); color: #fff; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
        .nav-name { font-size: 13px; font-weight: 600; color: #0F172A; }
        .btn-logout { padding: 7px 14px; border-radius: 6px; font-size: 13px; font-weight: 500; color: #EF4444; background: #FEF2F2; border: 1px solid #FECACA; cursor: pointer; text-decoration: none; transition: all .15s; }
        .btn-logout:hover { background: #FEE2E2; }

        /* Layout */
        .app-layout { display: flex; padding-top: 64px; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 220px; flex-shrink: 0; position: fixed; top: 64px; left: 0; bottom: 0; background: #fff; border-right: 1px solid #E2E8F0; padding: 20px 12px; overflow-y: auto; }
        .sidebar-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94A3B8; padding: 0 10px 8px; }
        .sidebar-item { display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 6px; font-size: 14px; font-weight: 500; color: #64748B; cursor: pointer; text-decoration: none; margin-bottom: 2px; transition: all .15s; }
        .sidebar-item:hover { background: #F8FAFC; color: #334155; }
        .sidebar-item.active { background: #EFF6FF; color: #1D4ED8; font-weight: 600; }
        .sidebar-item.soon { cursor: default; opacity: .55; }
        .sidebar-item.soon:hover { background: none; color: #64748B; }

        /* Main */
        .main { flex: 1; margin-left: 220px; padding: 32px 36px; }
        .page-title { font-size: 26px; font-weight: 800; color: #0F172A; margin-bottom: 4px; }
        .page-sub { font-size: 14px; color: #64748B; margin-bottom: 28px; }

        /* Stats cards */
        .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: #fff; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; position: relative; overflow: hidden; }
        .stat-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #64748B; margin-bottom: 8px; }
        .stat-value { font-size: 22px; font-weight: 800; color: #0F172A; }
        .stat-value.pending { color: #F59E0B; }
        .stat-value.done { color: #059669; }
        .stat-sub { font-size: 12px; color: #94A3B8; margin-top: 4px; }
        .stat-icon { position: absolute; right: 16px; top: 16px; font-size: 28px; opacity: .15; }

        /* Quick action cards */
        .action-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; margin-bottom: 24px; }
        .action-card { background: #fff; border: 1px solid #E2E8F0; border-radius: 12px; padding: 22px; cursor: pointer; transition: all .2s; text-decoration: none; color: inherit; display: block; }
        .action-card:hover { border-color: #1D4ED8; box-shadow: 0 4px 16px rgba(29,78,216,.1); transform: translateY(-2px); }
        .action-card.disabled { opacity: .5; pointer-events: none; }
        .action-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 12px; }
        .action-title { font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 4px; }
        .action-sub { font-size: 12px; color: #94A3B8; margin-bottom: 10px; }
        .progress-wrap { background: #E2E8F0; border-radius: 99px; height: 6px; overflow: hidden; }
        .progress-fill { height: 100%; background: #1D4ED8; border-radius: 99px; }

        @media (max-width: 900px) { .sidebar { display: none; } .main { margin-left: 0; padding: 20px 16px; } .stats-row,.action-row { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 600px) { .stats-row,.action-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<!-- ── Navbar ─────────────────────────────────────────── -->
<nav class="navbar">
    <a class="navbar-brand" href="dashboard.php">
        <img src="../Images/Logo.jpg" alt="Future Finder logo" class="brand-logo-img">
    </a>
    <div class="navbar-spacer"></div>
    <a href="dashboard.php"  class="nav-link active">Dashboard</a>
    <a href="assessment.php" class="nav-link">Assessment</a>
    <a href="results.php"    class="nav-link">Results</a>
    <div class="nav-user">
        <!-- Avatar shows initials from session -->
        <div class="avatar"><?php echo $initials; ?></div>
        <span class="nav-name"><?php echo strtoupper($firstName); ?></span>
        <a href="../logout.php" class="btn-logout">⎋ Logout</a>
    </div>
</nav>

<div class="app-layout">

    <!-- ── Sidebar ──────────────────────────────────── -->
    <aside class="sidebar">
        <div class="sidebar-label">Navigation</div>
        <a href="dashboard.php"  class="sidebar-item active">📊 Dashboard</a>
        <a href="before_assessment.php" class="sidebar-item">🎯 Take Assessment</a>
        <a href="results.php"    class="sidebar-item">⭐ View Results</a>
        <a href="roadmap.php"    class="sidebar-item">Career Roadmap</a>
        <div class="sidebar-label" style="margin-top:16px;">Coming Soon</div>
        <span class="sidebar-item soon">💼 Explore Careers</span>
        <span class="sidebar-item soon">⚖️ Compare Careers</span>
        
        <span class="sidebar-item soon">🗺️ Career Roadmap</span>
        <span class="sidebar-item soon">📄 CV Generator</span>
        <span class="sidebar-item soon">👤 My Profile</span>
    </aside>

    <!-- ── Main content ─────────────────────────────── -->
    <main class="main">

        <h1 class="page-title">Welcome back, <?php echo $firstName; ?> 👋</h1>
        <p class="page-sub">Here's your career guidance overview and progress</p>

        <!-- Stats cards — data loaded from DB by JS below -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-label">Assessment Status</div>
                <div class="stat-value" id="statStatus">Loading...</div>
                <div class="stat-sub" id="statStatusSub"></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🎯</div>
                <div class="stat-label">Top Career Match</div>
                <div class="stat-value" id="statCareer" style="font-size:15px">—</div>
                <div class="stat-sub" id="statCareerSub">Complete assessment</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-label">Career Readiness</div>
                <div class="stat-value" id="statReadiness">0%</div>
                <div class="stat-sub">Based on your answers</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🗺️</div>
                <div class="stat-label">Roadmap Available</div>
                <div class="stat-value" id="statRoadmap">No</div>
                <div class="stat-sub" id="statRoadmapSub">Complete assessment first</div>
            </div>
        </div>

        <!-- Quick action cards -->
        <div class="action-row">
            <a href="assessment.php" class="action-card">
                <div class="action-icon" style="background:#EFF6FF">🎯</div>
                <div class="action-title" id="assessBtn">Take Assessment</div>
                <div class="action-sub">12 questions · ~5 minutes</div>
                <div class="progress-wrap">
                    <div class="progress-fill" id="assessProgress" style="width:0%"></div>
                </div>
            </a>
            <a href="results.php" class="action-card disabled" id="resultsCard">
                <div class="action-icon" style="background:#F0FDF4">⭐</div>
                <div class="action-title">View Results</div>
                <div class="action-sub" id="resultsSub">Complete assessment first</div>
            </a>
            <div class="action-card disabled" id="roadmapCard" style="cursor:default;">
                <div class="action-icon" style="background:#FFF7ED">🗺️</div>
                <div class="action-title">Career Roadmap</div>
                <div class="action-sub" id="roadmapSub">Coming soon</div>
            </div>
        </div>

        <!-- Career match progress bars — shown after assessment -->
        <div id="matchSection" style="display:none; background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:24px;">
            <h3 style="font-size:16px; font-weight:700; color:#0F172A; margin-bottom:18px;">Career Match Overview</h3>
            <div id="matchBars"></div>
        </div>

    </main>
</div>

<script>
// ── Load dashboard data from DB ──────────────────────────
// Fetches latest assessment status and recommendations for this user
document.addEventListener('DOMContentLoaded', () => {
    fetch('../API/get_dashboard.php')
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;

            const assessed = data.assessed;

            // Update stats cards
            document.getElementById('statStatus').textContent    = assessed ? 'Completed ✓' : 'Pending';
            document.getElementById('statStatus').className      = 'stat-value ' + (assessed ? 'done' : 'pending');
            document.getElementById('statStatusSub').textContent = assessed ? 'View your results below' : 'Start your assessment now';

            if (assessed && data.recommendations && data.recommendations.length > 0) {
                const top = data.recommendations[0];
                document.getElementById('statCareer').textContent    = top.Title;
                document.getElementById('statCareerSub').textContent = top.MatchScore + '% match';
                document.getElementById('statReadiness').textContent = top.MatchScore + '%';
                document.getElementById('statRoadmap').textContent   = 'Soon';
                document.getElementById('statRoadmapSub').textContent = 'Roadmap feature coming soon';

                // Update action cards
                document.getElementById('assessBtn').textContent        = 'Retake Assessment';
                document.getElementById('assessProgress').style.width   = '100%';
                document.getElementById('resultsCard').classList.remove('disabled');
                document.getElementById('resultsSub').textContent        = data.recommendations.length + ' matches found';

                // Show match bars
                const matchSection = document.getElementById('matchSection');
                const matchBars    = document.getElementById('matchBars');
                matchSection.style.display = 'block';
                const colors = ['#1D4ED8','#059669','#F59E0B','#7C3AED','#EF4444'];

                data.recommendations.forEach((rec, i) => {
                    matchBars.innerHTML += `
                        <div style="margin-bottom:14px">
                            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px">
                                <span style="font-weight:600">${rec.Title}</span>
                                <span style="font-weight:700;color:${colors[i]}">${rec.MatchScore}%</span>
                            </div>
                            <div style="background:#E2E8F0;border-radius:99px;height:8px;overflow:hidden">
                                <div style="height:100%;background:${colors[i]};width:${rec.MatchScore}%;border-radius:99px;transition:width .8s ease"></div>
                            </div>
                        </div>`;
                });
            }
        })
        .catch(err => console.log('Dashboard data load error:', err));
});
</script>

</body>
</html>
