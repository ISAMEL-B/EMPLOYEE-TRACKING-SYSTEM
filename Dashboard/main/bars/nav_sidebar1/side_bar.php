<?php
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-logo-container">
    <div class="sidebar-logo"></div>
    <div class="sidebar-title">Dashboard</div>
  </div>
  <div class="sidebar-menu">
    <ul>
      <li class="<?php echo ($current_page == 'upload_csv.php') ? 'active' : ''; ?>">
        <a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/upload_csv.php">
          <i class="fas fa-upload"></i>
          <span class="menu-text">Upload</span>
        </a>
      </li>
      <li class="<?php echo ($current_page == 'approve.php') ? 'active' : ''; ?>">
        <a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/approve/approve.php">
          <i class="fas fa-check-circle"></i>
          <span class="menu-text">Approvals</span>
        </a>
      </li>
      <li class="<?php echo ($current_page == 'modify_column.php') ? 'active' : ''; ?>">
        <a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/head/main/head/modify_column.php">
          <i class="fas fa-edit"></i>
          <span class="menu-text">Edit</span>
        </a>
      </li>
      <li class="<?php echo ($current_page == 're_registration.php') ? 'active' : ''; ?>">
        <a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/re_registration.php">
          <i class="fas fa-edit"></i>
          <span class="menu-text">Profile</span>
        </a>
      </li>
    </ul>
  </div>
</div>