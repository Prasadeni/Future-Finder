<?php
session_start();
require_once __DIR__ . '/../Includes/db_connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../login.php'); exit;
}

// Helper
function safeCount($conn, $table) {
    $result = mysqli_query($conn, "SELECT COUNT(*) c FROM `$table`");
    return ($result && mysqli_num_rows($result)) ? (int)mysqli_fetch_assoc($result)['c'] : 0;
}

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM Questions WHERE QuestionID=$id");
    header('Location: manage-questions.php?msg=deleted'); exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = (int)($_POST['id'] ?? 0);
    $text = mysqli_real_escape_string($conn, trim($_POST['text'] ?? ''));
    $cat  = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
    $weight = (int)($_POST['weight'] ?? 0);

    // Build options JSON from individual option fields
    $opts = [];
    if (!empty($_POST['option_a'])) $opts[] = ['label' => $_POST['option_a'], 'scores' => []];
    if (!empty($_POST['option_b'])) $opts[] = ['label' => $_POST['option_b'], 'scores' => []];
    if (!empty($_POST['option_c'])) $opts[] = ['label' => $_POST['option_c'], 'scores' => []];
    if (!empty($_POST['option_d'])) $opts[] = ['label' => $_POST['option_d'], 'scores' => []];
    $options_json = json_encode($opts);

    if ($text && count($opts) >= 2) {
        if ($id > 0) {
            mysqli_query($conn, "UPDATE Questions SET Text='$text', Category='$cat', Weight=$weight, Options='$options_json' WHERE QuestionID=$id");
            $msg = 'updated';
        } else {
            mysqli_query($conn, "INSERT INTO Questions (Text, Category, Weight, Options) VALUES ('$text','$cat',$weight,'$options_json')");
            $msg = 'added';
        }
        header("Location: manage-questions.php?msg=$msg"); exit;
    }
}

$msg     = $_GET['msg'] ?? '';
$editId  = (int)($_GET['edit'] ?? 0);
$editRow = null;
if ($editId > 0) {
    $res = mysqli_query($conn, "SELECT * FROM Questions WHERE QuestionID=$editId");
    $editRow = mysqli_fetch_assoc($res);
    // Decode options JSON into separate fields for the form
    if ($editRow && !empty($editRow['Options'])) {
        $opts = json_decode($editRow['Options'], true);
        if (is_array($opts)) {
            for ($i=0; $i<count($opts); $i++) {
                $editRow['option_'.chr(97+$i)] = $opts[$i]['label'] ?? '';
            }
        }
    }
}
$questions = mysqli_query($conn, "SELECT * FROM Questions ORDER BY Weight ASC, QuestionID ASC");
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

    <div class="admin-card">
      <h2><?= $editRow ? '<i class="fa-solid fa-pen" style="color:var(--teal);margin-right:8px"></i>Edit Question' : '<i class="fa-solid fa-plus" style="color:var(--teal);margin-right:8px"></i>Add New Question' ?></h2>
      <form method="POST" class="admin-form">
        <input type="hidden" name="id" value="<?= $editRow['QuestionID'] ?? 0 ?>">

        <div class="form-group full">
          <label>Question Text *</label>
          <textarea name="text" rows="3" required><?= htmlspecialchars($editRow['Text'] ?? '') ?></textarea>
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
          <label>Category</label>
          <input type="text" name="category" placeholder="e.g. Technology" value="<?= htmlspecialchars($editRow['Category'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Weight (order)</label>
          <input type="number" name="weight" min="1" value="<?= $editRow['Weight'] ?? 1 ?>">
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

    <div class="admin-card">
      <div class="card-header">
        <h2>All Questions <span style="color:var(--muted);font-weight:400;font-size:13px">(<?= mysqli_num_rows($questions) ?> total)</span></h2>
      </div>
      <table class="admin-table">
        <thead>
          <tr><th>#</th><th>Question</th><th>Category</th><th>Options</th><th>Weight</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($questions)===0): ?>
            <tr><td colspan="6" class="empty">No questions yet. Add one above.</td></tr>
          <?php else: $i=1; while ($q = mysqli_fetch_assoc($questions)):
            $opts = json_decode($q['Options'], true);
            $optLabels = is_array($opts) ? array_column($opts, 'label') : [];
          ?>
            <tr>
              <td style="color:var(--muted)"><?= $i++ ?></td>
              <td style="max-width:280px"><?= htmlspecialchars($q['Text']) ?></td>
              <td><span class="badge badge-user"><?= htmlspecialchars($q['Category']?:'—') ?></span></td>
              <td style="font-size:11px;color:var(--muted)">
                <?php foreach ($optLabels as $idx => $label): ?>
                  <?= chr(65+$idx) ?>: <?= htmlspecialchars($label) ?><br>
                <?php endforeach; ?>
              </td>
              <td style="text-align:center"><?= $q['Weight'] ?: '—' ?></td>
              <td class="action-btns">
                <a href="manage-questions.php?edit=<?= $q['QuestionID'] ?>" class="btn-sm btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                <a href="manage-questions.php?delete=<?= $q['QuestionID'] ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this question?')" title="Delete"><i class="fa-solid fa-trash"></i></a>
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