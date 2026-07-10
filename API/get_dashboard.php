<?php

session_start();
header('Content-Type: application/json');

// Session guard
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'auth' => false]);
    exit;
}

require_once '../Includes/db_connection.php';

$userID = intval($_SESSION['user_id']);

// Check if user has a completed assessment
$stmt = mysqli_prepare($conn,
    "SELECT AssessmentID FROM Assessments
     WHERE UserID = ? AND Status = 'completed'
     ORDER BY AssessmentID DESC LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$latest = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$latest) {
    // No completed assessment yet
    echo json_encode(['success' => true, 'assessed' => false, 'recommendations' => []]);
    exit;
}

$assessmentID = $latest['AssessmentID'];

// Get top 3 recommendations for that assessment with career title
$stmt2 = mysqli_prepare($conn,
    "SELECT c.Title, r.MatchScore
     FROM Recommendations r
     JOIN Careers c ON c.CareerID = r.CareerID
     WHERE r.AssessmentID = ?
     ORDER BY r.MatchScore DESC
     LIMIT 3");
mysqli_stmt_bind_param($stmt2, 'i', $assessmentID);
mysqli_stmt_execute($stmt2);
$recs = mysqli_fetch_all(mysqli_stmt_get_result($stmt2), MYSQLI_ASSOC);
mysqli_stmt_close($stmt2);

echo json_encode([
    'success'         => true,
    'assessed'        => true,
    'assessmentID'    => $assessmentID,
    'recommendations' => $recs
]);

mysqli_close($conn);
?>
