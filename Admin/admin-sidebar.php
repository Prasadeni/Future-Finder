<?php
$cp = basename($_SERVER['PHP_SELF']);
function aa($page, $cur) { return $page === $cur ? 'active' : ''; }
?>
<aside class="admin-sidebar" id="adminSidebar">
  <a href="admin.php" class="sidebar-brand">
    
    <i class="fa-solid fa-user-shield admin-icon"></i>
    <div>
      <span class="brand-title">Future Finder</span>
      <span class="brand-sub">Admin Panel</span>
    </div>
  </a>

  <nav class="sidebar-nav">
    <div class="sidebar-section">Overview</div>
    <a href="admin.php" class="nav-item <?= aa('admin.php', $cp) ?>">
      <i class="fa-solid fa-gauge-high"></i> Dashboard
    </a>

    <div class="sidebar-section">Management</div>
    <a href="manage-users.php" class="nav-item <?= aa('manage-users.php', $cp) ?>">
      <i class="fa-solid fa-users"></i> Manage Users
    </a>
    <a href="manage-questions.php" class="nav-item <?= aa('manage-questions.php', $cp) ?>">
      <i class="fa-solid fa-clipboard-question"></i> Assessment Questions
    </a>
    <a href="manage-careers.php" class="nav-item <?= aa('manage-careers.php', $cp) ?>">
      <i class="fa-solid fa-briefcase"></i> Career Data
    </a>

    <div class="sidebar-section">Analytics</div>
    <a href="reports.php" class="nav-item <?= aa('reports.php', $cp) ?>">
      <i class="fa-solid fa-chart-bar"></i> Reports
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="../logout.php" class="nav-item logout">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
  </div>
</aside>