<?php
// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
$user_role = $_SESSION['user_role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Navigation Bar Styles */
        .ets-nav-container {
            background-color: #2e3192;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            position: fixed;
            top: 0;
            left: 250px;
            width: calc(100% - 250px);
            height: 70px;
            transition: all 0.3s ease;
        }

        /* When sidebar is collapsed */
        .main-sidebar.collapsed~.ets-nav-container {
            left: 80px;
            width: calc(100% - 80px);
        }

        /* Role Indicator Styling */
        .role-indicator {

            /* Role Indicator */
            position: fixed;
            top: 20px;
            /* Adjust based on navbar height */
            left: 270px;
            /* Sidebar width (280px) + 20px spacing */
            /* background-color: #4CAF50; */
            background-color:rgb(68, 40, 250);
            /* MUST green */
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-left: 3px solid var(--must-yellow);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            transition: all 0.3s ease;
        }

        /* FontAwesome icon styling */
        .role-indicator::before {
            content: "\f007";
            /* User icon */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 12px;
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .role-indicator {
                left: 20px;
                /* When sidebar is collapsed */
            }
        }

        @media (max-width: 576px) {
            .role-indicator {
                font-size: 11px;
                padding: 4px 8px;
                top: 10px;
            }

            .role-indicator::before {
                font-size: 10px;
            }
        }

        /* Navigation Tabs Container */
        .ets-nav-tabs {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 20px;
            height: 100%;
            position: relative;
        }

        /* Hamburger Menu Icon */
        .ets-hamburger {
            position: absolute;
            left: 15px;
            color: white;
            background-color: transparent;
            font-size: 20px;
            cursor: pointer;
            z-index: 1001;
            padding-top: 5%;
            transition: transform 0.3s ease;
            display: none;
            border: none;
        }

        .ets-hamburger:hover {
            color: #FFEB3B;
        }

        /* Navigation Links */
        .ets-nav-link {
            background-color: transparent;
            border: none;
            padding: 10px 20px;
            margin: 0 5px;
            cursor: pointer;
            font-size: 16px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            /* Prevent items from shrinking */
        }

        /* Active Navigation Link Styling */
        .ets-nav-link.active {
            background-color: #4CAF50;
            color: white !important;
            border-bottom: 3px solid #FFEB3B;
            font-weight: bold;
        }

        /* Hover effect for nav links */
        .ets-nav-link:not(.active):hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #FFEB3B;
        }

        /* Logout Button Special Styling */
        .ets-nav-link.ets-logout {
            color: #e74c3c;
            font-weight: bold;
        }

        .ets-nav-link.ets-logout:hover {
            background-color: rgba(231, 76, 60, 0.1);
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .ets-nav-container {
                left: 0;
                width: 100%;
                overflow-x: auto;
                /* Enable horizontal scrolling */
                -webkit-overflow-scrolling: touch;
                /* Smooth scrolling on iOS */
            }

            .main-sidebar.mobile-show~.ets-nav-container {
                left: 250px;
                width: calc(100% - 250px);
            }

            .main-sidebar.collapsed.mobile-show~.ets-nav-container {
                left: 80px;
                width: calc(100% - 80px);
            }

            .ets-hamburger {
                display: block;
                position: fixed;
                /* Keep hamburger fixed during scroll */
                left: 15px;
                top: 20px;
            }

            /* Adjust nav tabs container */
            .ets-nav-tabs {
                justify-content: flex-start;
                /* Align items to start */
                padding-left: 60px;
                /* Make space for hamburger */
                padding-right: 20px;
                width: max-content;
                /* Allow container to expand beyond viewport */
                min-width: 100%;
                /* Ensure it takes full width */
            }

            /* Keep both icon and text visible */
            .ets-nav-link span {
                display: inline !important;
                /* Force text to be visible */
            }

            /* Slightly reduce padding on mobile */
            .ets-nav-link {
                padding: 10px 15px;
            }
        }

        @media (max-width: 768px) {
            .role-indicator {
                display: none;
            }

            /* Make links more compact on very small screens */
            .ets-nav-link {
                padding: 10px 12px;
                font-size: 14px;
            }

            .ets-nav-link i {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <div class="ets-nav-container">
        <button class="ets-hamburger" id="ets-hamburger" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div class="ets-nav-tabs" id="ets-nav-tab" role="tablist">
            <div class="role-indicator">
                Welcome our <?php echo strtoupper(htmlspecialchars($_SESSION['user_role'] ?? 'guest'));  ?>
            </div>
            <?php if ($_SESSION['user_role'] !== 'hrm' && $_SESSION['user_role'] !== 'staff'): ?>
                <a class="ets-nav-link <?= ($current_page === 'upload_csv.php') ? 'active' : '' ?>"
                    href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/upload_csv.php">
                    <i class="fas fa-home"></i> <span>Home</span>
                </a>
                <a class="ets-nav-link <?= ($current_page === 'approve.php') ? 'active' : '' ?>"
                    href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/approve/approve.php">
                    <i class="fa fa-check-circle"></i> <span>Approve</span>
                </a>
            <?php endif; ?>
            <?php if ($_SESSION['user_role'] === 'hrm'): ?>
                <a class="ets-nav-link <?= ($current_page === 'index.php') ? 'active' : '' ?>"
                    href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/index.php">
                    <i class="fas fa-chart-pie"></i> <span>ScoreCard</span>
                </a>
                <a class="ets-nav-link <?= ($current_page === 'approve.php') ? 'active' : '' ?>"
                    href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/approve/approve.php">
                    <i class="fa fa-check-circle"></i> <span>Approve</span>
                </a>

                <a class="ets-nav-link <?= ($current_page === 'view_criteria.php') ? 'active' : '' ?>"
                    href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/head/view_criteria.php">
                    <i class="fas fa-tasks"></i> <span>Modify Criteria</span>
                </a>

            <?php endif; ?>

            <!-- Logout is visible to everyone -->
            <a class="ets-nav-link ets-logout"
                href="/EMPLOYEE-TRACKING-SYSTEM/registration/logout.php">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>
    </div>

    <script>
        // Initialize navbar on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            // Set active nav link
            setActiveNavLink();

            // Handle responsive behavior
            handleNavbarResponsive();

            // Add window resize listener
            window.addEventListener('resize', handleNavbarResponsive);
        });

        // Set active navigation link
        function setActiveNavLink() {
            const currentPage = '<?= $current_page ?>';
            if (currentPage) {
                document.querySelectorAll('.ets-nav-link').forEach(link => {
                    if (link.getAttribute('href').includes(currentPage)) {
                        link.classList.add('active');
                    }
                });
            }
        }

        // Handle responsive behavior
        function handleNavbarResponsive() {
            const isMobile = window.innerWidth <= 992;
            const hamburger = document.getElementById('ets-hamburger');

            if (isMobile) {
                hamburger.style.display = 'block';
            } else {
                hamburger.style.display = 'none';
            }
        }

        // Toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.querySelector('.main-sidebar');
            const isMobile = window.innerWidth <= 992;

            if (isMobile) {
                // Mobile behavior - toggle visibility
                sidebar.classList.toggle('mobile-show');
            } else {
                // Desktop behavior - toggle collapsed state
                sidebar.classList.toggle('collapsed');
            }
        }
    </script>

</body>

</html>