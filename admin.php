<?php
session_start();
require_once __DIR__ . '/Includes/db_connection.php';

// Admin guard
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php'); exit;
}

// ── Stats ──────────────────────────────────────────────
$totalStudents    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM Users WHERE role='user'"))['c'];
$totalQuestions   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM Questions"))['c'] ?? 0;
$totalCareers     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM Careers"))['c'] ?? 0;
$totalAssessments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM AssessmentResults"))['c'] ?? 0;

// ── Recent students ────────────────────────────────────
$recentStudents = mysqli_query($conn,
    "SELECT id, first_name, last_name, email, created_at
     FROM Users WHERE role='user'
     ORDER BY created_at DESC LIMIT 6");

// ── Top career matches ─────────────────────────────────
$topCareers = null;
$hasResults = mysqli_query($conn, "SHOW TABLES LIKE 'AssessmentResults'");
if (mysqli_num_rows($hasResults) > 0) {
    $topCareers = mysqli_query($conn,
        "SELECT career_title, COUNT(*) total
         FROM AssessmentResults
         GROUP BY career_title
         ORDER BY total DESC LIMIT 5");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Future Finder</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="CSS/admin.css">
</head>
<body>

<?php include __DIR__ . '/Admin/admin-sidebar.php'; ?>

<div class="admin-main">
  <?php include __DIR__ . '/Admin/admin-topbar.php'; ?>

  <div class="admin-content">

    <div class="page-header">
      <h1>Welcome back, <?= htmlspecialchars($_SESSION['first_name']) ?> 👋</h1>
      <p>Here's an overview of the Future Finder system today.</p>
    </div>

    <!-- Stats -->
    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-user-graduate"></i></div>
        <div class="stat-info">
          <span class="stat-number"><?= $totalStudents ?></span>
          <span class="stat-label">Registered Students</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon teal"><i class="fa-solid fa-clipboard-question"></i></div>
        <div class="stat-info">
          <span class="stat-number"><?= $totalQuestions ?></span>
          <span class="stat-label">Assessment Questions</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-briefcase"></i></div>
        <div class="stat-info">
          <span class="stat-number"><?= $totalCareers ?></span>
          <span class="stat-label">Career Paths</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-chart-line"></i></div>
        <div class="stat-info">
          <span class="stat-number"><?= $totalAssessments ?></span>
          <span class="stat-label">Assessments Taken</span>
        </div>
      </div>
    </div>

    <!-- Two columns -->
    <div class="two-col">

      <!-- Recent registrations -->
      <div class="admin-card">
        <div class="card-header">
          <h2><i class="fa-solid fa-users" style="color:var(--teal);margin-right:8px"></i>Recent Registrations</h2>
          <a href="Admin/manage-users.php" class="btn-link">View All →</a>
        </div>
        <table class="admin-table">
          <thead>
            <tr><th>Name</th><th>Email</th><th>Joined</th><th></th></tr>
          </thead>
          <tbody>
            <?php if (!$recentStudents || mysqli_num_rows($recentStudents) === 0): ?>
              <tr><td colspan="4" class="empty">No students registered yet.</td></tr>
            <?php else: while ($u = mysqli_fetch_assoc($recentStudents)): ?>
              <tr>
                <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
                <td style="color:var(--muted);font-size:12px"><?= htmlspecialchars($u['email']) ?></td>
                <td style="font-size:12px;color:var(--muted)"><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
                <td>
                  <a href="Admin/manage-users.php?delete=<?= $u['id'] ?>"
                     class="btn-sm btn-delete"
                     onclick="return confirm('Delete this student?')"
                     title="Delete">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endwhile; endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Top careers -->
      <div class="admin-card">
        <div class="card-header">
          <h2><i class="fa-solid fa-trophy" style="color:var(--teal);margin-right:8px"></i>Top Career Matches</h2>
          <a href="Admin/reports.php" class="btn-link">Full Report →</a>
        </div>
        <?php if (!$topCareers || mysqli_num_rows($topCareers) === 0): ?>
          <p style="color:var(--muted);font-size:13px;padding:20px 0">No assessment data yet.</p>
        <?php else:
          $maxTotal = 1;
          $rows = [];
          while ($r = mysqli_fetch_assoc($topCareers)) { $rows[] = $r; if ($r['total'] > $maxTotal) $maxTotal = $r['total']; }
          foreach ($rows as $tc):
        ?>
          <div class="bar-row">
            <span class="bar-label"><?= htmlspecialchars($tc['career_title']) ?></span>
            <div class="bar-track">
              <div class="bar-fill" style="width:<?= round(($tc['total']/$maxTotal)*100) ?>%"></div>
            </div>
            <span class="bar-count"><?= $tc['total'] ?></span>
          </div>
        <?php endforeach; endif; ?>
      </div>

    </div>

    <!-- Quick actions -->
    <div class="admin-card">
      <h2><i class="fa-solid fa-bolt" style="color:var(--teal);margin-right:8px"></i>Quick Actions</h2>
      <div class="quick-grid">
        <a href="Admin/manage-users.php" class="quick-card">
          <i class="fa-solid fa-users"></i><span>Manage Students</span>
        </a>
        <a href="Admin/manage-questions.php" class="quick-card">
          <i class="fa-solid fa-clipboard-question"></i><span>Assessment Questions</span>
        </a>
        <a href="Admin/manage-careers.php" class="quick-card">
          <i class="fa-solid fa-briefcase"></i><span>Career Data</span>
        </a>
        <a href="Admin/reports.php" class="quick-card">
          <i class="fa-solid fa-chart-bar"></i><span>View Reports</span>
        </a>
      </div>
    </div>

  </div>
</div>

</body>
</html>
