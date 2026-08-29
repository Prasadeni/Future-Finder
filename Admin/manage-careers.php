<?php
session_start();
require_once __DIR__ . '/../Includes/db_connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../login.php'); exit;
}

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM Careers WHERE CareerID=$id");
    header('Location: manage-careers.php?msg=deleted'); exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = (int)($_POST['id'] ?? 0);
    $ti   = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
    $de   = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $sk   = mysqli_real_escape_string($conn, trim($_POST['required_education'] ?? ''));
    $sal  = mysqli_real_escape_string($conn, trim($_POST['salary_range'] ?? ''));
    $dem  = in_array($_POST['demand']??'', ['High','Medium','Low']) ? $_POST['demand'] : 'Medium';
    $growth = mysqli_real_escape_string($conn, trim($_POST['growth'] ?? ''));
    $industry = mysqli_real_escape_string($conn, trim($_POST['industry'] ?? ''));

    if ($ti && $de) {
        if ($id > 0) {
            mysqli_query($conn, "UPDATE Careers SET Title='$ti', Description='$de', RequiredEducation='$sk', SalaryRange='$sal', Demand='$dem', Growth='$growth', Industry='$industry' WHERE CareerID=$id");
            $msg = 'updated';
        } else {
            mysqli_query($conn, "INSERT INTO Careers (Title, Description, RequiredEducation, SalaryRange, Demand, Growth, Industry) VALUES ('$ti','$de','$sk','$sal','$dem','$growth','$industry')");
            $msg = 'added';
        }
        header("Location: manage-careers.php?msg=$msg"); exit;
    }
}

$msg     = $_GET['msg'] ?? '';
$editId  = (int)($_GET['edit'] ?? 0);
$editRow = $editId > 0 ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Careers WHERE CareerID=$editId")) : null;
$careers = mysqli_query($conn, "SELECT * FROM Careers ORDER BY Title");
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
        <input type="hidden" name="id" value="<?= $editRow['CareerID'] ?? 0 ?>">

        <div class="form-group">
          <label>Career Title *</label>
          <input type="text" name="title" placeholder="e.g. Software Engineer" value="<?= htmlspecialchars($editRow['Title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label>Industry</label>
          <input type="text" name="industry" placeholder="e.g. Technology, Finance" value="<?= htmlspecialchars($editRow['Industry'] ?? '') ?>">
        </div>
        <div class="form-group full">
          <label>Description *</label>
          <textarea name="description" rows="3" required><?= htmlspecialchars($editRow['Description'] ?? '') ?></textarea>
        </div>
        <div class="form-group full">
          <label>Required Education / Skills</label>
          <input type="text" name="required_education" placeholder="e.g. Bachelor's in Computer Science" value="<?= htmlspecialchars($editRow['RequiredEducation'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Salary Range</label>
          <input type="text" name="salary_range" placeholder="e.g. $40,000 – $80,000/yr" value="<?= htmlspecialchars($editRow['SalaryRange'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Demand Level</label>
          <select name="demand">
            <?php foreach (['High','Medium','Low'] as $d): ?>
              <option value="<?= $d ?>" <?= ($editRow['Demand'] ?? 'Medium')===$d?'selected':'' ?>><?= $d ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Growth</label>
          <input type="text" name="growth" placeholder="e.g. High, 15%" value="<?= htmlspecialchars($editRow['Growth'] ?? '') ?>">
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
          <tr><th>#</th><th>Title</th><th>Industry</th><th>Required Education</th><th>Salary</th><th>Demand</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($careers)===0): ?>
            <tr><td colspan="7" class="empty">No careers yet. Add one above.</td></tr>
          <?php else: $i=1; while ($c=mysqli_fetch_assoc($careers)): ?>
            <tr>
              <td style="color:var(--muted)"><?= $i++ ?></td>
              <td><strong><?= htmlspecialchars($c['Title']) ?></strong></td>
              <td><span class="badge badge-user"><?= htmlspecialchars($c['Industry']?:'—') ?></span></td>
              <td style="font-size:11px;color:var(--muted);max-width:200px">
                <?= htmlspecialchars(strlen($c['RequiredEducation'])>60?substr($c['RequiredEducation'],0,60).'…':$c['RequiredEducation']) ?>
              </td>
              <td style="font-size:12px"><?= htmlspecialchars($c['SalaryRange']?:'—') ?></td>
              <td>
                <span class="badge <?= $c['Demand']==='High'?'badge-high':($c['Demand']==='Medium'?'badge-medium':'badge-low') ?>">
                  <?= $c['Demand']?:'—' ?>
                </span>
              </td>
              <td class="action-btns">
                <a href="manage-careers.php?edit=<?= $c['CareerID'] ?>" class="btn-sm btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                <a href="manage-careers.php?delete=<?= $c['CareerID'] ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this career?')" title="Delete"><i class="fa-solid fa-trash"></i></a>
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