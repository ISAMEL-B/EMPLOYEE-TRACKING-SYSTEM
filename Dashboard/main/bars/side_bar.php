<?php
// Get current page name (handles URLs with parameters)
$current_uri = $_SERVER['REQUEST_URI'];
$current_page = basename(parse_url($current_uri, PHP_URL_PATH));

// Define menu structure with parent-child relationships
$menu_structure = [
    'Dashboard' => [
        'pages' => ['index.php', 'index2.php', 'index3.php', 'individual_view.php', 'hrm_assistant.php'],
        'icon' => 'fa-tachometer-alt'
    ],
    'Update' => [
        'pages' => ['upload_csv.php', 'approve.php', 'view_criteria.php', 'modify_db.php'],
        'icon' => 'fa-edit'
    ],
    'Manage' => [
        'pages' => ['individual_view.php', 'staff_profile.php', 'for_staff_profile.php', 're_register.php', 'hrm_profile.php', 'about_us.php'],
        'icon' => 'fa-info-circle'
    ],
    'Authentication' => [
        'pages' => ['register.php', 'logout.php', 'lock_screen.php', 'password_recovery.php'],
        'icon' => 'fa-lock'
    ]
];

// Determine active parent menu and if current page is in submenu
$active_parent = '';
$is_submenu_active = false;

foreach ($menu_structure as $parent => $data) {
    if (in_array($current_page, $data['pages'])) {
        $active_parent = $parent;
        $is_submenu_active = true;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Tracking System</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <link rel="stylesheet" href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/components/src/fontawesome/css/all.min.css">

    <style>
        /* Ensure active parent stays open */
        .treeview.active .treeview-menu {
            display: block !important;
            max-height: 300px !important;
            opacity: 1 !important;
        }

        /* Prevent collapse animation on page load */
        .main-sidebar.collapsed .treeview.active .treeview-menu {
            display: block !important;
        }

        /* ===== ACTIVE SUBMENU STYLING ===== */
        .treeview-menu li a.active-submenu {
            background-color: #FFF59D !important;
            /* Lighter yellow */
            color: #000 !important;
            font-weight: 600;
            border-left: 3px solid #4CAF50;
            /* Green accent */
            position: relative;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
        }

        /* Arrow indicator */
        .treeview-menu li a.active-submenu::after {
            content: "→";
            position: absolute;
            right: 15px;
            color: #4CAF50;
            font-weight: bold;
            animation: bounce 0.5s infinite alternate;
        }

        @keyframes bounce {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(3px);
            }
        }

        /* ///===== ACTIVE SUBMENU STYLING =====/// */

        /* Sidebar Styles */
        .main-sidebar {
            background-color: #4caf50;
            /* Green background for sidebar */
            color: #ffffff;
            /* White text color */
            width: 250px;
            /* Fixed width for sidebar */
            height: 100vh;
            /* Full height */
            position: fixed;
            /* Fixed position */
            top: 0;
            left: 0;
            overflow-y: auto;
            /* Scrollable if content overflows */
            transition: width 0.3s;
            /* Smooth transition for width */
        }

        .main-sidebar {
            width: 250px;
            background-color: #4caf50;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: width 0.3s;
            overflow-x: hidden;
            z-index: 1000;
        }

        .collapsed {
            width: 80px;
            /* Reduced width when collapsed */
        }

        .logo-box {
            display: flex;
            /* Flexbox for alignment */
            justify-content: space-between;
            /* Space between logo and hamburger */
            align-items: center;
            /* Center items vertically */
            padding: 15px;
            /* Padding around the logo box */
            background-color: #388e3c;
            /* Darker green for logo area */
        }

        .logo img {
            max-height: 40px;
            /* Logo size */
        }

        .hamburger {
            background: none;
            border: none;
            color: #ffffff;
            cursor: pointer;
            font-size: 24px;
            display: none;
            /* Hide by default */
        }

        /* Show hamburger on small screens (max-width: 768px) */
        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }
        }

        .sidebar-menu {
            list-style: none;
            /* Remove default list styles */
            padding: 0;
            /* Remove padding */
            margin: 0;
            /* Remove margin */
        }

        .sidebar-menu>li {
            position: relative;
            /* Position relative for dropdowns */
        }

        .sidebar-menu>li>a {
            display: flex;
            /* Flexbox for alignment */
            align-items: center;
            /* Center items vertically */
            padding: 15px 20px;
            /* Padding for links */
            color: #ffffff;
            /* White text color */
            text-decoration: none;
            /* Remove underline */
            transition: background 0.3s;
            /* Smooth background transition */
        }

        .collapsed .sidebar-menu>li>a {
            justify-content: center;
            /* Center icons when collapsed */
        }

        .collapsed .sidebar-menu>li>a span {
            display: none;
            /* Hide text when collapsed */
        }

        .sidebar-menu>li>a:hover {
            background-color: #388e3c;
            /* Darker green background on hover */
        }

        /* Submenu Styles */
        .treeview-menu {
            display: none;
            /* Hide dropdowns by default */
            list-style: none;
            /* Remove list styles */
            padding-left: 20px;
            /* Indent dropdown items */
            transition: max-height 0.5s ease-in-out, opacity 0.5s ease-in-out;
            /* Smooth transition */
            max-height: 0;
            /* Start with max-height of 0 */
            overflow: hidden;
            /* Hide overflow */
        }

        .treeview.active .treeview-menu {
            display: block;
            /* Show dropdown when active */
            max-height: 300px;
            /* Set a max-height for the dropdown */
            opacity: 1;
            /* Make it fully visible */
        }

        /* ===== COLOR PALETTE ===== */
        :root {
            --primary: #2c3e50;
            /* Dark blue-gray - for text */
            --secondary: #3498db;
            /* Vibrant blue - for accents */
            --active-bg: #FFEB3B;
            /* Yellow - active parent */
            --active-text: #000;
            /* Black - active text */
            --submenu-bg: #e6e8f4;
            /* Light gray-blue - submenu background */
            --submenu-hover: #d1d5e8;
            /* Slightly darker gray-blue */
            --sidebar-bg: #4caf50;
            /* Green - sidebar background */
            --white: #ffffff;
            /* Pure white */
        }

        /* ===== ACTIVE PARENT MENU STYLING ===== */
        .treeview.active>a {
            background-color: var(--active-bg) !important;
            color: var(--active-text) !important;
            font-weight: 600;
        }

        /* Dropdown arrow color */
        .treeview.active>a .pull-right-container i {
            color: var(--active-text) !important;
        }

        /* ===== SUBMENU STYLING ===== */
        .treeview-menu {
            background-color: var(--submenu-bg);
            padding: 5px 0;
            border-radius: 0 0 4px 4px;
            border-left: 3px solid var(--secondary);
            /* Blue accent border */
        }

        .treeview-menu li a {
            color: var(--primary) !important;
            padding: 8px 15px 8px 35px;
            transition: all 0.2s;
        }

        .treeview-menu li a:hover {
            background-color: var(--submenu-hover) !important;
            padding-left: 38px;
            color: var(--secondary) !important;
            /* Blue text on hover */
        }

        /* Submenu animation */
        .treeview.active .treeview-menu {
            display: block;
            max-height: 300px;
            opacity: 1;
        }

        /* Sidebar consistency */
        .main-sidebar {
            background-color: var(--sidebar-bg);
        }

        .sidebar-menu>li>a {
            color: var(--white);
        }

        /* ///===== SUBMENU STYLING =====/// */

        .treeview-menu li {
            background-color: #d5d8e8;
            /* Match sidebar background */
            border-radius: 4px;
            /* Rounded corners */
            margin: 5px 0;
            /* Spacing between items */
            transition: background 0.3s;
            /* Smooth background transition */
            transform: translateY(20px);
            /* Start from below */
            opacity: 0;
            /* Start as invisible */
            animation: slideIn 0.5s forwards;
            /* Animation for sliding in */
        }

        .treeview.active .treeview-menu li {
            opacity: 1;
            /* Make it fully visible */
        }

        .treeview-menu li a {
            display: flex;
            /* Flexbox for alignment */
            align-items: center;
            /* Center items vertically */
            padding: 10px 15px;
            /* Padding for links */
            color: #ffffff;
            /* White text color */
            text-decoration: none;
            /* Remove underline */
            border-radius: 4px;
            /* Rounded corners */
        }

        .treeview-menu li a:hover {
            background-color: #388e3c;
            /* Darker green on hover */
        }

        .treeview>a .pull-right-container {
            margin-left: auto;
            /* Push right icon to the end */
            transition: transform 0.3s ease;
            /* Smooth rotation */
        }

        .treeview.active>a .pull-right-container {
            transform: rotate(90deg);
            /* Rotate icon when active */
        }

        /* Keyframe Animation for Slide In */
        @keyframes slideIn {
            0% {
                transform: translateY(20px);
                /* Start from below */
                opacity: 0;
                /* Start as invisible */
            }

            100% {
                transform: translateY(0);
                /* End at original position */
                opacity: 1;
                /* End as visible */
            }
        }

        /* Adjust spacing between icons and text */
        .treeview-menu li a i {
            margin-right: 10px;
            /* Space between icon and text */
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .main-sidebar {
                width: 200px;
                /* Adjust width for smaller screens */
            }
        }

        /* RESPONSIVENESS */

        /* Mobile-specific styles */
        @media (max-width: 992px) {
            .main-sidebar {
                left: -250px;
                transition: left 0.3s ease, width 0.3s ease;
            }

            .main-sidebar.collapsed {
                left: -80px;
                width: 80px;
            }

            .main-sidebar.mobile-show {
                left: 0;
                width: 250px;
            }

            .main-sidebar.collapsed.mobile-show {
                left: 0;
                width: 80px;
            }
        }

        /* Mobile hidden state */
        .main-sidebar.mobile-hidden {
            left: -250px;
            transition: left 0.3s ease;
        }

        /* Desktop collapsed state */
        .main-sidebar.collapsed {
            width: 80px;
            transition: width 0.3s ease;
        }

        /* Ensure proper transitions */
        .main-sidebar {
            transition: left 0.3s ease, width 0.3s ease;
        }

        @media (min-width: 993px) {
            .main-sidebar:not(.collapsed) {
                width: 250px;
                left: 0;
            }

            .main-sidebar.collapsed {
                width: 80px;
                left: 0;
            }
        }

        @media (max-width: 992px) {
            .main-sidebar:not(.mobile-hidden) {
                left: 0;
                width: 250px;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <aside class="main-sidebar">
        <div class="logo-box">
            <div class="logo">
                <img src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png" alt="MUST Logo">
            </div>
            <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">
                <i class="fa fa-bars"></i>
            </button>
        </div>
        <section class="sidebar position-relative">
            <ul class="sidebar-menu" data-widget="tree">
                <!-- Dashboard -->
                <?php if ($_SESSION['user_role'] === 'hrm'): ?>
                    <li class="treeview <?= ($active_parent === 'Dashboard') ? 'active' : '' ?>">
                        <a href="#" class="toggle">
                            <i class="fa fa-tachometer-alt"></i>
                            <span style="margin-left: 10px;">Dashboard</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/index.php" class="<?= ($current_page == 'index.php') ? 'active-submenu' : '' ?>"><i class="fa fa-chart-line"></i> General Progress</a></li>
                            <?php if ($_SESSION['user_role'] === 'hrm'): ?>
                                <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/index2.php" class="<?= ($current_page == 'index2.php') ? 'active-submenu' : '' ?>"><i class="fa fa-university"></i> Faculty Progress</a></li>
                                <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/index3.php" class="<?= ($current_page == 'index3.php') ? 'active-submenu' : '' ?>"><i class="fa fa-building"></i> Department Progress</a></li>
                                <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/individual_view.php" class="<?= ($current_page == 'individual_view.php') ? 'active-submenu' : '' ?>"><i class="fa fa-user"></i> Individual Progress</a></li>
                                <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/decisions/hrm_assistant.php" class="<?= ($current_page == 'hrm_assistant.php') ? 'active-submenu' : '' ?>"><i class="fa fa-user-cog"></i> HRM Assistant </a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Update -->
                <?php if ($_SESSION['user_role'] !== 'staff'): ?>
                    <li class="treeview <?= ($active_parent === 'Update') ? 'active' : '' ?>">
                        <a href="#" class="toggle">
                            <i class="fa fa-edit"></i>
                            <span style="margin-left: 10px;">Update</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/upload_csv.php" class="<?= ($current_page == 'upload_csv.php') ? 'active-submenu' : '' ?>"><i class="fa fa-file-upload"></i> CSV Upload</a></li>
                            <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/approve/approve.php" class="<?= ($current_page == 'approve.php') ? 'active-submenu' : '' ?>"><i class="fa fa-check-circle"></i> Approve</a></li>
                            <?php if ($_SESSION['user_role'] === 'hrm'): ?>
                                <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/view_criteria.php" class="<?= ($current_page == 'view_criteria.php') ? 'active-submenu' : '' ?>"><i class="fa fa-edit"></i> View | Edit Criteria</a></li>
                                <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/modify_column.php" class="<?= ($current_page == 'modify_column.php') ? 'active-submenu' : '' ?>"><i class="fa fa-database"></i> Modify DB Tables</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Manage -->
                <li class="treeview <?= ($active_parent === 'Manage') ? 'active' : '' ?>">
                    <a href="#" class="toggle">
                        <i class="fa fa-info-circle"></i>
                        <span style="margin-left: 10px;">Manage</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if ($_SESSION['user_role'] !== 'hrm'): ?>
                            <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/staff/for_staff_profile.php" class="<?= ($current_page == 'for_staff_profile.php') ? 'active-submenu' : '' ?>"><i class="fas fa-user"></i> My Profile</a></li>
                        <?php endif; ?>

                        <?php if ($_SESSION['user_role'] === 'hrm'): ?>
                            <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/staff_profile.php" class="<?= ($current_page == 'staff_profile.php') ? 'active-submenu' : '' ?>"><i class="fas fa-user"></i> Staff Profile</a></li>
                            <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/re_registration.php" class="<?= ($current_page == 're_registration.php') ? 'active-submenu' : '' ?>"><i class="fa fa-file-upload"></i>Update Profile</a></li>
                            <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/hrm_profile.php" class="<?= ($current_page == 'hrm_profile.php') ? 'active-submenu' : '' ?>"><i class="fa fa-file-upload"></i>My Profile</a></li>
                            <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/about_us.php" class="<?= ($current_page == 'about_us.php') ? 'active-submenu' : '' ?>"><i class="fa fa-file-upload"></i>About Us</a></li>
                        <?php endif; ?>
                    </ul>
                </li>

                <!-- Authentication (Always visible) -->
                <li class="treeview <?= ($active_parent === 'Authentication') ? 'active' : '' ?>">
                    <a href="#" class="toggle">
                        <i class="fa fa-lock"></i>
                        <span style="margin-left: 10px;">Authentication</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if ($_SESSION['user_role'] === 'hrm'): ?>
                            <li><a href="/EMPLOYEE-TRACKING-SYSTEM/registration/register.php" class="d-light <?= ($current_page == 'register.php') ? 'active-submenu' : '' ?>"><i class="fas fa-user-plus"></i>Register</a></li>
                        <?php endif; ?>
                        <li><a href="/EMPLOYEE-TRACKING-SYSTEM/registration/logout.php" class="d-light <?= ($current_page == 'logout.php') ? 'active-submenu' : '' ?>"><i class="fas fa-sign-in-alt"></i> Log Out</a></li>
                        <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/staff/lock_screen.php" class="d-light <?= ($current_page == 'lock_screen.php') ? 'active-submenu' : '' ?>"><i class="fas fa-lock"></i> Lockscreen</a></li>
                        <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/staff/password_recovery.php" class="d-light <?= ($current_page == 'password_recovvery.php') ? 'active-submenu' : '' ?>"><i class="fas fa-key"></i> Recover password</a></li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>


    <script>
        // DOM Ready Handler
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize sidebar state
            initSidebar();

            // Set active menu items based on current page
            setActiveMenu();

            // Setup event listeners
            setupEventListeners();

            // Initialize hamburger icon
            initHamburger();
        });

        function initHamburger() {
            const hamburger = document.getElementById('hamburger');
            if (hamburger) {
                hamburger.addEventListener('click', function() {
                    toggleSidebar();
                });
            }
        }

        // Initialize sidebar state
        function initSidebar() {
            const sidebar = document.querySelector('.main-sidebar');
            const isMobile = window.innerWidth <= 992;

            if (isMobile) {
                sidebar.classList.add('mobile-hidden');
                sidebar.classList.remove('collapsed');
            } else {
                sidebar.classList.remove('mobile-hidden');
            }
        }

        // Set active menu items
        function setActiveMenu() {
            const currentPage = '<?= $current_page ?>';
            if (currentPage) {
                const activeLinks = document.querySelectorAll(`a[href*="${currentPage}"]`);
                activeLinks.forEach(link => {
                    link.classList.add('active-submenu');
                    const parentMenu = link.closest('.treeview');
                    if (parentMenu) {
                        parentMenu.classList.add('active');
                        const submenu = parentMenu.querySelector('.treeview-menu');
                        if (submenu) {
                            submenu.style.display = 'block';
                            submenu.style.maxHeight = 'none';
                            submenu.style.opacity = '1';
                        }
                    }
                });
            }
        }

        // Setup event listeners
        function setupEventListeners() {
            // Toggle submenus
            const toggles = document.querySelectorAll('.toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parentLi = this.parentElement;
                    const submenu = parentLi.querySelector('.treeview-menu');

                    // Close all other open submenus
                    document.querySelectorAll('.treeview.active').forEach(active => {
                        if (active !== parentLi) {
                            active.classList.remove('active');
                            active.querySelector('.treeview-menu').style.maxHeight = null;
                        }
                    });

                    // Toggle current submenu
                    parentLi.classList.toggle('active');
                    if (parentLi.classList.contains('active')) {
                        submenu.style.maxHeight = submenu.scrollHeight + "px";
                    } else {
                        submenu.style.maxHeight = null;
                    }
                });
            });

            // Window resize handler
            window.addEventListener('resize', handleResponsiveBehavior);
        }

        // Handle responsive behavior
        function handleResponsiveBehavior() {
            const sidebar = document.querySelector('.main-sidebar');
            const isMobile = window.innerWidth <= 992;

            if (isMobile) {
                if (!sidebar.classList.contains('mobile-hidden')) {
                    sidebar.classList.add('mobile-hidden');
                }
                sidebar.classList.remove('collapsed');
            } else {
                sidebar.classList.remove('mobile-hidden');
                sidebar.style.left = '0';
            }
        }

        // Global toggle function for hamburger
        function toggleSidebar() {
            const sidebar = document.querySelector('.main-sidebar');
            const isMobile = window.innerWidth <= 992;

            if (isMobile) {
                // Mobile toggle
                sidebar.classList.toggle('mobile-hidden');

                // Close all submenus when hiding sidebar
                if (sidebar.classList.contains('mobile-hidden')) {
                    document.querySelectorAll('.treeview.active').forEach(active => {
                        active.classList.remove('active');
                        active.querySelector('.treeview-menu').style.maxHeight = null;
                    });
                }
            } else {
                // Desktop toggle
                sidebar.classList.toggle('collapsed');

                // Close all submenus when collapsing
                if (sidebar.classList.contains('collapsed')) {
                    document.querySelectorAll('.treeview.active').forEach(active => {
                        active.classList.remove('active');
                        active.querySelector('.treeview-menu').style.maxHeight = null;
                    });
                }
            }
        }

        // Make the function available globally
        window.toggleSidebar = toggleSidebar;
    </script>
</body>

</html>