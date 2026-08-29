<?php
session_start();
require_once __DIR__ . '/../Includes/db_connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../login.php'); exit;
}

// Delete user
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id !== (int)$_SESSION['user_id']) {
        mysqli_query($conn, "DELETE FROM Users WHERE id=$id AND role='user'");
    }
    header('Location: manage-users.php?msg=deleted'); exit;
}

// Toggle role
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_role'])) {
    $id  = (int)$_POST['user_id'];
    $rol = in_array($_POST['new_role'], ['user','admin']) ? $_POST['new_role'] : 'user';
    if ($id !== (int)$_SESSION['user_id']) {
        mysqli_query($conn, "UPDATE Users SET role='$rol' WHERE id=$id");
    }
    header('Location: manage-users.php?msg=updated'); exit;
}

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

    <?php if ($msg === 'deleted'): ?><div class="alert success"><i class="fa-solid fa-check"></i> Student deleted successfully.</div><?php endif; ?>
    <?php if ($msg === 'updated'): ?><div class="alert success"><i class="fa-solid fa-check"></i> User role updated.</div><?php endif; ?>

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
                     onclick="return confirm('Delete <?= htmlspecialchars($u['first_name']) ?>?')"
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
