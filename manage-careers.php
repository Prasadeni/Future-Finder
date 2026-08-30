<?php
session_start();
require_once __DIR__ . '/../Includes/db_connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../login.php'); exit;
}

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM Careers WHERE id=$id");
    header('Location: manage-careers.php?msg=deleted'); exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = (int)($_POST['id'] ?? 0);
    $ti   = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
    $de   = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $sk   = mysqli_real_escape_string($conn, trim($_POST['required_skills'] ?? ''));
    $sal  = mysqli_real_escape_string($conn, trim($_POST['salary_range'] ?? ''));
    $dem  = in_array($_POST['demand_level']??'', ['High','Medium','Low']) ? $_POST['demand_level'] : 'Medium';
    $cat  = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
    $road = mysqli_real_escape_string($conn, trim($_POST['roadmap'] ?? ''));

    if ($ti && $de) {
        if ($id > 0) {
            mysqli_query($conn, "UPDATE Careers SET title='$ti',description='$de',required_skills='$sk',salary_range='$sal',demand_level='$dem',category='$cat',roadmap='$road' WHERE id=$id");
            $msg = 'updated';
        } else {
            mysqli_query($conn, "INSERT INTO Careers (title,description,required_skills,salary_range,demand_level,category,roadmap) VALUES ('$ti','$de','$sk','$sal','$dem','$cat','$road')");
            $msg = 'added';
        }
        header("Location: manage-careers.php?msg=$msg"); exit;
    }
}

$msg     = $_GET['msg'] ?? '';
$editId  = (int)($_GET['edit'] ?? 0);
$editRow = $editId > 0 ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Careers WHERE id=$editId")) : null;
$careers = mysqli_query($conn, "SELECT * FROM Careers ORDER BY category, title");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Career Data | Admin</title>
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
      <h1>Career Data</h1>
      <p>Manage career paths, required skills, salary info, and roadmaps shown to students.</p>
    </div>

    <?php if ($msg==='added'):   ?><div class="alert success"><i class="fa-solid fa-check"></i> Career added.</div><?php endif; ?>
    <?php if ($msg==='updated'): ?><div class="alert success"><i class="fa-solid fa-check"></i> Career updated.</div><?php endif; ?>
    <?php if ($msg==='deleted'): ?><div class="alert success"><i class="fa-solid fa-check"></i> Career deleted.</div><?php endif; ?>

    <div class="admin-card">
      <h2><?= $editRow ? '<i class="fa-solid fa-pen" style="color:var(--teal);margin-right:8px"></i>Edit Career' : '<i class="fa-solid fa-plus" style="color:var(--teal);margin-right:8px"></i>Add New Career' ?></h2>
      <form method="POST" class="admin-form">
        <input type="hidden" name="id" value="<?= $editRow['id'] ?? 0 ?>">

        <div class="form-group">
          <label>Career Title *</label>
          <input type="text" name="title" placeholder="e.g. Software Engineer" value="<?= htmlspecialchars($editRow['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label>Category</label>
          <input type="text" name="category" placeholder="e.g. Technology, Business" value="<?= htmlspecialchars($editRow['category'] ?? '') ?>">
        </div>
        <div class="form-group full">
          <label>Description *</label>
          <textarea name="description" rows="3" required><?= htmlspecialchars($editRow['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group full">
          <label>Required Skills <small>(comma-separated)</small></label>
          <input type="text" name="required_skills" placeholder="e.g. PHP, MySQL, JavaScript, Problem Solving" value="<?= htmlspecialchars($editRow['required_skills'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Salary Range</label>
          <input type="text" name="salary_range" placeholder="e.g. $40,000 – $80,000/yr" value="<?= htmlspecialchars($editRow['salary_range'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Demand Level</label>
          <select name="demand_level">
            <?php foreach (['High','Medium','Low'] as $d): ?>
              <option value="<?= $d ?>" <?= ($editRow['demand_level'] ?? 'Medium')===$d?'selected':'' ?>><?= $d ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group full">
          <label>Career Roadmap <small>(step-by-step, one per line)</small></label>
          <textarea name="roadmap" rows="5" placeholder="Step 1: Learn HTML & CSS&#10;Step 2: Learn JavaScript&#10;Step 3: Learn a backend language..."><?= htmlspecialchars($editRow['roadmap'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-primary">
            <i class="fa-solid <?= $editRow?'fa-floppy-disk':'fa-plus' ?>"></i>
            <?= $editRow ? 'Update Career' : 'Add Career' ?>
          </button>
          <?php if ($editRow): ?><a href="manage-careers.php" class="btn-secondary">Cancel</a><?php endif; ?>
        </div>
      </form>
    </div>

    <div class="admin-card">
      <div class="card-header">
        <h2>All Career Paths <span style="color:var(--muted);font-weight:400;font-size:13px">(<?= mysqli_num_rows($careers) ?> total)</span></h2>
      </div>
      <table class="admin-table">
        <thead>
          <tr><th>#</th><th>Title</th><th>Category</th><th>Required Skills</th><th>Salary</th><th>Demand</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($careers)===0): ?>
            <tr><td colspan="7" class="empty">No careers yet. Add one above.</td></tr>
          <?php else: $i=1; while ($c=mysqli_fetch_assoc($careers)): ?>
            <tr>
              <td style="color:var(--muted)"><?= $i++ ?></td>
              <td><strong><?= htmlspecialchars($c['title']) ?></strong></td>
              <td><span class="badge badge-user"><?= htmlspecialchars($c['category']?:'—') ?></span></td>
              <td style="font-size:11px;color:var(--muted);max-width:200px">
                <?= htmlspecialchars(strlen($c['required_skills'])>60?substr($c['required_skills'],0,60).'…':$c['required_skills']) ?>
              </td>
              <td style="font-size:12px"><?= htmlspecialchars($c['salary_range']?:'—') ?></td>
              <td>
                <span class="badge <?= $c['demand_level']==='High'?'badge-high':($c['demand_level']==='Medium'?'badge-medium':'badge-low') ?>">
                  <?= $c['demand_level']?:'—' ?>
                </span>
              </td>
              <td class="action-btns">
                <a href="manage-careers.php?edit=<?= $c['id'] ?>" class="btn-sm btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                <a href="manage-careers.php?delete=<?= $c['id'] ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this career?')" title="Delete"><i class="fa-solid fa-trash"></i></a>
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
