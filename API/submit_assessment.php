<?php
header('Content-Type: application/json');
require_once '../Includes/db_connection.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['answers']) || !isset($input['AssessmentID'])) {
    echo json_encode(['error' => true, 'message' => 'Invalid data received.']);
    exit;
}

$AssessmentID = intval($input['AssessmentID']);
$answers      = $input['answers']; // array of {QuestionID, SelectedOption}

// Verify assessment exists
$check = mysqli_query($conn, "SELECT AssessmentID FROM Assessments WHERE AssessmentID = $AssessmentID");
if (mysqli_num_rows($check) === 0) {
    echo json_encode(['error' => true, 'message' => 'Assessment not found.']);
    exit;
}

// Load all questions (QuestionID, Weight, Options JSON)
$qResult = mysqli_query($conn, "SELECT QuestionID, Weight, Options FROM Questions");
if (!$qResult) {
    echo json_encode(['error' => true, 'message' => 'Could not load questions.']);
    exit;
}
$qMap = [];
while ($row = mysqli_fetch_assoc($qResult)) {
    $row['Options'] = json_decode($row['Options'], true); // decode to array
    $qMap[$row['QuestionID']] = $row;
}

// 1) Accumulate career scores
$careerScores = [];

// Prepare statement for inserting answers
$stmtInsert = mysqli_prepare($conn, "INSERT INTO Answers (AssessmentID, QuestionID, SelectedOption) VALUES (?, ?, ?)");
if (!$stmtInsert) {
    echo json_encode(['error' => true, 'message' => 'Prepare failed: ' . mysqli_error($conn)]);
    exit;
}

foreach ($answers as $ans) {
    $qID    = intval($ans['QuestionID']);
    $option = trim($ans['SelectedOption']);

    // Save answer to Answers table
    mysqli_stmt_bind_param($stmtInsert, 'iis', $AssessmentID, $qID, $option);
    if (!mysqli_stmt_execute($stmtInsert)) {
        echo json_encode(['error' => true, 'message' => 'Failed to save answer.']);
        exit;
    }

    // If we have question data for this QID, process scoring
    if (!isset($qMap[$qID])) continue;
    $q = $qMap[$qID];
    $weight = floatval($q['Weight']);
    $opts = $q['Options'];

    // Find the selected option (by label)
    $chosen = null;
    foreach ($opts as $opt) {
        if ($opt['label'] === $option) {
            $chosen = $opt;
            break;
        }
    }
    if (!$chosen) continue; // should not happen

    // Add weighted points to each career
    foreach ($chosen['scores'] as $careerID => $points) {
        $careerID = intval($careerID); // ensure integer
        if (!isset($careerScores[$careerID])) {
            $careerScores[$careerID] = 0;
        }
        $careerScores[$careerID] += ($points * $weight);
    }
}
mysqli_stmt_close($stmtInsert);

// 2) Normalise scores to percentages (0-100) based on max score
$maxScore = max($careerScores) ?: 1;
foreach ($careerScores as $cID => &$score) {
    $score = round(($score / $maxScore) * 100, 2);
}
unset($score);

// Sort descending
arsort($careerScores);

// 3) Save top 3 recommendations
$today = date('Y-m-d');
$stmtRec = mysqli_prepare($conn, "INSERT INTO Recommendations (AssessmentID, CareerID, MatchScore, Date) VALUES (?, ?, ?, ?)");
if (!$stmtRec) {
    echo json_encode(['error' => true, 'message' => 'Prepare recommendations failed.']);
    exit;
}

$count = 0;
foreach ($careerScores as $CareerID => $MatchScore) {
    if ($count >= 3) break;
    mysqli_stmt_bind_param($stmtRec, 'iids', $AssessmentID, $CareerID, $MatchScore, $today);
    mysqli_stmt_execute($stmtRec);
    $count++;
}
mysqli_stmt_close($stmtRec);

// 4) Mark assessment as completed
$completedAt = date('Y-m-d H:i:s');
$totalScore = 0.00; // not used, but keep column happy
$stmtUpd = mysqli_prepare($conn, "UPDATE Assessments SET Status='completed', TotalScore=?, CompletedDate=? WHERE AssessmentID=?");
if ($stmtUpd) {
    mysqli_stmt_bind_param($stmtUpd, 'dsi', $totalScore, $completedAt, $AssessmentID);
    mysqli_stmt_execute($stmtUpd);
    mysqli_stmt_close($stmtUpd);
}

// 5) Return success
echo json_encode([
    'success' => true,
    'AssessmentID' => $AssessmentID,
    'recommendations' => $careerScores // optional debugging
]);

mysqli_close($conn);
?>