<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST Employee Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --must-green: #006837;
            --must-yellow: #FFD700;
            --must-blue: #005BAA;
            --must-light: #E5F2E9;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Navbar Styles */
        .navbar-must {
            background: linear-gradient(135deg, var(--must-green) 0%, var(--must-blue) 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: white !important;
        }
        
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .nav-link i {
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }
        
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.3);
            color: white !important;
        }
        
        .badge-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.6rem;
            background-color: var(--must-yellow);
            color: black;
        }
        
        /* Sidebar Styles */
        .sidebar {
            min-height: calc(100vh - 56px);
            width: 250px;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 1.5rem 1rem;
            background: linear-gradient(135deg, var(--must-light) 0%, white 100%);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .user-profile {
            text-align: center;
        }
        
        .user-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }
        
        .user-name {
            font-weight: 600;
            margin-bottom: 0;
            color: var(--must-green);
        }
        
        .user-role {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .menu-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #495057;
            text-decoration: none;
            transition: all 0.3s;
            position: relative;
            font-weight: 500;
        }
        
        .menu-item:hover, .menu-item.active {
            color: var(--must-green);
            background-color: var(--must-light);
        }
        
        .menu-item:hover::before, .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--must-yellow);
        }
        
        .menu-item i {
            margin-right: 10px;
            color: var(--must-blue);
            width: 20px;
            text-align: center;
        }
        
        .submenu {
            padding-left: 2.5rem;
            font-size: 0.9rem;
        }
        
        .submenu .menu-item {
            padding: 0.5rem 1rem;
        }
        
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        
        /* Main Content Area */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        
        /* Toggle Sidebar */
        .sidebar-collapsed .sidebar {
            width: 70px;
            overflow: hidden;
        }
        
        .sidebar-collapsed .sidebar-header, 
        .sidebar-collapsed .user-name,
        .sidebar-collapsed .user-role,
        .sidebar-collapsed .menu-text {
            display: none;
        }
        
        .sidebar-collapsed .menu-item {
            text-align: center;
            padding: 0.75rem 0;
        }
        
        .sidebar-collapsed .menu-item i {
            margin-right: 0;
            font-size: 1.2rem;
        }
        
        .sidebar-collapsed .main-content {
            margin-left: 70px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                left: -250px;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-collapsed .sidebar {
                left: -70px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-must sticky-top">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <a class="navbar-brand" href="#">
                <img src="https://via.placeholder.com/40x40?text=MUST" alt="MUST Logo">
                <span class="d-none d-sm-inline">Employee Dashboard</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fas fa-home"></i>
                            <span class="d-none d-lg-inline">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-line"></i>
                            <span class="d-none d-lg-inline">Analytics</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-users"></i>
                            <span class="d-none d-lg-inline">Employees</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog"></i>
                            <span class="d-none d-lg-inline">Settings</span>
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="position-relative">
                                <span class="badge badge-notification rounded-pill">3</span>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Notifications</h6>
                            <a class="dropdown-item" href="#">New report generated</a>
                            <a class="dropdown-item" href="#">5 new employees added</a>
                            <a class="dropdown-item" href="#">System update available</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">View all notifications</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                            <span class="d-none d-lg-inline">Admin User</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="user-profile">
                <img src="https://via.placeholder.com/70x70?text=User" alt="User" class="user-avatar">
                <h5 class="user-name">Dr. John Doe</h5>
                <span class="user-role">HR Manager</span>
            </div>
        </div>
        
        <div class="sidebar-menu">
            <a href="#" class="menu-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text">Dashboard</span>
            </a>
            
            <a href="#" class="menu-item">
                <i class="fas fa-graduation-cap"></i>
                <span class="menu-text">Academic Performance</span>
            </a>
            
            <a href="#" class="menu-item">
                <i class="fas fa-flask"></i>
                <span class="menu-text">Research & Publications</span>
                <span class="badge bg-success float-end">15</span>
            </a>
            
            <a href="#" class="menu-item">
                <i class="fas fa-hands-helping"></i>
                <span class="menu-text">Community Service</span>
            </a>
            
            <a href="#" class="menu-item">
                <i class="fas fa-users"></i>
                <span class="menu-text">Employee Management</span>
            </a>
            
            <a href="#" class="menu-item">
                <i class="fas fa-chart-pie"></i>
                <span class="menu-text">Reports & Analytics</span>
            </a>
            
            <div class="submenu">
                <a href="#" class="menu-item">
                    <i class="fas fa-file-export"></i>
                    <span class="menu-text">Export Data</span>
                </a>
                
                <a href="#" class="menu-item">
                    <i class="fas fa-cog"></i>
                    <span class="menu-text">System Settings</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-footer text-center">
            <button class="btn btn-sm btn-outline-secondary" id="collapseSidebar">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Your dashboard content goes here -->
            <h4 class="mb-4">Welcome to MUST Employee Tracking System</h4>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> This is where your dashboard content will appear.
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Toggle Sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
        
        // Collapse Sidebar
        document.getElementById('collapseSidebar').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-collapsed');
            
            // Change icon
            const icon = this.querySelector('i');
            if (document.body.classList.contains('sidebar-collapsed')) {
                icon.classList.remove('fa-chevron-left');
                icon.classList.add('fa-chevron-right');
            } else {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-left');
            }
        });
        
        // Activate menu items
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                menuItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>