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
$message = '';
$messageType = '';

// ── Fetch current user data ──
$stmt = mysqli_prepare($conn, "SELECT first_name, last_name, email FROM Users WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// ── Check if user has a CV ──
$cvCheckStmt = mysqli_prepare($conn, "SELECT CVID FROM CV WHERE UserID = ?");
mysqli_stmt_bind_param($cvCheckStmt, 'i', $userID);
mysqli_stmt_execute($cvCheckStmt);
mysqli_stmt_store_result($cvCheckStmt);
$hasCV = mysqli_stmt_num_rows($cvCheckStmt) > 0;
mysqli_stmt_close($cvCheckStmt);

// ── Check if profile table exists and create if not ──
$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'user_profiles'");
if (mysqli_num_rows($tableCheck) == 0) {
    $createSQL = "
        CREATE TABLE `user_profiles` (
            `profile_id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `institution` varchar(255) DEFAULT NULL,
            `degree` varchar(255) DEFAULT NULL,
            `field_of_study` varchar(255) DEFAULT NULL,
            `graduation_year` year(4) DEFAULT NULL,
            `gpa` varchar(20) DEFAULT NULL,
            `skills` text DEFAULT NULL,
            `interests` text DEFAULT NULL,
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`profile_id`),
            UNIQUE KEY `user_id` (`user_id`),
            CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
    mysqli_query($conn, $createSQL);
}

// ── Fetch profile data ──
$profileStmt = mysqli_prepare($conn, "SELECT * FROM user_profiles WHERE user_id = ?");
mysqli_stmt_bind_param($profileStmt, 'i', $userID);
mysqli_stmt_execute($profileStmt);
$profileResult = mysqli_stmt_get_result($profileStmt);
$profile = mysqli_fetch_assoc($profileResult);
mysqli_stmt_close($profileStmt);

// ── Handle form submission ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $institution = trim($_POST['institution'] ?? '');
    $degree = trim($_POST['degree'] ?? '');
    $fieldOfStudy = trim($_POST['field_of_study'] ?? '');
    $graduationYear = !empty($_POST['graduation_year']) ? intval($_POST['graduation_year']) : null;
    $gpa = trim($_POST['gpa'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $interests = trim($_POST['interests'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $errors = [];

    if (empty($firstName) || empty($lastName)) {
        $errors[] = 'First name and last name are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }

    if (empty($errors)) {
        $checkStmt = mysqli_prepare($conn, "SELECT id FROM Users WHERE email = ? AND id != ?");
        mysqli_stmt_bind_param($checkStmt, 'si', $email, $userID);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);
        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $errors[] = 'This email is already used by another account.';
        }
        mysqli_stmt_close($checkStmt);
    }

    if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
        $passStmt = mysqli_prepare($conn, "SELECT password FROM Users WHERE id = ?");
        mysqli_stmt_bind_param($passStmt, 'i', $userID);
        mysqli_stmt_execute($passStmt);
        $passResult = mysqli_stmt_get_result($passStmt);
        $row = mysqli_fetch_assoc($passResult);
        mysqli_stmt_close($passStmt);

        if (!password_verify($currentPassword, $row['password'])) {
            $errors[] = 'Current password is incorrect.';
        }
        if (strlen($newPassword) < 6) {
            $errors[] = 'New password must be at least 6 characters.';
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }
    }

    if (empty($errors)) {
        $updateStmt = mysqli_prepare($conn, "UPDATE Users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
        mysqli_stmt_bind_param($updateStmt, 'sssi', $firstName, $lastName, $email, $userID);
        $success = mysqli_stmt_execute($updateStmt);
        mysqli_stmt_close($updateStmt);

        if (!empty($newPassword) && $success) {
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $passUpdate = mysqli_prepare($conn, "UPDATE Users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($passUpdate, 'si', $hashed, $userID);
            $success = mysqli_stmt_execute($passUpdate);
            mysqli_stmt_close($passUpdate);
        }

        if ($success) {
            if ($profile) {
                $profileUpdate = mysqli_prepare($conn, "
                    UPDATE user_profiles SET 
                        institution = ?, degree = ?, field_of_study = ?, 
                        graduation_year = ?, gpa = ?, skills = ?, interests = ? 
                    WHERE user_id = ?
                ");
                mysqli_stmt_bind_param($profileUpdate, 'sssssssi', 
                    $institution, $degree, $fieldOfStudy, $graduationYear, $gpa, $skills, $interests, $userID
                );
                $profileSuccess = mysqli_stmt_execute($profileUpdate);
                mysqli_stmt_close($profileUpdate);
            } else {
                $profileInsert = mysqli_prepare($conn, "
                    INSERT INTO user_profiles (user_id, institution, degree, field_of_study, graduation_year, gpa, skills, interests) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                mysqli_stmt_bind_param($profileInsert, 'isssssss', 
                    $userID, $institution, $degree, $fieldOfStudy, $graduationYear, $gpa, $skills, $interests
                );
                $profileSuccess = mysqli_stmt_execute($profileInsert);
                mysqli_stmt_close($profileInsert);
            }

            if ($profileSuccess) {
                $_SESSION['first_name'] = $firstName;
                $_SESSION['last_name'] = $lastName;
                $message = 'Profile updated successfully!';
                $messageType = 'success';
                
                $user['first_name'] = $firstName;
                $user['last_name'] = $lastName;
                $user['email'] = $email;
                $profile['institution'] = $institution;
                $profile['degree'] = $degree;
                $profile['field_of_study'] = $fieldOfStudy;
                $profile['graduation_year'] = $graduationYear;
                $profile['gpa'] = $gpa;
                $profile['skills'] = $skills;
                $profile['interests'] = $interests;
            } else {
                $message = 'Failed to update profile. Please try again.';
                $messageType = 'error';
            }
        } else {
            $message = 'Failed to update profile. Please try again.';
            $messageType = 'error';
        }
    } else {
        $message = implode('<br>', $errors);
        $messageType = 'error';
    }
}

mysqli_close($conn);
$currentPage = 'profile.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Future Finder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #0d0e3a; color: #ffffff; min-height: 100vh; }
        .profile-wrapper { max-width: 820px; margin: 40px auto; padding: 0 24px 80px; }
        .profile-header { margin-bottom: 30px; animation: fadeInUp 0.8s ease forwards; }
        .profile-header h1 { font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 900; margin-bottom: 2px; }
        .profile-header h1 span { color: #36ada3; }
        .profile-header p { color: rgba(255,255,255,0.6); font-size: 1rem; }
        .profile-card { background: rgba(26,31,122,0.4); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.06); border-radius: 20px; padding: 36px 32px; animation: fadeInUp 0.8s ease 0.1s forwards; opacity: 0; }
        .profile-card .form-group { margin-bottom: 18px; }
        .profile-card label { display: block; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.3px; color: rgba(255,255,255,0.5); margin-bottom: 4px; }
        .profile-card label .required { color: #ef4444; }
        .profile-card input, .profile-card select, .profile-card textarea { width: 100%; padding: 12px 16px; background: rgba(13,14,58,0.5); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.95rem; transition: border-color 0.3s; }
        .profile-card input:focus, .profile-card select:focus, .profile-card textarea:focus { outline: none; border-color: #36ada3; }
        .profile-card input::placeholder, .profile-card textarea::placeholder { color: rgba(255,255,255,0.25); }
        .profile-card textarea { resize: vertical; min-height: 60px; }
        .profile-card select option { background: #0d0e3a; color: #fff; }
        .profile-card .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .profile-card .form-row.three-col { grid-template-columns: 1fr 1fr 1fr; }
        .profile-card .section-divider { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 24px 0 20px 0; }
        .profile-card .section-title { font-size: 0.85rem; font-weight: 700; color: #36ada3; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
        .profile-card .section-title i { font-size: 16px; }
        .profile-card .btn { padding: 12px 28px; border: none; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; transition: background 0.3s, transform 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .profile-card .btn-primary { background: #36ada3; color: #fff; }
        .profile-card .btn-primary:hover { background: #2d9992; transform: translateY(-2px); }
        .profile-card .btn-primary:active { transform: scale(0.97); }
        .profile-card .btn-secondary { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.08); text-decoration: none; }
        .profile-card .btn-secondary:hover { background: rgba(255,255,255,0.12); transform: translateY(-2px); }
        .profile-card .form-actions { display: flex; gap: 12px; margin-top: 8px; flex-wrap: wrap; }
        .profile-card .status-msg { padding: 12px 16px; border-radius: 10px; margin-top: 16px; font-weight: 600; display: none; }
        .profile-card .status-msg.success { background: rgba(54,173,163,0.12); border: 1px solid rgba(54,173,163,0.2); color: #36ada3; display: block; }
        .profile-card .status-msg.error { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.2); color: #ef4444; display: block; }
        .profile-card .cv-notice { background: rgba(255,193,7,0.08); border: 1px solid rgba(255,193,7,0.15); border-radius: 10px; padding: 12px 16px; margin-top: 16px; color: rgba(255,255,255,0.7); font-size: 0.9rem; display: flex; align-items: center; gap: 10px; }
        .profile-card .cv-notice i { color: #ffc107; font-size: 18px; }
        .profile-card .cv-notice a { color: #36ada3; text-decoration: none; font-weight: 600; }
        .profile-card .cv-notice a:hover { text-decoration: underline; }
        .password-hint { font-size: 0.75rem; color: rgba(255,255,255,0.3); margin-top: 4px; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 768px) { .profile-card .form-row { grid-template-columns: 1fr; gap: 0; } .profile-card .form-row.three-col { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 480px) { .profile-wrapper { padding: 16px 12px 60px; } .profile-card { padding: 20px 16px; } .profile-card .form-row.three-col { grid-template-columns: 1fr; } .profile-card .form-actions { flex-direction: column; } .profile-card .form-actions .btn { justify-content: center; } }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/../shared/navbaroptional.php'; ?>

    <div class="profile-wrapper">
        <div class="profile-header">
            <h1>My <span>Profile</span></h1>
            <p>Manage your personal information, educational details, skills, and interests.</p>
        </div>

        <div class="profile-card">
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_profile">

                <!-- PERSONAL DETAILS -->
                <div class="section-title"><i class="fas fa-user"></i> Personal Details</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name <span class="required">*</span></label>
                        <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="required">*</span></label>
                        <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email Address <span class="required">*</span></label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <!-- EDUCATIONAL DETAILS -->
                <hr class="section-divider">
                <div class="section-title"><i class="fas fa-graduation-cap"></i> Educational Details</div>
                <div class="form-group">
                    <label>Institution / University</label>
                    <input type="text" name="institution" placeholder="Uva Wellassa University" value="<?= htmlspecialchars($profile['institution'] ?? '') ?>">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Degree</label>
                        <input type="text" name="degree" placeholder="BSc in Industrial IT" value="<?= htmlspecialchars($profile['degree'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Field of Study</label>
                        <input type="text" name="field_of_study" placeholder="Computer Science" value="<?= htmlspecialchars($profile['field_of_study'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-row three-col">
                    <div class="form-group">
                        <label>Graduation Year</label>
                        <select name="graduation_year">
                            <option value="">— Select Year —</option>
                            <?php for ($year = date('Y'); $year >= 2010; $year--): ?>
                                <option value="<?= $year ?>" <?= (isset($profile['graduation_year']) && $profile['graduation_year'] == $year) ? 'selected' : '' ?>><?= $year ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>GPA / Grade</label>
                        <input type="text" name="gpa" placeholder="3.8 / 4.0" value="<?= htmlspecialchars($profile['gpa'] ?? '') ?>">
                    </div>
                </div>

                <!-- SKILLS & INTERESTS -->
                <hr class="section-divider">
                <div class="section-title"><i class="fas fa-tools"></i> Skills & Interests</div>
                <div class="form-group">
                    <label>Skills <span style="font-weight:400;color:rgba(255,255,255,0.3);">(comma separated)</span></label>
                    <textarea name="skills" rows="2" placeholder="JavaScript, Python, React, Project Management"><?= htmlspecialchars($profile['skills'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Interests <span style="font-weight:400;color:rgba(255,255,255,0.3);">(comma separated)</span></label>
                    <textarea name="interests" rows="2" placeholder="Web Development, Data Science, AI, UX Design"><?= htmlspecialchars($profile['interests'] ?? '') ?></textarea>
                </div>

                <!-- CHANGE PASSWORD -->
                <hr class="section-divider">
                <div class="section-title"><i class="fas fa-key"></i> Change Password</div>
                <div class="password-hint" style="margin-bottom:12px;">
                    <i class="fas fa-info-circle"></i> Leave password fields empty if you don't want to change your password.
                </div>
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" placeholder="Enter current password to change">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" placeholder="Min 6 characters">
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" placeholder="Re-enter new password">
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Profile</button>
                    <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                </div>

                <?php if ($message): ?>
                    <div class="status-msg <?= $messageType ?>"><?= $message ?></div>
                <?php endif; ?>

                <?php if ($hasCV && $messageType === 'success'): ?>
                    <div class="cv-notice">
                        <i class="fas fa-info-circle"></i>
                        <span>You have already created a CV. Please <a href="cv.php">update your CV separately</a> to reflect these changes, or use the <strong>"Sync with Profile"</strong> button on the CV page.</span>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php require_once __DIR__ . '/../shared/footer.php'; ?>
</body>
</html>