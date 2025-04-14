<?php
session_start();
require_once '../head/approve/config.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['request_reset'])) {
        // Step 1: Request password reset
        $email = $conn->real_escape_string($_POST['email']);
        
        $query = "SELECT user_id, email, phone_number FROM users WHERE email = '$email' OR personal_email = '$email'";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Generate token (valid for 1 hour)
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $update = "UPDATE users SET 
                      reset_token = '$token', 
                      reset_token_expiry = '$expiry' 
                      WHERE user_id = {$user['user_id']}";
            
            if ($conn->query($update)) {
                // Send email with reset link (in production)
                $_SESSION['recovery_email'] = $email;
                $_SESSION['recovery_token'] = $token;
                
                // For demo, we'll just proceed to verification
                header('Location: password-recovery.php?step=verify');
                exit();
            }
        } else {
            $_SESSION['error'] = "No account found with that email address";
        }
    }
    elseif (isset($_POST['verify_code'])) {
        // Step 2: Verify code (simplified for demo)
        if ($_POST['code'] === '123456') { // In production, send real SMS code
            $_SESSION['verified'] = true;
            header('Location: password-recovery.php?step=reset');
            exit();
        } else {
            $_SESSION['error'] = "Invalid verification code";
        }
    }
    elseif (isset($_POST['reset_password'])) {
        // Step 3: Reset password
        if (!isset($_SESSION['verified'])) {
            header('Location: password-recovery.php');
            exit();
        }
        
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_SESSION['recovery_email'];
        
        $update = "UPDATE users SET 
                  password = '$password',
                  reset_token = NULL,
                  reset_token_expiry = NULL
                  WHERE email = '$email'";
        
        if ($conn->query($update)) {
            $_SESSION['success'] = "Password updated successfully!";
            unset($_SESSION['recovery_email'], $_SESSION['recovery_token'], $_SESSION['verified']);
            header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
            exit();
        }
    }
}

// Determine current step
$step = isset($_GET['step']) ? $_GET['step'] : 'request';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST HRM - Password Recovery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            background-color: var(--must-light-green);
            height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .recovery-container {
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border-top: 5px solid var(--must-green);
        }
        
        .recovery-header {
            background-color: var(--must-green);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .recovery-header h2 {
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .recovery-body {
            padding: 30px;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #eee;
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #ddd;
            color: #777;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .step.active .step-number {
            background-color: var(--must-green);
            color: white;
        }
        
        .step.completed .step-number {
            background-color: var(--must-blue);
            color: white;
        }
        
        .step-label {
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        
        .step.active .step-label {
            color: var(--must-green);
            font-weight: 600;
        }
        
        .step.completed .step-label {
            color: var(--must-blue);
        }
        
        .form-control:focus {
            border-color: var(--must-green);
            box-shadow: 0 0 0 0.25rem rgba(0, 102, 51, 0.25);
        }
        
        .btn-primary {
            background-color: var(--must-green);
            border-color: var(--must-green);
        }
        
        .btn-primary:hover {
            background-color: var(--must-blue);
            border-color: var(--must-blue);
        }
        
        .verification-input {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .verification-input input {
            width: 40px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .password-strength {
            height: 5px;
            background-color: #eee;
            margin-top: 5px;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            background-color: #dc3545;
            transition: width 0.3s;
        }
        
        .password-criteria {
            font-size: 13px;
            color: #6c757d;
            margin-top: 10px;
        }
        
        .password-criteria i {
            margin-right: 5px;
        }
        
        .password-criteria .valid {
            color: var(--must-green);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="recovery-container">
            <div class="recovery-header">
                <h2><i class="fas fa-lock-open me-2"></i>Password Recovery</h2>
            </div>
            
            <div class="recovery-body">
                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step <?php echo in_array($step, ['request', 'verify', 'reset']) ? 'completed' : ''; ?> <?php echo $step === 'request' ? 'active' : ''; ?>">
                        <div class="step-number">1</div>
                        <div class="step-label">Request Reset</div>
                    </div>
                    <div class="step <?php echo in_array($step, ['verify', 'reset']) ? 'completed' : ''; ?> <?php echo $step === 'verify' ? 'active' : ''; ?>">
                        <div class="step-number">2</div>
                        <div class="step-label">Verify Identity</div>
                    </div>
                    <div class="step <?php echo $step === 'reset' ? 'active' : ''; ?>">
                        <div class="step-number">3</div>
                        <div class="step-label">New Password</div>
                    </div>
                </div>
                
                <!-- Error/Success Messages -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Step 1: Request Reset -->
                <?php if ($step === 'request'): ?>
                    <form method="POST">
                        <div class="mb-4 text-center">
                            <i class="fas fa-key fa-3x mb-3" style="color: var(--must-green);"></i>
                            <h4>Reset Your Password</h4>
                            <p class="text-muted">Enter your email address to receive a reset link</p>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                   placeholder="Enter your MUST email address">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="request_reset" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i> Send Reset Link
                            </button>
                            <a href="/EMPLOYEE-TRACKING-SYSTEM/registration/register.php" class="btn btn-link text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i> Back to Login
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
                
                <!-- Step 2: Verify Identity -->
                <?php if ($step === 'verify'): ?>
                    <form method="POST">
                        <div class="mb-4 text-center">
                            <i class="fas fa-mobile-alt fa-3x mb-3" style="color: var(--must-blue);"></i>
                            <h4>Verify Your Identity</h4>
                            <p class="text-muted">We sent a verification code to your registered phone number</p>
                            <small class="text-muted">(Demo: Use code <strong>123456</strong>)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Verification Code</label>
                            <div class="verification-input">
                                <input type="text" maxlength="1" pattern="\d" required>
                                <input type="text" maxlength="1" pattern="\d" required>
                                <input type="text" maxlength="1" pattern="\d" required>
                                <input type="text" maxlength="1" pattern="\d" required>
                                <input type="text" maxlength="1" pattern="\d" required>
                                <input type="text" maxlength="1" pattern="\d" required>
                            </div>
                            <input type="hidden" name="code" value="123456">
                            
                            <div class="text-center mt-3">
                                <a href="#" class="text-decoration-none">Resend Code</a>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="verify_code" class="btn btn-primary">
                                <i class="fas fa-check-circle me-2"></i> Verify Code
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
                
                <!-- Step 3: Reset Password -->
                <?php if ($step === 'reset'): ?>
                    <form method="POST" id="resetForm">
                        <div class="mb-4 text-center">
                            <i class="fas fa-lock fa-3x mb-3" style="color: var(--must-green);"></i>
                            <h4>Create New Password</h4>
                            <p class="text-muted">Your new password must be different from previous passwords</p>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required
                                   placeholder="Enter your new password">
                            <div class="password-strength">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                            <div class="password-criteria">
                                <div><i class="fas fa-circle" id="lengthCheck"></i> Minimum 8 characters</div>
                                <div><i class="fas fa-circle" id="numberCheck"></i> At least one number</div>
                                <div><i class="fas fa-circle" id="specialCheck"></i> At least one special character</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" required
                                   placeholder="Re-enter your new password">
                            <div class="invalid-feedback" id="passwordMatchError">Passwords do not match</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="reset_password" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            let strength = 0;
            
            // Check length
            const hasLength = password.length >= 8;
            document.getElementById('lengthCheck').className = hasLength ? 'fas fa-check-circle valid' : 'fas fa-circle';
            if (hasLength) strength += 25;
            
            // Check for numbers
            const hasNumber = /\d/.test(password);
            document.getElementById('numberCheck').className = hasNumber ? 'fas fa-check-circle valid' : 'fas fa-circle';
            if (hasNumber) strength += 25;
            
            // Check for special chars
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            document.getElementById('specialCheck').className = hasSpecial ? 'fas fa-check-circle valid' : 'fas fa-circle';
            if (hasSpecial) strength += 25;
            
            // Check for uppercase
            const hasUpper = /[A-Z]/.test(password);
            if (hasUpper) strength += 25;
            
            // Update strength bar
            strengthBar.style.width = strength + '%';
            strengthBar.style.backgroundColor = 
                strength < 50 ? '#dc3545' : 
                strength < 75 ? '#fd7e14' : '#28a745';
        });
        
        // Password confirmation check
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const errorElement = document.getElementById('passwordMatchError');
            
            if (password !== confirmPassword && confirmPassword !== '') {
                this.classList.add('is-invalid');
                errorElement.style.display = 'block';
            } else {
                this.classList.remove('is-invalid');
                errorElement.style.display = 'none';
            }
        });
        
        // Form submission validation
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                document.getElementById('confirm_password').classList.add('is-invalid');
                document.getElementById('passwordMatchError').style.display = 'block';
            }
        });
        
        // Verification code input auto-focus
        const verificationInputs = document.querySelectorAll('.verification-input input');
        if (verificationInputs.length > 0) {
            verificationInputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (this.value.length === 1 && index < verificationInputs.length - 1) {
                        verificationInputs[index + 1].focus();
                    }
                });
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                        verificationInputs[index - 1].focus();
                    }
                });
            });
        }
    </script>
</body>
</html>