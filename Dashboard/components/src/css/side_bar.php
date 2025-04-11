<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Tracking System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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
            /* No background */
            border: none;
            /* No border */
            color: #ffffff;
            /* White color for icon */
            cursor: pointer;
            /* Pointer cursor */
            font-size: 24px;
            /* Icon size */
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

        .treeview-menu li {
            background-color: #4caf50;
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
    </style>
</head>

<body>

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
                <li class="treeview">
                    <a href="#" class="toggle">
                        <i class="fa fa-tachometer-alt"></i> <!-- Icon for Dashboard -->
                        <span style="margin-left: 10px;">Dashboard</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="index.php"><i class="fa fa-chart-line"></i> General Progress </a></li>
                        <li><a href="index2.php"><i class="fa fa-university"></i> Faculty Progress </a></li>
                        <li><a href="index3.php"><i class="fa fa-building"></i> Department Progress </a></li>
                        <li><a href="index4.php"><i class="fa fa-user"></i> Individual Progress </a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#" class="toggle">
                        <i class="fa fa-edit"></i> <!-- Icon for Update -->
                        <span style="margin-left: 10px;">Update</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/upload_csv.php"><i class="fa fa-file-upload"></i> CSV Upload </a></li>
                        <li><a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/view_criteria.php"><i class="fa fa-edit"></i> View | Edit Criteria </a></li>
                        <li><a href="#"><i class="fa fa-database"></i> Modify DB Tables </a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#" class="toggle">
                        <i class="fa fa-info-circle"></i> <!-- Icon for About -->
                        <span style="margin-left: 10px;">About</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="extra_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#" class="toggle">
                        <i class="fa fa-lock"></i> <!-- Icon for Authentication -->
                        <span style="margin-left: 10px;">Authentication</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="/EMPLOYEE-TRACKING-SYSTEM/registration/register.php" class="d-light"><i class="fas fa-user-plus"></i>Register</a>
                        </li>
                        <li>
                            <a href="/EMPLOYEE-TRACKING-SYSTEM/registration/logout.php" class="d-light"><i class="fas fa-sign-in-alt"></i> Log Out</a>
                        </li>
                        <li>
                            <a href="auth_lockscreen.html" class="d-light"><i class="fas fa-lock"></i> Lockscreen</a>
                        </li>
                        <li>
                            <a href="auth_user_pass.html" class="d-light"><i class="fas fa-key"></i> Recover password</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>

    <script>
        document.getElementById('hamburger').addEventListener('click', function() {
            const sidebar = document.querySelector('.main-sidebar');

            // Check if the sidebar is collapsing
            const isCollapsing = sidebar.classList.contains('collapsed');

            // If collapsing, close all active submenus
            if (!isCollapsing) {
                const activeSubmenus = document.querySelectorAll('.treeview.active');
                activeSubmenus.forEach(active => {
                    const submenu = active.querySelector('.treeview-menu');
                    active.classList.remove('active');
                    submenu.style.maxHeight = null; // Reset max-height to allow closing animation
                });
            }

            // Toggle the collapsed class on the sidebar
            sidebar.classList.toggle('collapsed');
        });

        // Handle submenu toggling
        const toggles = document.querySelectorAll('.toggle');

        toggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const parentLi = this.parentElement;
                const submenu = parentLi.querySelector('.treeview-menu');

                // Close all other open submenus
                toggles.forEach(otherToggle => {
                    const otherParentLi = otherToggle.parentElement;
                    const otherSubmenu = otherParentLi.querySelector('.treeview-menu');
                    if (otherParentLi !== parentLi) {
                        otherParentLi.classList.remove('active');
                        otherSubmenu.style.maxHeight = null; // Reset max-height
                    }
                });

                // Toggle the clicked submenu
                parentLi.classList.toggle('active');

                // Adjust max-height for animation
                if (parentLi.classList.contains('active')) {
                    submenu.style.maxHeight = submenu.scrollHeight + "px"; // Set max-height to the scroll height
                } else {
                    submenu.style.maxHeight = null; // Reset max-height to allow closing animation
                }
            });
        });
    </script>
</body>

</html>