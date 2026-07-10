<?php

session_start();
header('Content-Type: application/json');

// Session guard — only registered/logged-in users can start an assessment
// $_SESSION['user_id'] is set by login.php on successful login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'error'    => true,
        'auth'     => false,
        'message'  => 'Not logged in. Please log in to take the assessment.',
        'redirect' => '../login.html'
    ]);
    exit;
}

require_once '../Includes/db_connection.php';

// Read the real logged-in user's ID from the session
// This was set in $_SESSION by login.php after password_verify() succeeded
$userID = intval($_SESSION['user_id']);
$today  = date('Y-m-d');

$sql  = "INSERT INTO Assessments (UserID, AssessmentType, Date, Status)
         VALUES (?, 'Career Assessment', ?, 'in_progress')";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(['error' => true, 'message' => 'Prepare failed: ' . mysqli_error($conn)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'is', $userID, $today);

if (mysqli_stmt_execute($stmt)) {
    $AssessmentID = mysqli_insert_id($conn);
    echo json_encode(['success' => true, 'AssessmentID' => $AssessmentID]);
} else {
    echo json_encode(['error' => true, 'message' => 'Could not start assessment: ' . mysqli_stmt_error($stmt)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
