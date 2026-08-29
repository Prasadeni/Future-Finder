<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Includes/db_connection.php';

// Check database connection
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed. Please check your XAMPP server.']);
    exit;
}

// Check if IDs are provided
if (!isset($_GET['ids']) || empty($_GET['ids'])) {
    echo json_encode(['error' => 'No career IDs provided.']);
    exit;
}

// Parse and validate IDs
$ids = array_map('intval', explode(',', $_GET['ids']));

if (count($ids) !== 2) {
    echo json_encode(['error' => 'Please provide exactly 2 career IDs.']);
    exit;
}

// Validate both IDs are positive
if ($ids[0] <= 0 || $ids[1] <= 0) {
    echo json_encode(['error' => 'Invalid career IDs provided.']);
    exit;
}

// Build query with placeholders
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$sql = "SELECT CareerID, Title, Description, SalaryRange, Demand, Growth, RequiredEducation, Industry 
        FROM Careers 
        WHERE CareerID IN ($placeholders)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(['error' => 'Database error: ' . mysqli_error($conn)]);
    exit;
}

// Bind parameters dynamically
$types = str_repeat('i', count($ids));
mysqli_stmt_bind_param($stmt, $types, $ids[0], $ids[1]);

// Execute and get results
if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(['error' => 'Query execution failed: ' . mysqli_stmt_error($stmt)]);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit;
}

$result = mysqli_stmt_get_result($stmt);
$careers = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Check if both careers were found
if (count($careers) !== 2) {
    echo json_encode(['error' => 'One or both careers not found in the database.']);
    exit;
}

// Return success
echo json_encode(['success' => true, 'careers' => $careers]);
?>