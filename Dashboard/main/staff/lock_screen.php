<?php
session_start();

// Database configuration (replace with your actual credentials)
include '../head/approve/config.php';
// Handle unlock request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unlock'])) {
    $password = $_POST['password'] ?? '';
    $employee_id = $_SESSION['employee_id'] ?? '';

    // Prepare SQL to fetch user's password hash
    $stmt = $conn->prepare("SELECT password FROM staff WHERE employee_id = ?");
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password correct - unlock session
            $_SESSION['last_activity'] = time();
            $return_url = $_SESSION['return_url'] ?? '../index.php';
            unset($_SESSION['return_url']);
            header("Location: " . $return_url);
            exit();
        }
    }
    
    // Authentication failed
    $error = "Invalid password. Please try again.";
}

// If not handling unlock, display the lock screen
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST ETS - Screen Locked</title>
    <link rel="stylesheet" href="../../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../components/bootstrap/css/bootstrap.min.css">
 <link rel="stylesheet" href="lock_style.css">
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

            <img src="../<?php echo $_SESSION['path'] ?? ''; ?>&background=006633&color=fff" class="user-avatar" alt="User Avatar">
            
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['employee_id'] ?? 'User'); ?></div>
            <div class="user-role"><?php echo htmlspecialchars($_SESSION['user_role'] ?? 'Staff'); ?></div>

            <form action="" method="post">
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required
                        style="border-color: var(--must-green); padding: 12px; text-align: center;">
                    <?php if (isset($error)): ?>
                        <div class="text-danger mt-2"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" name="unlock" class="btn btn-unlock">
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

    <script src="../../components/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>