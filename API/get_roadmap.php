<?php
// ============================================================
// get_roadmap.php — Returns roadmap stages for a given career
// Method: GET ?career_id=X
// Returns: { success, career, stages[] }
// Session required
// ============================================================

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'auth' => false]);
    exit;
}

require_once '../Includes/db_connection.php';

$careerID = isset($_GET['career_id']) ? intval($_GET['career_id']) : 0;

if ($careerID === 0) {
    echo json_encode(['success' => false, 'message' => 'No career ID provided']);
    exit;
}

// Get career details
$cStmt = mysqli_prepare($conn, "SELECT CareerID, Title, Industry, Description FROM Careers WHERE CareerID = ?");
mysqli_stmt_bind_param($cStmt, 'i', $careerID);
mysqli_stmt_execute($cStmt);
$career = mysqli_stmt_get_result($cStmt)->fetch_assoc();
mysqli_stmt_close($cStmt);

if (!$career) {
    echo json_encode(['success' => false, 'message' => 'Career not found']);
    exit;
}

// Get roadmap stages ordered by stage number
$rStmt = mysqli_prepare($conn,
    "SELECT RoadmapID, StageNumber, Title, Description, EstimatedTime, Icon
     FROM Roadmap
     WHERE CareerID = ?
     ORDER BY StageNumber ASC"
);
mysqli_stmt_bind_param($rStmt, 'i', $careerID);
mysqli_stmt_execute($rStmt);
$stages = mysqli_fetch_all(mysqli_stmt_get_result($rStmt), MYSQLI_ASSOC);
mysqli_stmt_close($rStmt);

echo json_encode([
    'success' => true,
    'career'  => $career,
    'stages'  => $stages
]);

mysqli_close($conn);
?>