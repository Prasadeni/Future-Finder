<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../Includes/db_connection.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['answers']) || !isset($input['AssessmentID'])) {
    echo json_encode(['error' => true, 'message' => 'Invalid data received.']);
    exit;
}

$AssessmentID = intval($input['AssessmentID']);
$answers      = $input['answers'];

// Verify assessment exists
$check = mysqli_query($conn, "SELECT AssessmentID FROM Assessments WHERE AssessmentID = $AssessmentID");
if (mysqli_num_rows($check) === 0) {
    echo json_encode(['error' => true, 'message' => 'Assessment not found.']);
    exit;
}

// Load all questions
$qResult = mysqli_query($conn, "SELECT QuestionID, Weight, Options FROM Questions");
if (!$qResult) {
    echo json_encode(['error' => true, 'message' => 'Could not load questions.']);
    exit;
}
$qMap = [];
while ($row = mysqli_fetch_assoc($qResult)) {
    $row['Options'] = json_decode($row['Options'], true);
    $qMap[$row['QuestionID']] = $row;
}

// Prepare statement for inserting answers
$stmtInsert = mysqli_prepare($conn, "INSERT INTO Answers (AssessmentID, QuestionID, SelectedOption) VALUES (?, ?, ?)");
if (!$stmtInsert) {
    echo json_encode(['error' => true, 'message' => 'Prepare failed: ' . mysqli_error($conn)]);
    exit;
}

// ─────────────────────────────────────────────────────────
// 1) Accumulate career scores (only careers 1-15)
// ─────────────────────────────────────────────────────────
$careerScores = [];

foreach ($answers as $ans) {
    $qID    = intval($ans['QuestionID']);
    $option = trim($ans['SelectedOption']);

    // Save answer
    mysqli_stmt_bind_param($stmtInsert, 'iis', $AssessmentID, $qID, $option);
    if (!mysqli_stmt_execute($stmtInsert)) {
        echo json_encode(['error' => true, 'message' => 'Failed to save answer.']);
        exit;
    }

    if (!isset($qMap[$qID])) continue;
    $q = $qMap[$qID];
    $weight = floatval($q['Weight']);
    $opts = $q['Options'];

    // Find the chosen option
    $chosen = null;
    foreach ($opts as $opt) {
        if ($opt['label'] === $option) {
            $chosen = $opt;
            break;
        }
    }
    if (!$chosen) continue;

    // Add weighted points (only careers 1-15)
    foreach ($chosen['scores'] as $careerID => $points) {
        $careerID = intval($careerID);
        if ($careerID > 15) continue;

        if (!isset($careerScores[$careerID])) {
            $careerScores[$careerID] = 0;
        }
        $careerScores[$careerID] += ($points * $weight);
    }
}
mysqli_stmt_close($stmtInsert);

// ─────────────────────────────────────────────────────────
// 2) Normalise scores to percentages (0-100)
// ─────────────────────────────────────────────────────────
if (empty($careerScores)) {
    echo json_encode(['error' => true, 'message' => 'No career scores could be calculated.']);
    exit;
}

$maxScore = max($careerScores) ?: 1;
foreach ($careerScores as $cID => &$score) {
    $score = round(($score / $maxScore) * 100, 2);
}
unset($score);

arsort($careerScores);

// ─────────────────────────────────────────────────────────
// 3) Load valid CareerIDs (safety net — filters out missing IDs like 9)
// ─────────────────────────────────────────────────────────
$validCareers = [];
$validRes = mysqli_query($conn, "SELECT CareerID FROM Careers WHERE CareerID <= 15");
while ($r = mysqli_fetch_assoc($validRes)) {
    $validCareers[intval($r['CareerID'])] = true;
}

// ─────────────────────────────────────────────────────────
// 4) DELETE old recommendations for this assessment (prevent duplicates)
// ─────────────────────────────────────────────────────────
$delRec = mysqli_prepare($conn, "DELETE FROM Recommendations WHERE AssessmentID = ?");
mysqli_stmt_bind_param($delRec, 'i', $AssessmentID);
mysqli_stmt_execute($delRec);
mysqli_stmt_close($delRec);

// ─────────────────────────────────────────────────────────
// 5) Save top 3 recommendations (only valid + unique career IDs)
// ─────────────────────────────────────────────────────────
$today = date('Y-m-d');
$stmtRec = mysqli_prepare($conn, "INSERT INTO Recommendations (AssessmentID, CareerID, MatchScore, Date) VALUES (?, ?, ?, ?)");
if (!$stmtRec) {
    echo json_encode(['error' => true, 'message' => 'Prepare recommendations failed.']);
    exit;
}

$count = 0;
$savedCareers = [];

foreach ($careerScores as $CareerID => $MatchScore) {
    if ($count >= 3) break;

    // Skip career IDs that don't exist in Careers table
    if (!isset($validCareers[$CareerID])) continue;

    // Skip if already saved (double-safety against duplicates)
    if (isset($savedCareers[$CareerID])) continue;

    mysqli_stmt_bind_param($stmtRec, 'iids', $AssessmentID, $CareerID, $MatchScore, $today);
    mysqli_stmt_execute($stmtRec);
    $savedCareers[$CareerID] = true;
    $count++;
}
mysqli_stmt_close($stmtRec);

// ─────────────────────────────────────────────────────────
// 6) Mark assessment as completed
// ─────────────────────────────────────────────────────────
$completedAt = date('Y-m-d H:i:s');
$totalScore = 0.00;
$stmtUpd = mysqli_prepare($conn, "UPDATE Assessments SET Status='completed', TotalScore=?, CompletedDate=? WHERE AssessmentID=?");
if ($stmtUpd) {
    mysqli_stmt_bind_param($stmtUpd, 'dsi', $totalScore, $completedAt, $AssessmentID);
    mysqli_stmt_execute($stmtUpd);
    mysqli_stmt_close($stmtUpd);
}

// ─────────────────────────────────────────────────────────
// 7) Return success
// ─────────────────────────────────────────────────────────
echo json_encode([
    'success' => true,
    'AssessmentID' => $AssessmentID,
    'recommendations' => $careerScores
]);

mysqli_close($conn);
?>