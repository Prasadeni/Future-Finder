<?php

session_start();
header('Content-Type: application/json');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Use the shared mysqli connection to futurefinder database
require_once 'Includes/db_connection.php';

$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']       ?? '';

// Basic validation
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => ['email' => 'Enter a valid email address']]);
    exit;
}
if (strlen($password) < 6) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => ['password' => 'Password must be at least 6 characters']]);
    exit;
}

// Fetch user from futurefinder Users table
$stmt = mysqli_prepare($conn, 'SELECT id, first_name, last_name, email, password, role FROM Users WHERE email = ?');
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user   = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Verify password — password_verify() checks bcrypt hash
if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Incorrect email or password']);
    exit;
}

// Regenerate session ID on login to prevent session fixation attacks
session_regenerate_id(true);

// Store user info in PHP session — used by session guards on all protected pages
$_SESSION['user_id']    = $user['id'];
$_SESSION['first_name'] = $user['first_name'];
$_SESSION['last_name']  = $user['last_name'];
$_SESSION['email']      = $user['email'];
$_SESSION['role']       = $user['role'];

// Redirect based on role:
// admin → admin panel | user → student dashboard
$redirect = ($user['role'] === 'admin') ? 'admin.php' : 'User/dashboard.php';

echo json_encode([
    'success'  => true,
    'message'  => 'Logged in successfully',
    'redirect' => $redirect,
    'user'     => [
        'firstName' => $user['first_name'],
        'lastName'  => $user['last_name'],
        'email'     => $user['email'],
        'role'      => $user['role'],
    ]
]);

mysqli_close($conn);
?>
