<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../Includes/db_connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// ── Delete user ──────────────────────────────────────────────
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Prevent admin from deleting themselves
    if ($id === (int)$_SESSION['user_id']) {
        header('Location: manage-users.php?msg=cannot_delete_self');
        exit;
    }

    // Check if user exists and is a regular user (not admin)
    $check = mysqli_prepare($conn, "SELECT id, role FROM Users WHERE id = ?");
    mysqli_stmt_bind_param($check, 'i', $id);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($check);

    if (!$user) {
        header('Location: manage-users.php?msg=not_found');
        exit;
    }

    if ($user['role'] === 'admin') {
        header('Location: manage-users.php?msg=cannot_delete_admin');
        exit;
    }

    // ── Start transaction to delete all related records ──
    mysqli_begin_transaction($conn);

    try {
        // 1. Delete all Answers for this user's Assessments
        $delAnswers = mysqli_prepare($conn, 
            "DELETE a FROM Answers a 
             JOIN Assessments ass ON a.AssessmentID = ass.AssessmentID 
             WHERE ass.UserID = ?"
        );
        mysqli_stmt_bind_param($delAnswers, 'i', $id);
        mysqli_stmt_execute($delAnswers);
        mysqli_stmt_close($delAnswers);

        // 2. Delete all Assessments for this user
        $delAssessments = mysqli_prepare($conn, "DELETE FROM Assessments WHERE UserID = ?");
        mysqli_stmt_bind_param($delAssessments, 'i', $id);
        mysqli_stmt_execute($delAssessments);
        mysqli_stmt_close($delAssessments);

        // 3. Delete CV records
        $delCV = mysqli_prepare($conn, "DELETE FROM CV WHERE UserID = ?");
        mysqli_stmt_bind_param($delCV, 'i', $id);
        mysqli_stmt_execute($delCV);
        mysqli_stmt_close($delCV);

        // 4. Delete Comparisons
        $delComparisons = mysqli_prepare($conn, "DELETE FROM Comparisons WHERE UserID = ?");
        mysqli_stmt_bind_param($delComparisons, 'i', $id);
        mysqli_stmt_execute($delComparisons);
        mysqli_stmt_close($delComparisons);

        // 5. Delete the user
        $delUser = mysqli_prepare($conn, "DELETE FROM Users WHERE id = ?");
        mysqli_stmt_bind_param($delUser, 'i', $id);
        mysqli_stmt_execute($delUser);
        mysqli_stmt_close($delUser);

        // Commit transaction
        mysqli_commit($conn);
        header('Location: manage-users.php?msg=deleted');
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header('Location: manage-users.php?msg=delete_failed');
    }
    exit;
}

// ── Toggle role ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_role'])) {
    $id  = (int)$_POST['user_id'];
    $rol = in_array($_POST['new_role'], ['user','admin']) ? $_POST['new_role'] : 'user';
    
    if ($id !== (int)$_SESSION['user_id']) {
        $update = mysqli_prepare($conn, "UPDATE Users SET role = ? WHERE id = ?");
        mysqli_stmt_bind_param($update, 'si', $rol, $id);
        mysqli_stmt_execute($update);
        mysqli_stmt_close($update);
    }
    header('Location: manage-users.php?msg=updated');
    exit;
}

// ── Search and list users ────────────────────────────────────
$msg    = $_GET['msg'] ?? '';
$search = trim($_GET['search'] ?? '');
$where  = $search ? "WHERE (first_name LIKE '%".mysqli_real_escape_string($conn,$search)."%' OR last_name LIKE '%".mysqli_real_escape_string($conn,$search)."%' OR email LIKE '%".mysqli_real_escape_string($conn,$search)."%')" : '';
$users  = mysqli_query($conn, "SELECT * FROM Users $where ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users | Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../CSS/admin.css">
</head>
<body>
<?php include __DIR__ . '/admin-sidebar.php'; ?>
<div class="admin-main">
  <?php include __DIR__ . '/admin-topbar.php'; ?>
  <div class="admin-content">

    <div class="page-header">
      <h1>Manage Users</h1>
      <p>View, search, promote and delete student accounts.</p>
    </div>

    <?php if ($msg === 'deleted'): ?>
      <div class="alert success"><i class="fa-solid fa-check"></i> Student deleted successfully.</div>
    <?php elseif ($msg === 'updated'): ?>
      <div class="alert success"><i class="fa-solid fa-check"></i> User role updated.</div>
    <?php elseif ($msg === 'cannot_delete_self'): ?>
      <div class="alert error"><i class="fa-solid fa-triangle-exclamation"></i> You cannot delete your own account.</div>
    <?php elseif ($msg === 'cannot_delete_admin'): ?>
      <div class="alert error"><i class="fa-solid fa-triangle-exclamation"></i> You cannot delete an admin account.</div>
    <?php elseif ($msg === 'not_found'): ?>
      <div class="alert error"><i class="fa-solid fa-triangle-exclamation"></i> User not found.</div>
    <?php elseif ($msg === 'delete_failed'): ?>
      <div class="alert error"><i class="fa-solid fa-triangle-exclamation"></i> Delete failed. Please try again.</div>
    <?php endif; ?>

    <form method="GET" class="search-bar">
      <input type="text" name="search" placeholder="Search by name or email..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
      <?php if ($search): ?><a href="manage-users.php" class="btn-clear">Clear</a><?php endif; ?>
    </form>

    <div class="admin-card">
      <div class="card-header">
        <h2>All Users <span style="color:var(--muted);font-weight:400;font-size:13px">(<?= mysqli_num_rows($users) ?> found)</span></h2>
      </div>
      <table class="admin-table">
        <thead>
          <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($users) === 0): ?>
            <tr><td colspan="6" class="empty">No users found.</td></tr>
          <?php else: $i=1; while ($u = mysqli_fetch_assoc($users)): ?>
            <tr>
              <td style="color:var(--muted)"><?= $i++ ?></td>
              <td><strong><?= htmlspecialchars($u['first_name'].' '.$u['last_name']) ?></strong></td>
              <td style="color:var(--muted);font-size:12px"><?= htmlspecialchars($u['email']) ?></td>
              <td><span class="badge <?= $u['role']==='admin'?'badge-admin':'badge-user' ?>"><?= ucfirst($u['role']) ?></span></td>
              <td style="font-size:12px;color:var(--muted)"><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
              <td class="action-btns">
                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                  <form method="POST" style="display:inline">
                    <input type="hidden" name="user_id"  value="<?= $u['id'] ?>">
                    <input type="hidden" name="new_role" value="<?= $u['role']==='admin'?'user':'admin' ?>">
                    <button type="submit" name="toggle_role"
                            class="btn-sm btn-promote"
                            title="<?= $u['role']==='admin'?'Demote to Student':'Promote to Admin' ?>">
                      <i class="fa-solid <?= $u['role']==='admin'?'fa-user-minus':'fa-user-shield' ?>"></i>
                    </button>
                  </form>
                  <a href="manage-users.php?delete=<?= $u['id'] ?>"
                     class="btn-sm btn-delete"
                     onclick="return confirm('Delete <?= htmlspecialchars($u['first_name']) ?>? This action cannot be undone.')"
                     title="Delete">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                <?php else: ?>
                  <span style="color:var(--muted);font-size:11px">You</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>
</body>
</html>