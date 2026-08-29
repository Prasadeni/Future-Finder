<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /future_finder/login.php');
    exit;
}

if (($_SESSION['role'] ?? 'user') === 'admin') {
    header('Location: /future_finder/admin.php');
    exit;
}

require_once __DIR__ . '/../Includes/db_connection.php';

$userID = intval($_SESSION['user_id']);
$firstName = htmlspecialchars($_SESSION['first_name'] ?? 'User');
$lastName = htmlspecialchars($_SESSION['last_name'] ?? '');
$fullName = trim($firstName . ' ' . $lastName);

// ── Handle AJAX requests ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];

    // ── Save comparison ──
    if ($action === 'save_comparison') {
        $career1 = intval($_POST['career1'] ?? 0);
        $career2 = intval($_POST['career2'] ?? 0);

        if ($career1 && $career2 && $career1 !== $career2) {
            $check = mysqli_prepare($conn, "SELECT ComparisonID FROM Comparisons WHERE UserID = ? AND Career1ID = ? AND Career2ID = ?");
            mysqli_stmt_bind_param($check, 'iii', $userID, $career1, $career2);
            mysqli_stmt_execute($check);
            mysqli_stmt_store_result($check);

            if (mysqli_stmt_num_rows($check) === 0) {
                $insert = mysqli_prepare($conn, "INSERT INTO Comparisons (UserID, Career1ID, Career2ID) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($insert, 'iii', $userID, $career1, $career2);
                mysqli_stmt_execute($insert);
                mysqli_stmt_close($insert);
            }
            mysqli_stmt_close($check);
            echo json_encode(['success' => true, 'message' => 'Comparison saved!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Please select two different careers.']);
        }
        exit;
    }

    // ── Load saved comparisons ──
    if ($action === 'load_saved') {
        $query = mysqli_prepare($conn,
            "SELECT c1.Title AS Career1, c1.CareerID AS Career1ID,
                    c2.Title AS Career2, c2.CareerID AS Career2ID,
                    cp.ComparisonID, cp.created_at
             FROM Comparisons cp
             JOIN Careers c1 ON cp.Career1ID = c1.CareerID
             JOIN Careers c2 ON cp.Career2ID = c2.CareerID
             WHERE cp.UserID = ?
             ORDER BY cp.created_at DESC"
        );
        mysqli_stmt_bind_param($query, 'i', $userID);
        mysqli_stmt_execute($query);
        $result = mysqli_stmt_get_result($query);
        $comparisons = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($query);

        echo json_encode(['success' => true, 'comparisons' => $comparisons]);
        exit;
    }

    // ── Delete saved comparison ──
    if ($action === 'delete_comparison') {
        $comparisonID = intval($_POST['comparisonID'] ?? 0);
        if ($comparisonID) {
            $delete = mysqli_prepare($conn, "DELETE FROM Comparisons WHERE ComparisonID = ? AND UserID = ?");
            mysqli_stmt_bind_param($delete, 'ii', $comparisonID, $userID);
            mysqli_stmt_execute($delete);
            mysqli_stmt_close($delete);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }
}

// ── Get all careers for dropdown ──
$careersQuery = mysqli_query($conn, "SELECT CareerID, Title, Industry FROM Careers ORDER BY Industry, Title");
$allCareers = mysqli_fetch_all($careersQuery, MYSQLI_ASSOC);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Careers | Future Finder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        /* ============================================================
           COMPARE CAREERS PAGE — DARK THEME
           ============================================================ */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #0d0e3a;
            color: #ffffff;
            min-height: 100vh;
        }

        /* ── Main wrapper ── */
        .compare-wrapper {
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }

        /* ── Page header ── */
        .page-header {
            margin-bottom: 40px;
            animation: fadeInUp 0.8s ease forwards;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        .page-header .header-left h1 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 900;
            margin-bottom: 4px;
        }
        .page-header .header-left h1 span {
            color: #36ada3;
        }
        .page-header .header-left p {
            color: rgba(255,255,255,0.6);
            font-size: 1rem;
        }
        .page-header .header-actions {
            display: flex;
            gap: 12px;
        }
        .page-header .header-actions .btn-dashboard {
            padding: 10px 24px;
            background: rgba(54, 173, 163, 0.15);
            border: 1px solid rgba(54, 173, 163, 0.3);
            border-radius: 30px;
            color: #36ada3;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s, transform 0.2s;
        }
        .page-header .header-actions .btn-dashboard:hover {
            background: rgba(54, 173, 163, 0.25);
            transform: translateY(-2px);
        }

        /* ── Selection area ── */
        .selection-area {
            background: rgba(26, 31, 122, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            padding: 32px 32px 28px;
            margin-bottom: 40px;
            animation: fadeInUp 0.8s ease 0.1s forwards;
            opacity: 0;
        }
        .selection-area .selection-title {
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-bottom: 16px;
        }
        .selection-row {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: center;
        }
        .selection-row .select-wrap {
            flex: 1;
            min-width: 200px;
        }
        .selection-row select {
            width: 100%;
            padding: 12px 16px;
            background: rgba(13, 14, 58, 0.6);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            appearance: none;
            cursor: pointer;
            transition: border-color 0.3s;
        }
        .selection-row select:focus {
            outline: none;
            border-color: #36ada3;
        }
        .selection-row select option {
            background: #0d0e3a;
            color: #fff;
        }
        .selection-row .btn-compare {
            padding: 12px 36px;
            background: #36ada3;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            white-space: nowrap;
        }
        .selection-row .btn-compare:hover {
            background: #2d9992;
            transform: translateY(-2px);
        }
        .selection-row .btn-compare:active {
            transform: scale(0.97);
        }
        .selection-row .btn-compare:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* ── Comparison result ── */
        .comparison-result {
            background: rgba(26, 31, 122, 0.3);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 40px;
            animation: fadeInUp 0.8s ease 0.2s forwards;
            opacity: 0;
            display: none;
        }
        .comparison-result.visible {
            display: block;
        }
        .comparison-result .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }
        .comparison-result .result-header h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }
        .comparison-result .result-header .result-actions {
            display: flex;
            gap: 12px;
        }
        .comparison-result .result-header .result-actions button {
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }
        .comparison-result .result-header .result-actions .btn-save {
            background: rgba(54, 173, 163, 0.2);
            color: #36ada3;
            border: 1px solid rgba(54, 173, 163, 0.3);
        }
        .comparison-result .result-header .result-actions .btn-save:hover {
            background: rgba(54, 173, 163, 0.3);
            transform: translateY(-2px);
        }
        .comparison-result .result-header .result-actions .btn-save.saved {
            background: #36ada3;
            color: #fff;
            border-color: #36ada3;
        }

        /* ── Comparison table ── */
        .compare-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .compare-table th,
        .compare-table td {
            padding: 14px 18px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .compare-table th {
            font-weight: 700;
            color: rgba(255,255,255,0.4);
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .compare-table td {
            color: rgba(255,255,255,0.8);
        }
        .compare-table .career-name {
            font-weight: 700;
            color: #36ada3;
            font-size: 1.1rem;
        }
        .compare-table .career-industry {
            font-size: 12px;
            color: rgba(255,255,255,0.4);
        }
        .compare-table .career-desc {
            line-height: 1.6;
            max-width: 300px;
        }
        .compare-table .tag {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .compare-table .tag-high {
            background: rgba(54, 173, 163, 0.2);
            color: #36ada3;
        }
        .compare-table .tag-medium {
            background: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }
        .compare-table .tag-low {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }
        .compare-table .tag-very-high {
            background: rgba(54, 173, 163, 0.3);
            color: #36ada3;
        }

        /* ── Saved comparisons section ── */
        .saved-section {
            animation: fadeInUp 0.8s ease 0.3s forwards;
            opacity: 0;
        }
        .saved-section .saved-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 20px;
        }
        .saved-section .saved-header h2 {
            font-size: 1.2rem;
            font-weight: 700;
        }
        .saved-section .saved-header h2 span {
            color: #36ada3;
        }
        .saved-section .saved-header .badge-count {
            background: rgba(54, 173, 163, 0.15);
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            color: #36ada3;
            font-weight: 600;
        }
        .saved-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
        }
        .saved-card {
            background: rgba(26, 31, 122, 0.3);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 14px;
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s, border-color 0.3s;
        }
        .saved-card:hover {
            transform: translateY(-3px);
            border-color: rgba(54, 173, 163, 0.2);
        }
        .saved-card .saved-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .saved-card .saved-info .saved-careers {
            font-weight: 600;
        }
        .saved-card .saved-info .saved-careers span {
            color: #36ada3;
        }
        .saved-card .saved-info .saved-date {
            font-size: 11px;
            color: rgba(255,255,255,0.3);
        }
        .saved-card .saved-actions {
            display: flex;
            gap: 8px;
        }
        .saved-card .saved-actions button {
            background: none;
            border: none;
            color: rgba(255,255,255,0.3);
            font-size: 16px;
            cursor: pointer;
            transition: color 0.2s;
            padding: 4px 8px;
        }
        .saved-card .saved-actions .btn-view:hover {
            color: #36ada3;
        }
        .saved-card .saved-actions .btn-delete:hover {
            color: #ef4444;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: rgba(255,255,255,0.4);
        }
        .empty-state i {
            font-size: 48px;
            color: rgba(54, 173, 163, 0.2);
            margin-bottom: 12px;
            display: block;
        }
        .empty-state p {
            font-size: 14px;
        }

        /* ── Animations ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .selection-row {
                flex-direction: column;
            }
            .selection-row .select-wrap {
                width: 100%;
                min-width: unset;
            }
            .selection-row .btn-compare {
                width: 100%;
                justify-content: center;
            }
            .compare-table {
                font-size: 12px;
            }
            .compare-table th,
            .compare-table td {
                padding: 10px 12px;
            }
            .comparison-result .result-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .saved-grid {
                grid-template-columns: 1fr;
            }
            .comparison-result {
                padding: 16px;
                overflow-x: auto;
            }
        }

        @media (max-width: 480px) {
            .compare-wrapper {
                padding: 20px 12px 60px;
            }
            .selection-area {
                padding: 20px;
            }
        }

        /* ── Loading spinner ── */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.1);
            border-top-color: #36ada3;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            vertical-align: middle;
            margin-right: 8px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <!-- ── Navbar ── -->
    <?php
        $currentPage = 'compare.php';
        require_once __DIR__ . '/../shared/navbaroptional.php';
    ?>

    <!-- ── Main Content ── -->
    <div class="compare-wrapper">

        <!-- Page Header -->
        <div class="page-header">
            <div class="header-left">
                <h1>Compare <span>Careers</span></h1>
                <p>Select two careers to compare side-by-side and make informed decisions.</p>
            </div>
            <div class="header-actions">
                <a href="dashboard.php" class="btn-dashboard">
                    <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Selection Area -->
        <div class="selection-area">
            <div class="selection-title"><i class="fas fa-arrows-left-right" style="margin-right:8px;"></i> Select Careers to Compare</div>
            <div class="selection-row">
                <div class="select-wrap">
                    <select id="career1">
                        <option value="">— Select Career 1 —</option>
                        <?php foreach ($allCareers as $career): ?>
                            <option value="<?= $career['CareerID'] ?>">
                                <?= htmlspecialchars($career['Title']) ?> (<?= htmlspecialchars($career['Industry']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="select-wrap">
                    <select id="career2">
                        <option value="">— Select Career 2 —</option>
                        <?php foreach ($allCareers as $career): ?>
                            <option value="<?= $career['CareerID'] ?>">
                                <?= htmlspecialchars($career['Title']) ?> (<?= htmlspecialchars($career['Industry']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button class="btn-compare" id="btnCompare" onclick="compareCareers()">
                    <i class="fas fa-arrows-left-right" style="margin-right:8px;"></i> Compare
                </button>
            </div>
        </div>

        <!-- Comparison Result -->
        <div class="comparison-result" id="comparisonResult">
            <div class="result-header">
                <h2><i class="fas fa-chart-simple" style="color:#36ada3;margin-right:10px;"></i> Comparison Results</h2>
                <div class="result-actions">
                    <button class="btn-save" id="btnSave" onclick="saveComparison()">
                        <i class="fas fa-bookmark"></i> Save
                    </button>
                    <!-- Print button removed per request -->
                </div>
            </div>
            <div id="comparisonContent">
                <p style="color:rgba(255,255,255,0.4);text-align:center;padding:40px 0;">
                    <i class="fas fa-arrows-left-right" style="font-size:32px;display:block;margin-bottom:12px;color:rgba(54,173,163,0.2);"></i>
                    Select two careers and click <strong style="color:#36ada3;">Compare</strong> to see results.
                </p>
            </div>
        </div>

        <!-- Saved Comparisons -->
        <div class="saved-section">
            <div class="saved-header">
                <h2><i class="fas fa-clock-rotate-left" style="color:#36ada3;margin-right:10px;"></i> Your <span>Saved Comparisons</span></h2>
                <span class="badge-count" id="savedCount">0 saved</span>
            </div>
            <div id="savedContainer">
                <div class="empty-state">
                    <i class="fas fa-bookmark"></i>
                    <p>No saved comparisons yet. Compare two careers and save them here!</p>
                </div>
            </div>
        </div>

    </div>

    <!-- ── Footer ── -->
    <?php require_once __DIR__ . '/../shared/footer.php'; ?>

    <!-- ── JavaScript ── -->
    <script>
        // ============================================================
        // COMPARE CAREERS — JavaScript
        // ============================================================

        let currentComparison = null;
        let isSaved = false;

        // ── Compare careers ──
        function compareCareers() {
            const career1 = document.getElementById('career1').value;
            const career2 = document.getElementById('career2').value;

            if (!career1 || !career2) {
                alert('Please select two careers to compare.');
                return;
            }

            if (career1 === career2) {
                alert('Please select two different careers.');
                return;
            }

            const btn = document.getElementById('btnCompare');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Loading...';

            fetch('/future_finder/API/get_career_details.php?ids=' + career1 + ',' + career2)
                .then(r => r.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-arrows-left-right" style="margin-right:8px;"></i> Compare';

                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    currentComparison = data;
                    isSaved = false;
                    renderComparison(data);
                    document.getElementById('btnSave').classList.remove('saved');
                    document.getElementById('btnSave').innerHTML = '<i class="fas fa-bookmark"></i> Save';
                    document.getElementById('comparisonResult').classList.add('visible');
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-arrows-left-right" style="margin-right:8px;"></i> Compare';
                    alert('Error loading career data. Please try again.');
                    console.error(err);
                });
        }

        // ── Render comparison table ──
        function renderComparison(data) {
            const careers = data.careers;
            if (careers.length !== 2) {
                document.getElementById('comparisonContent').innerHTML = '<p style="color:rgba(255,255,255,0.4);">Error loading comparison data.</p>';
                return;
            }

            const c1 = careers[0];
            const c2 = careers[1];

            function demandTag(demand) {
                const map = {
                    'Very High': 'tag-very-high',
                    'High': 'tag-high',
                    'Medium': 'tag-medium',
                    'Low': 'tag-low'
                };
                return 'tag ' + (map[demand] || 'tag-medium');
            }

            const html = `
                <table class="compare-table">
                    <thead>
                        <tr>
                            <th style="width:20%;">Criteria</th>
                            <th style="width:40%;">
                                <div class="career-name">${c1.Title}</div>
                                <div class="career-industry">${c1.Industry}</div>
                            </th>
                            <th style="width:40%;">
                                <div class="career-name">${c2.Title}</div>
                                <div class="career-industry">${c2.Industry}</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Description</strong></td>
                            <td class="career-desc">${c1.Description}</td>
                            <td class="career-desc">${c2.Description}</td>
                        </tr>
                        <tr>
                            <td><strong>Salary Range</strong></td>
                            <td>${c1.SalaryRange}</td>
                            <td>${c2.SalaryRange}</td>
                        </tr>
                        <tr>
                            <td><strong>Demand</strong></td>
                            <td><span class="${demandTag(c1.Demand)}">${c1.Demand}</span></td>
                            <td><span class="${demandTag(c2.Demand)}">${c2.Demand}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Growth</strong></td>
                            <td><span class="${demandTag(c1.Growth)}">${c1.Growth}</span></td>
                            <td><span class="${demandTag(c2.Growth)}">${c2.Growth}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Required Education</strong></td>
                            <td>${c1.RequiredEducation}</td>
                            <td>${c2.RequiredEducation}</td>
                        </tr>
                        <tr>
                            <td><strong>Industry</strong></td>
                            <td>${c1.Industry}</td>
                            <td>${c2.Industry}</td>
                        </tr>
                    </tbody>
                </table>
            `;

            document.getElementById('comparisonContent').innerHTML = html;
        }

        // ── Save comparison ──
        function saveComparison() {
            if (!currentComparison || currentComparison.careers.length !== 2) {
                alert('Please compare two careers first.');
                return;
            }

            const c1 = currentComparison.careers[0];
            const c2 = currentComparison.careers[1];
            const btn = document.getElementById('btnSave');

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Saving...';

            const fd = new FormData();
            fd.append('action', 'save_comparison');
            fd.append('career1', c1.CareerID);
            fd.append('career2', c2.CareerID);

            fetch('/future_finder/User/compare.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                if (data.success) {
                    isSaved = true;
                    btn.classList.add('saved');
                    btn.innerHTML = '<i class="fas fa-check"></i> Saved';
                    loadSavedComparisons();
                } else {
                    alert(data.message || 'Failed to save comparison.');
                    btn.innerHTML = '<i class="fas fa-bookmark"></i> Save';
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-bookmark"></i> Save';
                alert('Error saving comparison.');
                console.error(err);
            });
        }

        // ── Load saved comparisons ──
        function loadSavedComparisons() {
            const fd = new FormData();
            fd.append('action', 'load_saved');

            fetch('/future_finder/User/compare.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    renderSavedComparisons(data.comparisons);
                }
            })
            .catch(err => console.error('Error loading saved comparisons:', err));
        }

        // ── Render saved comparisons ──
        function renderSavedComparisons(comparisons) {
            const container = document.getElementById('savedContainer');
            const countBadge = document.getElementById('savedCount');

            if (!comparisons || comparisons.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-bookmark"></i>
                        <p>No saved comparisons yet. Compare two careers and save them here!</p>
                    </div>
                `;
                countBadge.textContent = '0 saved';
                return;
            }

            countBadge.textContent = comparisons.length + ' saved';

            let html = '<div class="saved-grid">';
            comparisons.forEach(item => {
                const date = new Date(item.created_at);
                const formattedDate = date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });

                html += `
                    <div class="saved-card">
                        <div class="saved-info">
                            <div class="saved-careers">
                                <span>${item.Career1}</span>
                                <span style="color:rgba(255,255,255,0.2);margin:0 6px;">vs</span>
                                <span>${item.Career2}</span>
                            </div>
                            <div class="saved-date"><i class="far fa-calendar" style="margin-right:4px;"></i> ${formattedDate}</div>
                        </div>
                        <div class="saved-actions">
                            <button class="btn-view" onclick="loadSavedComparison(${item.Career1ID}, ${item.Career2ID})" title="View comparison">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-delete" onclick="deleteComparison(${item.ComparisonID})" title="Delete">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            container.innerHTML = html;
        }

        // ── Load a saved comparison ──
        function loadSavedComparison(id1, id2) {
            document.getElementById('career1').value = id1;
            document.getElementById('career2').value = id2;
            compareCareers();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ── Delete saved comparison ──
        function deleteComparison(comparisonID) {
            if (!confirm('Delete this saved comparison?')) return;

            const fd = new FormData();
            fd.append('action', 'delete_comparison');
            fd.append('comparisonID', comparisonID);

            fetch('/future_finder/User/compare.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadSavedComparisons();
                }
            })
            .catch(err => console.error('Error deleting comparison:', err));
        }

        // ── Enter key to compare ──
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('career1').addEventListener('keydown', (e) => {
                if (e.key === 'Enter') compareCareers();
            });
            document.getElementById('career2').addEventListener('keydown', (e) => {
                if (e.key === 'Enter') compareCareers();
            });

            // Load saved comparisons on page load
            loadSavedComparisons();
        });
    </script>

</body>
</html>