<?php
$fn       = htmlspecialchars($_SESSION['first_name'] ?? 'Admin');
$ln       = htmlspecialchars($_SESSION['last_name']  ?? '');
$initials = strtoupper(substr($fn, 0, 1) . substr($ln, 0, 1));
$titles   = [
  'admin.php'            => 'Dashboard',
  'manage-users.php'     => 'Manage Users',
  'manage-questions.php' => 'Assessment Questions',
  'manage-careers.php'   => 'Career Data',
  'reports.php'          => 'Reports & Analytics',
];
$pageTitle = $titles[basename($_SERVER['PHP_SELF'])] ?? 'Admin';
?>
<div class="admin-topbar">
  <div class="topbar-left">
    <button class="sidebar-toggle" onclick="document.getElementById('adminSidebar').classList.toggle('open')" aria-label="Toggle sidebar">
      <i class="fa-solid fa-bars"></i>
    </button>
    <span class="topbar-title"><?= $pageTitle ?></span>
  </div>
  <div class="topbar-right">
    <div class="admin-avatar"><?= $initials ?></div>
    <span class="admin-name"><?= $fn ?></span>
  </div>
</div>
