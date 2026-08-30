<?php
session_start();
require_once __DIR__ . '/../Includes/db_connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../login.php'); exit;
}

// Stats
$totalStudents    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM Users WHERE role='user'"))['c'];
$totalQuestions   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM Questions"))['c'] ?? 0;
$totalCareers     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM Careers"))['c'] ?? 0;
$totalAssessments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM AssessmentResults"))['c'] ?? 0;

// Top recommended careers
$topCareers = mysqli_query($conn,
    "SELECT career_title, COUNT(*) total
     FROM AssessmentResults
     GROUP BY career_title ORDER BY total DESC LIMIT 8");

// Monthly registrations (last 6 months)
$monthlyReg = mysqli_query($conn,
    "SELECT DATE_FORMAT(created_at,'%b %Y') month, COUNT(*) total
     FROM Users WHERE role='user'
     GROUP BY DATE_FORMAT(created_at,'%Y-%m')
     ORDER BY MIN(created_at) DESC LIMIT 6");

// Recent assessment results
$recentResults = mysqli_query($conn,
    "SELECT ar.created_at, u.first_name, u.last_name, u.email, ar.career_title, ar.score
     FROM AssessmentResults ar
     JOIN Users u ON ar.user_id = u.id
     ORDER BY ar.created_at DESC LIMIT 10");

// Career category distribution
$categoryDist = mysqli_query($conn,
    "SELECT category, COUNT(*) total FROM Careers WHERE category != '' GROUP BY category ORDER BY total DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reports | Admin</title>
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
      <h1>Reports & Analytics</h1>
      <p>Monitor system usage, student activity, and assessment results.</p>
    </div>

    <!-- Stats -->
    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-user-graduate"></i></div>
        <div class="stat-info"><span class="stat-number"><?= $totalStudents ?></span><span class="stat-label">Total Students</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon teal"><i class="fa-solid fa-chart-line"></i></div>
        <div class="stat-info"><span class="stat-number"><?= $totalAssessments ?></span><span class="stat-label">Assessments Taken</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-briefcase"></i></div>
        <div class="stat-info"><span class="stat-number"><?= $totalCareers ?></span><span class="stat-label">Career Paths</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-clipboard-question"></i></div>
        <div class="stat-info"><span class="stat-number"><?= $totalQuestions ?></span><span class="stat-label">Questions</span></div>
      </div>
    </div>

    <div class="two-col">

      <!-- Top careers -->
      <div class="admin-card">
        <div class="card-header"><h2><i class="fa-solid fa-trophy" style="color:var(--teal);margin-right:8px"></i>Top Recommended Careers</h2></div>
        <?php if (!$topCareers || mysqli_num_rows($topCareers)===0): ?>
          <p style="color:var(--muted);font-size:13px;padding:20px 0">No assessment results yet.</p>
        <?php else:
          $rows=[]; $max=1;
          while($r=mysqli_fetch_assoc($topCareers)){$rows[]=$r; if($r['total']>$max)$max=$r['total'];}
          foreach($rows as $tc): ?>
            <div class="bar-row">
              <span class="bar-label" title="<?= htmlspecialchars($tc['career_title']) ?>"><?= htmlspecialchars($tc['career_title']) ?></span>
              <div class="bar-track"><div class="bar-fill" style="width:<?= round(($tc['total']/$max)*100) ?>%"></div></div>
              <span class="bar-count"><?= $tc['total'] ?></span>
            </div>
          <?php endforeach; endif; ?>
      </div>

      <!-- Monthly registrations -->
      <div class="admin-card">
        <div class="card-header"><h2><i class="fa-solid fa-calendar" style="color:var(--teal);margin-right:8px"></i>Monthly Registrations</h2></div>
        <?php if (!$monthlyReg || mysqli_num_rows($monthlyReg)===0): ?>
          <p style="color:var(--muted);font-size:13px;padding:20px 0">No registration data yet.</p>
        <?php else: ?>
          <table class="admin-table">
            <thead><tr><th>Month</th><th>New Students</th><th>Visual</th></tr></thead>
            <tbody>
              <?php
              $mrows=[]; $mmax=1;
              while($mr=mysqli_fetch_assoc($monthlyReg)){$mrows[]=$mr; if($mr['total']>$mmax)$mmax=$mr['total'];}
              foreach($mrows as $mr): ?>
                <tr>
                  <td><?= $mr['month'] ?></td>
                  <td><span class="badge badge-admin"><?= $mr['total'] ?></span></td>
                  <td style="width:120px">
                    <div class="bar-track" style="height:6px">
                      <div class="bar-fill" style="width:<?= round(($mr['total']/$mmax)*100) ?>%"></div>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>

    </div>

    <!-- Career category distribution -->
    <?php if ($categoryDist && mysqli_num_rows($categoryDist) > 0): ?>
    <div class="admin-card">
      <div class="card-header"><h2><i class="fa-solid fa-layer-group" style="color:var(--teal);margin-right:8px"></i>Career Categories</h2></div>
      <?php
      $cats=[]; $cmax=1;
      while($cat=mysqli_fetch_assoc($categoryDist)){$cats[]=$cat; if($cat['total']>$cmax)$cmax=$cat['total'];}
      ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px">
        <?php foreach($cats as $cat): ?>
          <div style="background:var(--card2);border-radius:10px;padding:14px 16px">
            <div style="font-size:13px;font-weight:600;margin-bottom:8px"><?= htmlspecialchars($cat['category']) ?></div>
            <div class="bar-track" style="height:6px;margin-bottom:6px">
              <div class="bar-fill" style="width:<?= round(($cat['total']/$cmax)*100) ?>%"></div>
            </div>
            <div style="font-size:11px;color:var(--muted)"><?= $cat['total'] ?> career<?= $cat['total']>1?'s':'' ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Recent assessment results -->
    <div class="admin-card">
      <div class="card-header">
        <h2><i class="fa-solid fa-clock-rotate-left" style="color:var(--teal);margin-right:8px"></i>Recent Assessment Results</h2>
      </div>
      <table class="admin-table">
        <thead>
          <tr><th>Student</th><th>Email</th><th>Career Match</th><th>Score</th><th>Date</th></tr>
        </thead>
        <tbody>
          <?php if (!$recentResults || mysqli_num_rows($recentResults)===0): ?>
            <tr><td colspan="5" class="empty">No assessment results yet.</td></tr>
          <?php else: while($ra=mysqli_fetch_assoc($recentResults)): ?>
            <tr>
              <td><?= htmlspecialchars($ra['first_name'].' '.$ra['last_name']) ?></td>
              <td style="color:var(--muted);font-size:12px"><?= htmlspecialchars($ra['email']) ?></td>
              <td><?= htmlspecialchars($ra['career_title']) ?></td>
              <td><span class="badge badge-admin"><?= $ra['score'] ?></span></td>
              <td style="font-size:12px;color:var(--muted)"><?= date('M j, Y', strtotime($ra['created_at'])) ?></td>
            </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>
</body>
</html>
