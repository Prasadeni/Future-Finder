<?php
session_start();
require_once __DIR__ . '/../Includes/db_connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../login.php'); exit;
}

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM Questions WHERE id=$id");
    header('Location: manage-questions.php?msg=deleted'); exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = (int)($_POST['id'] ?? 0);
    $qt   = mysqli_real_escape_string($conn, trim($_POST['question_text'] ?? ''));
    $oa   = mysqli_real_escape_string($conn, trim($_POST['option_a'] ?? ''));
    $ob   = mysqli_real_escape_string($conn, trim($_POST['option_b'] ?? ''));
    $oc   = mysqli_real_escape_string($conn, trim($_POST['option_c'] ?? ''));
    $od   = mysqli_real_escape_string($conn, trim($_POST['option_d'] ?? ''));
    $cat  = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
    $ord  = (int)($_POST['question_order'] ?? 0);

    if ($qt && $oa && $ob) {
        if ($id > 0) {
            mysqli_query($conn, "UPDATE Questions SET question_text='$qt',option_a='$oa',option_b='$ob',option_c='$oc',option_d='$od',category='$cat',question_order=$ord WHERE id=$id");
            $msg = 'updated';
        } else {
            mysqli_query($conn, "INSERT INTO Questions (question_text,option_a,option_b,option_c,option_d,category,question_order) VALUES ('$qt','$oa','$ob','$oc','$od','$cat',$ord)");
            $msg = 'added';
        }
        header("Location: manage-questions.php?msg=$msg"); exit;
    }
}

$msg     = $_GET['msg'] ?? '';
$editId  = (int)($_GET['edit'] ?? 0);
$editRow = $editId > 0 ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Questions WHERE id=$editId")) : null;
$questions = mysqli_query($conn, "SELECT * FROM Questions ORDER BY question_order ASC, id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assessment Questions | Admin</title>
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
      <h1>Assessment Questions</h1>
      <p>Add, edit, or delete MCQ questions used in the skill assessment.</p>
    </div>

    <?php if ($msg==='added'):   ?><div class="alert success"><i class="fa-solid fa-check"></i> Question added.</div><?php endif; ?>
    <?php if ($msg==='updated'): ?><div class="alert success"><i class="fa-solid fa-check"></i> Question updated.</div><?php endif; ?>
    <?php if ($msg==='deleted'): ?><div class="alert success"><i class="fa-solid fa-check"></i> Question deleted.</div><?php endif; ?>

    <!-- Form -->
    <div class="admin-card">
      <h2><?= $editRow ? '<i class="fa-solid fa-pen" style="color:var(--teal);margin-right:8px"></i>Edit Question' : '<i class="fa-solid fa-plus" style="color:var(--teal);margin-right:8px"></i>Add New Question' ?></h2>
      <form method="POST" class="admin-form">
        <input type="hidden" name="id" value="<?= $editRow['id'] ?? 0 ?>">

        <div class="form-group full">
          <label>Question Text *</label>
          <textarea name="question_text" rows="3" required><?= htmlspecialchars($editRow['question_text'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
          <label>Option A *</label>
          <input type="text" name="option_a" value="<?= htmlspecialchars($editRow['option_a'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label>Option B *</label>
          <input type="text" name="option_b" value="<?= htmlspecialchars($editRow['option_b'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label>Option C</label>
          <input type="text" name="option_c" value="<?= htmlspecialchars($editRow['option_c'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Option D</label>
          <input type="text" name="option_d" value="<?= htmlspecialchars($editRow['option_d'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Category <small>(e.g. Technology, Arts)</small></label>
          <input type="text" name="category" placeholder="e.g. Technology" value="<?= htmlspecialchars($editRow['category'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Display Order</label>
          <input type="number" name="question_order" min="1" value="<?= $editRow['question_order'] ?? '' ?>" placeholder="1, 2, 3...">
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-primary">
            <i class="fa-solid <?= $editRow?'fa-floppy-disk':'fa-plus' ?>"></i>
            <?= $editRow ? 'Update Question' : 'Add Question' ?>
          </button>
          <?php if ($editRow): ?><a href="manage-questions.php" class="btn-secondary">Cancel</a><?php endif; ?>
        </div>
      </form>
    </div>

    <!-- List -->
    <div class="admin-card">
      <div class="card-header">
        <h2>All Questions <span style="color:var(--muted);font-weight:400;font-size:13px">(<?= mysqli_num_rows($questions) ?> total)</span></h2>
      </div>
      <table class="admin-table">
        <thead>
          <tr><th>#</th><th>Question</th><th>Category</th><th>Options</th><th>Order</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($questions)===0): ?>
            <tr><td colspan="6" class="empty">No questions yet. Add one above.</td></tr>
          <?php else: $i=1; while ($q=mysqli_fetch_assoc($questions)): ?>
            <tr>
              <td style="color:var(--muted)"><?= $i++ ?></td>
              <td style="max-width:280px"><?= htmlspecialchars($q['question_text']) ?></td>
              <td><span class="badge badge-user"><?= htmlspecialchars($q['category']?:'—') ?></span></td>
              <td style="font-size:11px;color:var(--muted)">
                A: <?= htmlspecialchars($q['option_a']) ?><br>
                B: <?= htmlspecialchars($q['option_b']) ?>
              </td>
              <td style="text-align:center"><?= $q['question_order'] ?: '—' ?></td>
              <td class="action-btns">
                <a href="manage-questions.php?edit=<?= $q['id'] ?>" class="btn-sm btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                <a href="manage-questions.php?delete=<?= $q['id'] ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this question?')" title="Delete"><i class="fa-solid fa-trash"></i></a>
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
