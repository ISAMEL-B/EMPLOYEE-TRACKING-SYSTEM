<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="nav_side_bar.css1">
    <style>
        /* Main Content Area Styling */
        body {
            background-color: rgb(202, 241, 226);
            /* Light gray background for the whole page */
            overflow: hidden;

        }

        /* Sidebar Styles */
        .sidebar {
            width: 240px;
            background-color: #4CAF50;
            /* Green background */
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        /* Collapsed Sidebar */
        .sidebar.collapsed {
            width: 80px;
            overflow-x: hidden;
        }

        /* Logo Container */
        .sidebar-logo-container {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .sidebar-logo-container {
            padding: 15px 10px;
        }

        /* MUST Logo */
        .sidebar-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background-color: white;
            border-radius: 50%;
            background-image: url("/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png");
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            border: 3px solid white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .sidebar-logo {
            width: 50px;
            height: 50px;
            background-size: 80%;
        }

        /* Sidebar Title */
        .sidebar-title {
            margin-top: 15px;
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .sidebar-title {
            display: none;
        }

        /* Menu Items */
        .sidebar-menu {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            padding: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        /* Active Menu Item */
        .sidebar-menu li.active a {
            background-color: #FFEB3B;
            /* Yellow background */
            color: #333;
            /* Dark text */
            font-weight: bold;
        }

        /* Hover Effect */
        .sidebar-menu li a:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Icons */
        .sidebar-menu li a i {
            font-size: 1.2rem;
            margin-right: 15px;
            min-width: 24px;
            text-align: center;
        }

        /* Menu Text */
        .sidebar-menu li a .menu-text {
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
            margin-left: 0;
        }

        /* Navigation Bar Adjustment */
        .nav-container {
            left: 240px;
            width: calc(100% - 240px);
            transition: left 0.3s ease, width 0.3s ease;
        }

        .sidebar.collapsed+.nav-container {
            left: 80px;
            width: calc(100% - 80px);
        }

        /* Main Content Adjustment */
        .main-content {
            margin-left: 240px;
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed+.nav-container+.main-content {
            margin-left: 80px;
        }

        /* Responsive Behavior */
        @media (max-width: 992px) {
            .sidebar {
                left: -240px;
            }

            .sidebar.show {
                left: 0;
            }

            .sidebar.collapsed {
                left: -80px;
            }

            .sidebar.collapsed.show {
                left: 0;
                width: 80px;
            }

            .nav-container {
                left: 0;
                width: 100%;
            }

            .sidebar.show+.nav-container {
                left: 240px;
                width: calc(100% - 240px);
            }

            .sidebar.collapsed.show+.nav-container {
                left: 80px;
                width: calc(100% - 80px);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.show+.nav-container+.main-content {
                margin-left: 240px;
            }

            .sidebar.collapsed.show+.nav-container+.main-content {
                margin-left: 80px;
            }
        }

        /* Navigation Bar Styles */
        .nav-container {
            background-color: #2c3e50;
            /* Dark blue-gray background */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            position: fixed;
            top: 0;
            left: 240px;
            /* Matches sidebar width */
            width: calc(100% - 240px);
            height: 60px;
            transition: left 0.3s ease, width 0.3s ease;
        }

        /* When sidebar is collapsed */
        .sidebar.collapsed+.nav-container {
            left: 80px;
            width: calc(100% - 80px);
        }

        /* Navigation Tabs Container */
        .nav-tabs {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 20px;
            height: 100%;
            position: relative;
        }

        /* Hamburger Menu Icon */
        .hamburger {
            position: absolute;
            left: 15px;
            color: white;
            font-size: 20px;
            cursor: pointer;
            z-index: 1001;
            transition: transform 0.3s ease;
        }

        .hamburger:hover {
            color: #FFEB3B;
            /* Yellow hover */
        }

        /* Navigation Links */
        .nav-link {
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
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #FFEB3B;
            /* Yellow text on hover */
        }

        /* Active Navigation Link */
        .nav-link.active {
            background-color: #4CAF50;
            /* Green background */
            color: white;
            border-bottom: 3px solid #FFEB3B;
            /* Yellow underline */
        }

        /* Logout Button Special Styling */
        .nav-link.logout {
            color: #e74c3c;
            /* Red color for logout */
            font-weight: bold;
        }

        .nav-link.logout:hover {
            background-color: rgba(231, 76, 60, 0.1);
        }

        /* Role Indicator */
        .role-indicator {
            position: absolute;
            top: 70px;
            right: 20px;
            background-color: #4CAF50;
            /* Green background */
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
            .nav-container {
                left: 0;
                width: 100%;
            }

            .sidebar.show+.nav-container {
                left: 240px;
                width: calc(100% - 240px);
            }

            .sidebar.collapsed.show+.nav-container {
                left: 80px;
                width: calc(100% - 80px);
            }

            /* Adjust nav links for mobile */
            .nav-tabs {
                justify-content: flex-start;
                overflow-x: auto;
                padding-left: 50px;
            }

            .nav-link {
                padding: 10px 15px;
                font-size: 14px;
                white-space: nowrap;
            }
        }

        /* Animation for active state */
        @keyframes navLinkPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .nav-link.active {
            animation: navLinkPulse 0.5s ease;
        }

        /* Main Content Area Styling */
        body {
            background-color: rgb(202, 241, 226);
            /* Light gray background for the whole page */
            overflow: hidden;

        }

        /* Sidebar Styles */
        .sidebar {
            width: 240px;
            background-color: #4CAF50;
            /* Green background */
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        /* Collapsed Sidebar */
        .sidebar.collapsed {
            width: 80px;
            overflow-x: hidden;
        }

        /* Logo Container */
        .sidebar-logo-container {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .sidebar-logo-container {
            padding: 15px 10px;
        }

        /* MUST Logo */
        .sidebar-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background-color: white;
            border-radius: 50%;
            background-image: url("/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png");
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            border: 3px solid white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .sidebar-logo {
            width: 50px;
            height: 50px;
            background-size: 80%;
        }

        /* Sidebar Title */
        .sidebar-title {
            margin-top: 15px;
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .sidebar-title {
            display: none;
        }

        /* Menu Items */
        .sidebar-menu {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            padding: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        /* Active Menu Item */
        .sidebar-menu li.active a {
            background-color: #FFEB3B;
            /* Yellow background */
            color: #333;
            /* Dark text */
            font-weight: bold;
        }

        /* Hover Effect */
        .sidebar-menu li a:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Icons */
        .sidebar-menu li a i {
            font-size: 1.2rem;
            margin-right: 15px;
            min-width: 24px;
            text-align: center;
        }

        /* Menu Text */
        .sidebar-menu li a .menu-text {
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
            margin-left: 0;
        }

        /* Navigation Bar Adjustment */
        .nav-container {
            left: 240px;
            width: calc(100% - 240px);
            transition: left 0.3s ease, width 0.3s ease;
        }

        .sidebar.collapsed+.nav-container {
            left: 80px;
            width: calc(100% - 80px);
        }

        /* Main Content Adjustment */
        .main-content {
            margin-left: 240px;
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed+.nav-container+.main-content {
            margin-left: 80px;
        }

        /* Responsive Behavior */
        @media (max-width: 992px) {
            .sidebar {
                left: -240px;
            }

            .sidebar.show {
                left: 0;
            }

            .sidebar.collapsed {
                left: -80px;
            }

            .sidebar.collapsed.show {
                left: 0;
                width: 80px;
            }

            .nav-container {
                left: 0;
                width: 100%;
            }

            .sidebar.show+.nav-container {
                left: 240px;
                width: calc(100% - 240px);
            }

            .sidebar.collapsed.show+.nav-container {
                left: 80px;
                width: calc(100% - 80px);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.show+.nav-container+.main-content {
                margin-left: 240px;
            }

            .sidebar.collapsed.show+.nav-container+.main-content {
                margin-left: 80px;
            }
        }

        /* Navigation Bar Styles */
        .nav-container {
            background-color: #2c3e50;
            /* Dark blue-gray background */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            position: fixed;
            top: 0;
            left: 240px;
            /* Matches sidebar width */
            width: calc(100% - 240px);
            height: 60px;
            transition: left 0.3s ease, width 0.3s ease;
        }

        /* When sidebar is collapsed */
        .sidebar.collapsed+.nav-container {
            left: 80px;
            width: calc(100% - 80px);
        }

        /* Navigation Tabs Container */
        .nav-tabs {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 20px;
            height: 100%;
            position: relative;
        }

        /* Hamburger Menu Icon */
        .hamburger {
            position: absolute;
            left: 15px;
            color: white;
            font-size: 20px;
            cursor: pointer;
            z-index: 1001;
            transition: transform 0.3s ease;
        }

        .hamburger:hover {
            color: #FFEB3B;
            /* Yellow hover */
        }

        /* Navigation Links */
        .nav-link {
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
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #FFEB3B;
            /* Yellow text on hover */
        }

        /* Active Navigation Link */
        .nav-link.active {
            background-color: #4CAF50;
            /* Green background */
            color: white;
            border-bottom: 3px solid #FFEB3B;
            /* Yellow underline */
        }

        /* Logout Button Special Styling */
        .nav-link.logout {
            color: #e74c3c;
            /* Red color for logout */
            font-weight: bold;
        }

        .nav-link.logout:hover {
            background-color: rgba(231, 76, 60, 0.1);
        }

        /* Role Indicator */
        .role-indicator {
            position: absolute;
            top: 70px;
            right: 20px;
            background-color: #4CAF50;
            /* Green background */
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
            .nav-container {
                left: 0;
                width: 100%;
            }

            .sidebar.show+.nav-container {
                left: 240px;
                width: calc(100% - 240px);
            }

            .sidebar.collapsed.show+.nav-container {
                left: 80px;
                width: calc(100% - 80px);
            }

            /* Adjust nav links for mobile */
            .nav-tabs {
                justify-content: flex-start;
                overflow-x: auto;
                padding-left: 50px;
            }

            .nav-link {
                padding: 10px 15px;
                font-size: 14px;
                white-space: nowrap;
            }
        }

        /* Animation for active state */
        @keyframes navLinkPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .nav-link.active {
            animation: navLinkPulse 0.5s ease;
        }
    </style>
</head>

<body>
    <?php include 'side_bar.php'; ?>
    <?php include 'nav_bar.php'; ?>
    <!-- Role Indicator -->
    <div class="role-indicator">
        Logged in as: <?php echo strtoupper(htmlspecialchars($_SESSION['user_role'] ?? 'guest')); ?>
    </div>


    <script>
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');

        hamburger.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');

            // For mobile view
            if (window.innerWidth <= 992) {
                sidebar.classList.toggle('show');
            }
        });
    </script>
</body>

</html>