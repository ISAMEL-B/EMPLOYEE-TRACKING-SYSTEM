<?php
// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
$user_role = $_SESSION['user_role'] ?? '';
?>

<!-- Navigation Bar -->
<div class="nav-container">
    <div class="nav-tabs" id="nav-tab" role="tablist">
        <div class="hamburger" id="hamburger"> <!-- Removed d-lg-none class -->
            <i class="fas fa-bars"></i>
        </div>

        <?php if ($user_role === 'hrm'): ?>
            <a class="nav-link <?php echo ($current_page === 'index.php') ? 'active' : ''; ?>"
               href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/index.php">
               ScoreCard
            </a>
        <?php else: ?>
            <a class="nav-link <?php echo ($current_page === 'upload_csv.php') ? 'active' : ''; ?>"
               href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/upload_csv.php">
               Home
            </a>
        <?php endif; ?>
        <a class="nav-link <?php echo ($current_page === 'modify_column.php') ? 'active' : ''; ?>"
           href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/modify_column.php">
           Modify CSV
        </a>

        <a class="nav-link <?php echo ($current_page === 'view_criteria.php') ? 'active' : ''; ?>"
           href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/view_criteria.php">
           Modify Criteria
        </a>

        <a class="nav-link" style="color:red;"
           href="/EMPLOYEE-TRACKING-SYSTEM/registration/logout.php">
           Logout
        </a>
    </div>
</div>