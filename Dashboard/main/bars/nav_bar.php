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

        /* Role Indicator */
        .ets-role-indicator {
            position: absolute;
            top: 70px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
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
            .ets-role-indicator {
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
            <?php if ($user_role === 'hrm'): ?>
                <a class="ets-nav-link <?= ($current_page === 'index.php') ? 'active' : '' ?>"
                    href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/index.php">
                    <i class="fas fa-chart-pie"></i> <span>ScoreCard</span>
                </a>
            <?php else: ?>
                <a class="ets-nav-link <?= ($current_page === 'upload_csv.php') ? 'active' : '' ?>"
                    href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/upload_csv.php">
                    <i class="fas fa-home"></i> <span>Home</span>
                </a>
            <?php endif; ?>
            <a class="ets-nav-link <?= ($current_page === 'modify_column.php') ? 'active' : '' ?>"
                href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/modify_column.php">
                <i class="fas fa-edit"></i> <span>Modify CSV</span>
            </a>

            <a class="ets-nav-link <?= ($current_page === 'view_criteria.php') ? 'active' : '' ?>"
                href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/view_criteria.php">
                <i class="fas fa-tasks"></i> <span>Modify Criteria</span>
            </a>

            <a class="ets-nav-link ets-logout"
                href="/EMPLOYEE-TRACKING-SYSTEM/registration/logout.php">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>

        <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        if ($current_page !== 'hrm_assistant.php' && isset($user_role) && !empty($user_role)): ?>
            <div class="ets-role-indicator">
                Role: <?= strtoupper($user_role) ?>
            </div>
        <?php endif; ?>

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