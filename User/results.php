<?php
session_start();

require_once '../Includes/db_connection.php';

// Session guard: must be logged in to view any results
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

$userID       = intval($_SESSION['user_id']);
$AssessmentID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// No specific assessment requested (e.g. "View Results" / "My Results" links) —
// automatically load this user's most recently completed assessment instead.
if ($AssessmentID === 0) {
    $latestStmt = mysqli_prepare($conn,
        "SELECT AssessmentID FROM Assessments
         WHERE UserID = ? AND Status = 'completed'
         ORDER BY AssessmentID DESC LIMIT 1");
    mysqli_stmt_bind_param($latestStmt, 'i', $userID);
    mysqli_stmt_execute($latestStmt);
    $latest = mysqli_stmt_get_result($latestStmt)->fetch_assoc();
    mysqli_stmt_close($latestStmt);

    if (!$latest) {
        // User has not completed an assessment yet — send them to take one.
        header('Location: assessment.php');
        exit;
    }
    $AssessmentID = intval($latest['AssessmentID']);
}

// load assessment
$aSQL  = "SELECT a.*, u.first_name, u.last_name FROM Assessments a
          JOIN Users u ON a.UserID = u.id
          WHERE a.AssessmentID = ?";
$aStmt = mysqli_prepare($conn, $aSQL);
mysqli_stmt_bind_param($aStmt, 'i', $AssessmentID);
mysqli_stmt_execute($aStmt);
$assessment = mysqli_stmt_get_result($aStmt)->fetch_assoc();

if (!$assessment) {
    die('Assessment not found. ID: ' . $AssessmentID);
}

// Only the owner of this assessment (or an admin) may view it
if ($userID !== (int)$assessment['UserID'] && ($_SESSION['role'] ?? 'user') !== 'admin') {
    die('You do not have permission to view this assessment.');
}

$assessmentOwnerName = trim($assessment['first_name'] . ' ' . $assessment['last_name']);


// load recommendations
$rSQL = "SELECT r.MatchScore, c.CareerID, c.Title, c.Description, c.SalaryRange, 
                c.Demand, c.Growth, c.RequiredEducation, c.Industry
         FROM Recommendations r
         JOIN Careers c ON r.CareerID = c.CareerID
         WHERE r.AssessmentID = ?
         ORDER BY r.MatchScore DESC";
$rStmt = mysqli_prepare($conn, $rSQL);
mysqli_stmt_bind_param($rStmt, 'i', $AssessmentID);
mysqli_stmt_execute($rStmt);
$rResult = mysqli_stmt_get_result($rStmt);
$recommendations = [];
while ($row = mysqli_fetch_assoc($rResult)) {
    $recommendations[] = $row;
}

// load questions and answers
$ansSQL = "SELECT q.QuestionID, q.Text, q.Category, a.SelectedOption 
           FROM Answers a 
           JOIN Questions q ON a.QuestionID = q.QuestionID 
           WHERE a.AssessmentID = ?";
$ansStmt = mysqli_prepare($conn, $ansSQL);
mysqli_stmt_bind_param($ansStmt, 'i', $AssessmentID);
mysqli_stmt_execute($ansStmt);
$ansResult = mysqli_fetch_all(mysqli_stmt_get_result($ansStmt), MYSQLI_ASSOC);


$qSQL = "SELECT QuestionID, Category, Weight, Options FROM Questions";
$qRes = mysqli_query($conn, $qSQL);
$qMap = [];
while ($row = mysqli_fetch_assoc($qRes)) {
    $qMap[$row['QuestionID']] = $row;
}

// compute weight
$optionCategoryMap = [0 => 'technical', 1 => 'analytical', 2 => 'creative', 3 => 'management'];
$categoryScores = ['technical' => 0, 'analytical' => 0, 'creative' => 0, 'management' => 0];
$totalWeight = 0; 

foreach ($ansResult as $a) {
    $qID = $a['QuestionID'];
    if (!isset($qMap[$qID])) continue;
    $q = $qMap[$qID];
    $weight = floatval($q['Weight']);
    $totalWeight += $weight;
    $opts = json_decode($q['Options'], true);
    $idx = array_search($a['SelectedOption'], $opts);
    if ($idx !== false && isset($optionCategoryMap[$idx])) {
        $cat = $optionCategoryMap[$idx];
        $categoryScores[$cat] += $weight;
    }
}

// Normalise to percentages (out of total weight)
$catPercentages = [];
foreach ($categoryScores as $cat => $score) {
    $catPercentages[$cat] = $totalWeight ? round(($score / $totalWeight) * 100, 2) : 0;
}

// load courses
$courses = [];
if (!empty($recommendations)) {
    $topCareerID = intval($recommendations[0]['CareerID']);
    $cSQL  = "SELECT Title, Provider, URL FROM Courses WHERE CareerID = ?";
    $cStmt = mysqli_prepare($conn, $cSQL);
    mysqli_stmt_bind_param($cStmt, 'i', $topCareerID);
    mysqli_stmt_execute($cStmt);
    $courses = mysqli_fetch_all(mysqli_stmt_get_result($cStmt), MYSQLI_ASSOC);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Results | Future Finder</title>
    <link rel="stylesheet" href="../CSS/results.css">
</head>
<body>

<nav>
    <a href="dashboard.php" class="nav-brand"><img src="../Images/Logo.jpg" alt="Future Finder logo"></a>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="assessment.php">Retake Assessment</a>
        <a href="../logout.php">Logout</a>
    </div>
</nav>

<div class="wrapper">

    
    <div class="hero">
        <div class="emoji">🎉</div>
        <h1>Assessment Complete, <?= htmlspecialchars($assessmentOwnerName) ?>!</h1>
        <p>Based on your answers, here are your career recommendations.</p>
        <?php if (!empty($recommendations)): ?>
            <div class="top-career">🏆 Best Match: <?= htmlspecialchars($recommendations[0]['Title']) ?></div>
        <?php endif; ?>
    </div>

    <!-- recommendations -->
    <div class="card">
        <div class="section-title">🚀 Career Recommendations</div>
        <div class="rec-grid">
            <?php foreach ($recommendations as $i => $rec): ?>
            <div class="rec-card <?= $i === 0 ? 'top' : '' ?>">
                <div class="rank-badge badge-<?= $i+1 ?>">
                    <?= $i === 0 ? '🥇 Best Match' : ($i === 1 ? '🥈 2nd Match' : '🥉 3rd Match') ?>
                </div>
                <div class="rec-title"><?= htmlspecialchars($rec['Title']) ?></div>
                <div class="rec-industry"><?= htmlspecialchars($rec['Industry']) ?></div>
                <div class="rec-desc"><?= htmlspecialchars($rec['Description']) ?></div>
                <div class="rec-meta">
                    <div class="rec-meta-row">
                        <span>💰</span>
                        <span><strong>Salary:</strong> <?= htmlspecialchars($rec['SalaryRange']) ?></span>
                    </div>
                    <div class="rec-meta-row">
                        <span>📈</span>
                        <span><strong>Demand:</strong> <?= htmlspecialchars($rec['Demand']) ?></span>
                    </div>
                    <div class="rec-meta-row">
                        <span>🎓</span>
                        <span><strong>Education:</strong> <?= htmlspecialchars($rec['RequiredEducation']) ?></span>
                    </div>
                </div>
                <div class="match-bar-wrap">
                    <div class="match-label">Match Score</div>
                    <div class="match-bar-bg">
                        <div class="match-bar-fill" style="width:0%" data-target="<?= $rec['MatchScore'] ?>%"></div>
                    </div>
                    <div class="match-score-text"><?= $rec['MatchScore'] ?>%</div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- course recommendation -->
    <?php if (!empty($courses)): ?>
    <div class="card">
        <div class="section-title">📚 Recommended Courses for <?= htmlspecialchars($recommendations[0]['Title']) ?></div>
        <div class="course-list">
            <?php foreach ($courses as $course): ?>
            <a href="<?= htmlspecialchars($course['URL']) ?>" target="_blank" class="course-item">
                <div class="course-icon">🎓</div>
                <div>
                    <div class="course-title"><?= htmlspecialchars($course['Title']) ?></div>
                    <div class="course-provider"><?= htmlspecialchars($course['Provider']) ?></div>
                </div>
                <div class="course-arrow">→</div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- action buttons -->
    <div class="action-row">
        <a href="dashboard.php" class="btn btn-primary">📊 Back to Dashboard</a>
        <a href="assessment.php" class="btn btn-outline">🔄 Retake Assessment</a>
    </div>

</div>

<!-- Progress bar animation -->
<script>
// Animate the progress bars on load
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('[data-target]').forEach(el => {
            el.style.width = el.dataset.target;
        });
    }, 200);
});
</script>

</body>
</html>