<?php


session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Use shared mysqli connection to futurefinder database
require_once 'Includes/db_connection.php';

$firstName = trim($_POST['firstName'] ?? '');
$lastName  = trim($_POST['lastName']  ?? '');
$email     = trim($_POST['email']     ?? '');
$password  = $_POST['password']        ?? '';

$errors = [];

// Validate inputs
if ($firstName === '' || $lastName === '') {
    $errors['name'] = 'First and last name are required';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Enter a valid email address';
}
if (strlen($password) < 6) {
    $errors['password'] = 'Password must be at least 6 characters';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Check if email already exists in futurefinder.Users
$check = mysqli_prepare($conn, 'SELECT id FROM Users WHERE email = ?');
mysqli_stmt_bind_param($check, 's', $email);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

if (mysqli_stmt_num_rows($check) > 0) {
    mysqli_stmt_close($check);
    http_response_code(409);
    echo json_encode(['success' => false, 'errors' => ['email' => 'An account with this email already exists']]);
    exit;
}
mysqli_stmt_close($check);

// Hash password with bcrypt before saving — NEVER store plain text passwords
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert new user — role defaults to 'user' (registered student)
$stmt = mysqli_prepare($conn, 'INSERT INTO Users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, \'user\')');
mysqli_stmt_bind_param($stmt, 'ssss', $firstName, $lastName, $email, $hashedPassword);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    echo json_encode(['success' => true, 'message' => 'Account created successfully. You can now log in.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Could not create account. Please try again.']);
}

mysqli_close($conn);
?>
