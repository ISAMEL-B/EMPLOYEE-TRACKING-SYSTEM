<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST ETS - Screen Locked</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
    <link rel="stylesheet" href="../../components/src/fontawesome/css/all.min.css">
    <!-- Style-->
    <link rel="stylesheet" href="../../components/bootstrap/css/bootstrap.min.css">

    <style>
        :root {
            --must-green: #006633;
            --must-yellow: #FFCC00;
            --must-blue: #003366;
            --must-light-green: #e6f2ec;
            --must-light-yellow: #fff9e6;
            --must-light-blue: #e6ecf2;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Layout Structure */
        #sidebar {
            width: 280px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
            z-index: 100;
        }
        
        .main-content {
            margin-left: 280px;
            padding-top: 70px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--must-light-green);
        }
        
        /* Lock Screen Container */
        .lock-screen-container {
            max-width: 500px;
            width: 100%;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
            border-top: 5px solid var(--must-green);
        }
        
        .lock-icon {
            font-size: 60px;
            color: var(--must-green);
            margin-bottom: 20px;
            background: var(--must-light-green);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 5px solid var(--must-yellow);
        }
        
        h2 {
            color: var(--must-blue);
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .security-message {
            color: #555;
            margin-bottom: 30px;
            font-size: 16px;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--must-yellow);
            margin-bottom: 20px;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--must-blue);
            margin-bottom: 5px;
        }
        
        .user-role {
            color: var(--must-green);
            font-weight: 500;
            margin-bottom: 30px;
            text-transform: uppercase;
            font-size: 14px;
        }
        
        .btn-unlock {
            background-color: var(--must-green);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            border-radius: 30px;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(0,102,51,0.2);
        }
        
        .btn-unlock:hover {
            background-color: var(--must-blue);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,102,51,0.3);
        }
        
        .system-branding {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
        
        .system-branding strong {
            color: var(--must-green);
        }
        
        /* Navbar styling */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: 70px;
            position: fixed;
            top: 0;
            right: 0;
            left: 280px;
            z-index: 99;
        }
        
        .navbar-brand img {
            height: 40px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .navbar {
                left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <div class="d-flex flex-column h-100">
            <div class="p-4 text-center">
                <img src="../../main/logo/mustlogo.png" alt="MUST Logo" style="height: 60px;">
                <div class="mt-3 fw-bold" style="color: var(--must-green);">EXPERT TRACKING SYSTEM</div>
            </div>
        </div>
    </div>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../../main/logo/mustlogo.png" alt="MUST Logo">
            </a>
            <div class="ms-auto d-flex align-items-center">
                <div class="text-muted me-3">
                    <i class="fas fa-clock me-1"></i>
                    <span id="current-time"></span>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="lock-screen-container">
            <div class="lock-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h2>Session Secured</h2>
            <p class="security-message">
                Your MUST Expert Tracking System session has been automatically locked for security.
                Please authenticate to continue accessing the system.
            </p>
            
            <!-- <img src="https://ui-avatars.com/api/?name=<?php //echo urlencode($_SESSION['user_name'] ?? 'User'); ?>&background=006633&color=fff"  -->
            <img src="../<?php echo $_SESSION['path']; ?>&background=006633&color=fff" 
                 class="user-avatar" 
                 alt="User Avatar">
            <div><p><?php echo $_SESSION['path']; ?></p></div>
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['employee_id'] ?? 'User'); ?></div>
            <div class="user-role"><?php echo htmlspecialchars($_SESSION['user_role'] ?? 'Staff'); ?></div>
            
            <form action="unlock.php" method="post">
                <div class="mb-3">
                    <input type="password" class="form-control" placeholder="Enter your password" required 
                           style="border-color: var(--must-green); padding: 12px; text-align: center;">
                </div>
                <button type="submit" class="btn btn-unlock">
                    <i class="fas fa-unlock me-2"></i> UNLOCK SESSION
                </button>
            </form>
            
            <div class="system-branding mt-4">
                <strong>MUST Expert Tracking System</strong> | Secure Academic Management Platform
            </div>
        </div>
    </main>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleTimeString();
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>