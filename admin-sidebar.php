<?php
$cp = basename($_SERVER['PHP_SELF']);
function aa($page, $cur) { return $page === $cur ? 'active' : ''; }

// Determine base path — admin sub-pages are in Admin/ folder
$base = (strpos($cp, 'admin.php') !== false) ? '' : '../';
?>
<aside class="admin-sidebar" id="adminSidebar">
  <a href="<?= $base ?>admin.php" class="sidebar-brand">
    <img src="<?= $base ?>Images/Logo.jpg" alt="Future Finder">
    <div>
      <span class="brand-title">Future Finder</span>
      <span class="brand-sub">Admin Panel</span>
    </div>
  </a>

  <nav class="sidebar-nav">
    <div class="sidebar-section">Overview</div>
    <a href="<?= $base ?>admin.php" class="nav-item <?= aa('admin.php', $cp) ?>">
      <i class="fa-solid fa-gauge-high"></i> Dashboard
    </a>

    <div class="sidebar-section">Management</div>
    <a href="<?= $base ?>Admin/manage-users.php" class="nav-item <?= aa('manage-users.php', $cp) ?>">
      <i class="fa-solid fa-users"></i> Manage Users
    </a>
    <a href="<?= $base ?>Admin/manage-questions.php" class="nav-item <?= aa('manage-questions.php', $cp) ?>">
      <i class="fa-solid fa-clipboard-question"></i> Assessment Questions
    </a>
    <a href="<?= $base ?>Admin/manage-careers.php" class="nav-item <?= aa('manage-careers.php', $cp) ?>">
      <i class="fa-solid fa-briefcase"></i> Career Data
    </a>

    <div class="sidebar-section">Analytics</div>
    <a href="<?= $base ?>Admin/reports.php" class="nav-item <?= aa('reports.php', $cp) ?>">
      <i class="fa-solid fa-chart-bar"></i> Reports
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="<?= $base ?>logout.php" class="nav-item logout">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
  </div>
</aside>
