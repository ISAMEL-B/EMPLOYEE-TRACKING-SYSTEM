<?php
session_start();

// Database configuration
include '../head/approve/config.php';

// Handle unlock request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unlock'])) {
    $password = $_POST['password'] ?? '';
    $employee_id = $_SESSION['employee_id'] ?? '';

    // Prepare SQL to fetch user's password hash and photo path
    $stmt = $conn->prepare("SELECT password, photo_path FROM staff WHERE employee_id = ?");
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

// Fetch user details for display
$employee_id = $_SESSION['employee_id'] ?? '';
$user_role = $_SESSION['user_role'] ?? 'Staff';
$phone_number = $_SESSION['phone_number'] ?? 'User';

// Get profile picture path
$profile_picture = '';
if (!empty($employee_id)) {
    $stmt = $conn->prepare("SELECT photo_path FROM staff WHERE employee_id = ?");
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $profile_picture = !empty($user['photo_path']) ? $user['photo_path'] : '';
        
        // If photo_path is relative, prepend the base URL
        if (!empty($profile_picture) && !filter_var($profile_picture, FILTER_VALIDATE_URL)) {
            $profile_picture = '../' . ltrim($profile_picture, '/');
        }
    }
}

// Default avatar if no picture exists
if (empty($profile_picture)) {
    $profile_picture = "https://ui-avatars.com/api/?name=" . urlencode($phone_number) . "&size=128&background=006633&color=fff";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST ETS - Screen Locked</title>
    <link rel="icon" type="image/png" href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png">
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

            <!-- User Avatar - shows actual profile picture or generated avatar -->
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" class="user-avatar" alt="User Avatar">
            
            <div class="user-name"><?php echo htmlspecialchars($phone_number); ?></div>
            <div class="user-role">MUST - <?php echo htmlspecialchars(strtoupper($user_role)); ?></div>

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
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            document.getElementById('current-time').textContent = timeString;
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>

    <script src="../../components/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>