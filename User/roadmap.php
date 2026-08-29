]<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userRole = $_SESSION['role'] ?? 'user';
if ($userRole === 'admin') {
    header('Location: ../Admin/admin.php');
    exit;
}

require_once '../Includes/db_connection.php';

$userID = intval($_SESSION['user_id']);
$firstName = htmlspecialchars($_SESSION['first_name']);

// Get all careers for the dropdown selector
$allCareers = mysqli_fetch_all(
    mysqli_query($conn, "SELECT CareerID, Title FROM Careers ORDER BY Title ASC"),
    MYSQLI_ASSOC
);

// Get user's top recommended career (default selection)
$defaultCareerID = 0;
$latestStmt = mysqli_prepare($conn,
    "SELECT r.CareerID FROM Recommendations r
     JOIN Assessments a ON r.AssessmentID = a.AssessmentID
     WHERE a.UserID = ? AND a.Status = 'completed'
     ORDER BY a.AssessmentID DESC, r.MatchScore DESC LIMIT 1"
);
mysqli_stmt_bind_param($latestStmt, 'i', $userID);
mysqli_stmt_execute($latestStmt);
$topRec = mysqli_stmt_get_result($latestStmt)->fetch_assoc();
mysqli_stmt_close($latestStmt);

if ($topRec) {
    $defaultCareerID = intval($topRec['CareerID']);
}

// If no recommendation yet, use first career
if ($defaultCareerID === 0 && !empty($allCareers)) {
    $defaultCareerID = intval($allCareers[0]['CareerID']);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Roadmap | Future Finder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ── Variables ── */
        :root {
            --bg:        #121358;
            --card:      #1a1b4b;
            --card-lt:   #1e2060;
            --primary:   #36ada3;
            --primary-dk:#2d9a90;
            --text:      #f0f4f8;
            --muted:     #a0a8c0;
            --border:    #2a2d6a;
            --radius:    14px;
            --shadow:    0 4px 24px rgba(10,11,60,0.5);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Layout ── */
        .page-wrap {
            max-width: 880px;
            margin: 32px auto;
            padding: 0 20px 80px;
        }

        /* ── Page header with Back button ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 28px;
        }
        .page-header h1 {
            font-size: 1.7rem;
            font-weight: 800;
            color: #fff;
        }
        .page-header h1 span { color: var(--primary); }
        .page-header p { font-size: 0.9rem; color: var(--muted); margin-top: 3px; }

        .header-actions {
            display: flex;
            gap: 12px;
        }
        .btn-dashboard {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(54,173,163,0.15);
            border: 1px solid rgba(54,173,163,0.3);
            border-radius: 30px;
            color: #36ada3;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-dashboard:hover { background: rgba(54,173,163,0.25); }

        /* ── Career selector row ── */
        .selector-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 28px;
        }
        .selector-row label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--muted);
            white-space: nowrap;
        }
        .selector-row select {
            background: var(--card);
            border: 1.5px solid var(--border);
            color: var(--text);
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 30px;
            cursor: pointer;
            outline: none;
            transition: border-color 0.2s;
            flex: 1;
            min-width: 200px;
        }
        .selector-row select:focus { border-color: var(--primary); }

        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: var(--primary);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
            white-space: nowrap;
            text-decoration: none;
        }
        .btn-download:hover { background: var(--primary-dk); transform: translateY(-1px); }
        .btn-download:active { transform: scale(0.97); }

        /* ── Career info card ── */
        .career-info-card {
            background: rgba(83,79,148,0.4);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            padding: 24px 28px;
            margin-bottom: 32px;
            display: flex;
            align-items: flex-start;
            gap: 20px;
            flex-wrap: wrap;
        }
        .career-icon-wrap {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: rgba(54,173,163,0.2);
            border: 1.5px solid var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }
        .career-info h2 {
            font-size: 1.3rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }
        .career-info .industry-tag {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--primary);
            background: rgba(54,173,163,0.15);
            border: 1px solid rgba(54,173,163,0.3);
            padding: 2px 10px;
            border-radius: 99px;
            margin-bottom: 8px;
        }
        .career-info p {
            font-size: 0.88rem;
            color: var(--muted);
            line-height: 1.6;
            max-width: 520px;
        }
        .total-time {
            margin-left: auto;
            text-align: center;
            background: rgba(54,173,163,0.1);
            border: 1.5px solid rgba(54,173,163,0.3);
            border-radius: 12px;
            padding: 14px 20px;
            min-width: 100px;
        }
        .total-time .t-num {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
        }
        .total-time .t-lbl {
            font-size: 0.72rem;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        /* ── Roadmap visual ── */
        #roadmap-visual {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px 28px;
            box-shadow: var(--shadow);
            position: relative;
        }

        .roadmap-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--border);
        }
        .roadmap-header h3 {
            font-size: 1.1rem;
            font-weight: 800;
            color: #fff;
        }
        .roadmap-header p {
            font-size: 0.8rem;
            color: var(--muted);
            margin-top: 2px;
        }
        .roadmap-header .ff-badge {
            margin-left: auto;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--primary);
            background: rgba(54,173,163,0.1);
            border: 1px solid rgba(54,173,163,0.3);
            padding: 4px 12px;
            border-radius: 99px;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 48px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 18px;
            top: 8px;
            bottom: 8px;
            width: 3px;
            background: linear-gradient(to bottom, #7dd3fc, #36ada3, #1e3a8a);
            border-radius: 3px;
        }

        /* Stage */
        .stage {
            position: relative;
            margin-bottom: 20px;
            animation: fadeInLeft 0.4s ease both;
        }
        .stage:last-child { margin-bottom: 0; }

        .stage-dot {
            position: absolute;
            left: -36px;
            top: 16px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary);
            border: 3px solid var(--card);
            box-shadow: 0 0 0 2px var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 800;
            color: #fff;
            z-index: 1;
        }

        .stage-card {
            background: linear-gradient(135deg, var(--card-lt), var(--card));
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: border-color 0.2s, transform 0.15s;
            cursor: default;
        }
        .stage-card:hover {
            border-color: var(--primary);
            transform: translateX(4px);
        }

        .stage-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #1a4080, #1e5590);
            border-radius: 10px;
            padding: 10px 14px;
            min-width: 100px;
            flex-shrink: 0;
        }
        .stage-pill .s-icon { font-size: 22px; }
        .stage-pill .s-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
        }
        .stage-pill .s-num {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.6);
        }

        .stage:nth-child(1) .stage-pill { background: linear-gradient(135deg, #1e90c0, #1470a0); }
        .stage:nth-child(2) .stage-pill { background: linear-gradient(135deg, #1a7eb0, #125f90); }
        .stage:nth-child(3) .stage-pill { background: linear-gradient(135deg, #1560a0, #0e4880); }
        .stage:nth-child(4) .stage-pill { background: linear-gradient(135deg, #124090, #0b3070); }
        .stage:nth-child(5) .stage-pill { background: linear-gradient(135deg, #0e2e70, #082060); }
        .stage:nth-child(6) .stage-pill { background: linear-gradient(135deg, #0a1f58, #061550); }

        .stage-content { flex: 1; }
        .stage-content .s-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
        }
        .stage-content .s-desc {
            font-size: 0.83rem;
            color: var(--muted);
            line-height: 1.55;
        }

        .stage-time {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary);
            white-space: nowrap;
            flex-shrink: 0;
        }
        .stage-time svg {
            width: 14px;
            height: 14px;
            stroke: var(--primary);
            flex-shrink: 0;
        }

        /* ── Loading + error states ── */
        .state-box {
            text-align: center;
            padding: 60px 20px;
        }
        .spinner {
            width: 44px; height: 44px;
            border: 4px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 16px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-16px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ── Action row ── */
        .action-row {
            display: flex;
            gap: 14px;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 24px;
            background: transparent;
            border: 2px solid var(--border);
            color: var(--muted);
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 30px;
            cursor: pointer;
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s;
        }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }

        /* ── RESPONSIVE ── */
        @media (max-width: 600px) {
            .page-header { flex-direction: column; align-items: flex-start; }
            .career-info-card { flex-direction: column; }
            .total-time { margin-left: 0; width: 100%; }
            .stage-pill { min-width: 80px; }
            #roadmap-visual { padding: 20px 14px; }
            .timeline { padding-left: 36px; }
            .stage-card { flex-wrap: wrap; }
        }

        /* ============================================================
           PRINT STYLES — FIXED: show parent chain, hide irrelevant elements
           ============================================================ */
        @media print {
            /* Keep the page-wrap and its children visible, but hide everything else */
            html, body {
                background: #fff !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Hide navbar, footer, and other top-level elements */
            body > *:not(.page-wrap) {
                display: none !important;
            }

            /* Make the page-wrap occupy full page with white background */
            .page-wrap {
                display: block !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                background: #ffffff !important;
            }

            /* Hide everything inside page-wrap except #roadmap-visual */
            .page-wrap > *:not(#roadmap-visual) {
                display: none !important;
            }

            /* Make the roadmap container fill the page */
            #roadmap-visual {
                display: block !important;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: #ffffff !important;
                color: #1a1a2e !important;
                padding: 40px !important;
                margin: 0 !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                overflow-y: auto;
                font-size: 11pt !important;
                line-height: 1.5;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Force all text to dark, preserve background colours */
            #roadmap-visual * {
                color: #1a1a2e !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Stage cards light background */
            #roadmap-visual .stage-card {
                background: #f5f7fa !important;
                border-color: #ccc !important;
            }

            #roadmap-visual .stage-pill {
                background: #e0e7f0 !important;
            }
            #roadmap-visual .stage-pill .s-label,
            #roadmap-visual .stage-pill .s-num {
                color: #1a1a2e !important;
            }
            #roadmap-visual .stage-content .s-title {
                color: #1a1a2e !important;
            }
            #roadmap-visual .stage-content .s-desc {
                color: #444 !important;
            }
            #roadmap-visual .stage-time {
                color: #1a1a2e !important;
            }
            #roadmap-visual .stage-time svg {
                stroke: #1a1a2e !important;
            }

            #roadmap-visual .timeline::before {
                background: #888 !important;
            }

            #roadmap-visual .stage-dot {
                background: #1a1a2e !important;
                border-color: #fff !important;
                box-shadow: 0 0 0 2px #1a1a2e !important;
                color: #fff !important;
            }

            #roadmap-visual .roadmap-header {
                border-bottom-color: #ccc !important;
            }
            #roadmap-visual .roadmap-header h3,
            #roadmap-visual .roadmap-header p {
                color: #1a1a2e !important;
            }
            #roadmap-visual .ff-badge {
                background: #e0e7f0 !important;
                border-color: #ccc !important;
                color: #1a1a2e !important;
            }

            /* Hide any extra cards that may appear inside the visual */
            #roadmap-visual .career-info-card,
            #roadmap-visual .total-time {
                display: none !important;
            }

            /* Avoid page breaks inside stages */
            .stage {
                page-break-inside: avoid;
                margin-bottom: 16px !important;
            }
            .roadmap-header {
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body>

<?php
    $currentPage = 'roadmap.php';
    require_once __DIR__ . '/../shared/navbaroptional.php';
?>

<div class="page-wrap">

    <!-- Page header with Back button -->
    <div class="page-header">
        <div>
            <h1>Career <span>Roadmap</span></h1>
            <p>Your step-by-step learning path to achieve your career goal</p>
        </div>
        <div class="header-actions">
            <a href="dashboard.php" class="btn-dashboard">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Career selector + download button -->
    <div class="selector-row">
        <label for="careerSelect">Select Career:</label>
        <select id="careerSelect" onchange="loadRoadmap(this.value)">
            <?php foreach ($allCareers as $c): ?>
            <option value="<?= $c['CareerID'] ?>"
                <?= $c['CareerID'] == $defaultCareerID ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['Title']) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <button class="btn-download" onclick="downloadPDF()">
            <i class="fa-solid fa-file-pdf"></i> Download PDF
        </button>
    </div>

    <!-- Career info banner -->
    <div class="career-info-card" id="careerInfoCard">
        <div class="career-icon-wrap" id="careerIcon">🎯</div>
        <div class="career-info">
            <div class="industry-tag" id="careerIndustry">Loading...</div>
            <h2 id="careerTitle">Loading career...</h2>
            <p id="careerDesc"></p>
        </div>
        <div class="total-time">
            <div class="t-num" id="totalMonths">—</div>
            <div class="t-lbl">Total<br>Months</div>
        </div>
    </div>

    <!-- Roadmap visual -->
    <div id="roadmap-visual">
        <div class="roadmap-header">
            <div>
                <h3 id="rm-title">Career Roadmap</h3>
                <p id="rm-sub">Future Finder — Smart Career Guidance System</p>
            </div>
            <div class="ff-badge">futurefinder.lk</div>
        </div>

        <!-- Timeline stages injected by JS -->
        <div class="timeline" id="timeline">
            <div class="state-box">
                <div class="spinner"></div>
                <p style="color:var(--muted)">Loading roadmap...</p>
            </div>
        </div>
    </div>

    <!-- Action buttons -->
    <div class="action-row">
        <a href="results.php"   class="btn-outline">My Results</a>
        <a href="before_assessment.php" class="btn-outline">Retake Assessment</a>
    </div>

</div>

<?php require_once __DIR__ . '/../shared/footer.php'; ?>

<script>
// ── Career icon map ─────────────────────────────────────────
const iconMap = {
    'Software':    '💻',
    'Web':         '🌐',
    'UI':          '🎨',
    'Network':     '📡',
    'Project':     '📋',
    'Cyber':       '🔐',
    'DevOps':      '🐳',
    'Data':        '📊',
    'Product':     '💡',
    'Marketing':   '📣',
    'Business':    '📋',
    'QA':          '🧪',
    'HR':          '👥',
    'Database':    '🗄️',
    'Frontend':    '⚛️',
};

function getCareerIcon(title) {
    for (const [key, icon] of Object.entries(iconMap)) {
        if (title.includes(key)) return icon;
    }
    return '🎯';
}

// ── Load roadmap ──────────────────────────────────────────
function loadRoadmap(careerID) {
    document.getElementById('timeline').innerHTML = `
        <div class="state-box">
            <div class="spinner"></div>
            <p style="color:var(--muted)">Loading roadmap...</p>
        </div>`;

    fetch('../API/get_roadmap.php?career_id=' + careerID)
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                document.getElementById('timeline').innerHTML =
                    `<div class="state-box"><p style="color:var(--muted)">No roadmap found for this career yet.</p></div>`;
                return;
            }
            renderRoadmap(data.career, data.stages);
        })
        .catch(err => {
            document.getElementById('timeline').innerHTML =
                `<div class="state-box"><p style="color:#f87171">Error loading roadmap. Check XAMPP is running.</p></div>`;
        });
}

// ── Render roadmap ────────────────────────────────────────
function renderRoadmap(career, stages) {
    const icon = getCareerIcon(career.Title);
    document.getElementById('careerIcon').textContent    = icon;
    document.getElementById('careerTitle').textContent   = career.Title;
    document.getElementById('careerIndustry').textContent= career.Industry || 'Technology';
    document.getElementById('careerDesc').textContent    = career.Description || '';

    document.getElementById('rm-title').textContent = career.Title + ' — Career Roadmap';

    let totalMonths = 0;
    stages.forEach(s => {
        const match = (s.EstimatedTime || '').match(/(\d+)/);
        if (match) totalMonths += parseInt(match[1]);
    });
    document.getElementById('totalMonths').textContent = totalMonths;

    const html = stages.map((stage, i) => `
        <div class="stage">
            <div class="stage-dot">${stage.StageNumber}</div>
            <div class="stage-card">
                <div class="stage-pill">
                    <div class="s-icon">${stage.Icon || '📌'}</div>
                    <div>
                        <div class="s-num">Stage ${stage.StageNumber}</div>
                        <div class="s-label">${truncate(stage.Title, 16)}</div>
                    </div>
                </div>
                <div class="stage-content">
                    <div class="s-title">${stage.Title}</div>
                    <div class="s-desc">${stage.Description}</div>
                </div>
                <div class="stage-time">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    ${stage.EstimatedTime}
                </div>
            </div>
        </div>
    `).join('');

    document.getElementById('timeline').innerHTML = html || `
        <div class="state-box">
            <p style="color:var(--muted)">No roadmap stages found for this career.</p>
        </div>`;
}

function truncate(str, max) {
    return str && str.length > max ? str.slice(0, max) + '…' : str;
}

// ── Download PDF ──────────────────────────────────────────
function downloadPDF() {
    // Ensure the roadmap is fully rendered (it should be, but just in case)
    // We can also add a small delay to let any pending animations finish.
    setTimeout(() => {
        window.print();
    }, 300);
}

// ── On page load ──────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const defaultID = <?= $defaultCareerID ?>;
    if (defaultID > 0) {
        loadRoadmap(defaultID);
    } else {
        document.getElementById('timeline').innerHTML =
            `<div class="state-box">
                <p style="color:var(--muted)">Complete your assessment first to see a recommended roadmap.</p>
                <a href="assessment.php" style="color:var(--primary);margin-top:12px;display:inline-block;">
                    → Take Assessment
                </a>
             </div>`;
    }
});
</script>

</body>
</html>