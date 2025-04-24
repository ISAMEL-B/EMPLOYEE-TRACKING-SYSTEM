<?php
session_start();
require_once '../head/approve/config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
date_default_timezone_set('Africa/Kampala');

// Load PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['request_reset'])) {
        // Step 1: Request password reset
        $email = $conn->real_escape_string($_POST['email']);
        
        $query = "SELECT staff_id, employee_id, email, first_name, last_name FROM staff WHERE personal_email = '$email' OR email = '$email'";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Generate 6-digit verification code
            $verification_code = sprintf('%06d', mt_rand(0, 999999));
            
            // Set expiry to 30 minutes
            $expiry = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            
            // Store code in session and database
            $_SESSION['recovery_email'] = $email;
            $_SESSION['verification_code'] = $verification_code;
            $_SESSION['code_expiry'] = $expiry;
            
            $update = "UPDATE staff SET 
                      reset_code = '$verification_code', 
                      reset_code_expiry = '$expiry' 
                      WHERE email = '$email' OR personal_email = '$email'";
            
            if ($conn->query($update)) {
                // Send email with verification code
                $mail = new PHPMailer(true);
                
                try {
                    // SMTP Configuration
                    $mail->isSMTP();
                    $mail->SMTPAuth = true;
                    $mail->Host = 'smtp.gmail.com';
                    $mail->Username = 'byaruhangaisamelk@gmail.com';
                    $mail->Password = 'aqvy jonz xoio nlxn';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    
                    // Recipients
                    $mail->setFrom('byaruhangaisamelk@gmail.com', 'MUST HRM System');
                    $mail->addAddress($email, $user['first_name']);
                    
                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Verification Code';
                    $mail->Body = "
                        <h2>Password Reset Request</h2>
                        <p>Hello {$user['first_name']},</p>
                        <p>You have requested to reset your password for the MUST HRM System.</p>
                        <p>Your verification code is: <strong>{$verification_code}</strong></p>
                        <p>This code will expire in 30 minutes.</p>
                        <p>If you didn't request this, please ignore this email.</p>
                        <p>Best regards,<br>MUST HRM Team</p>
                    ";
                    
                    $mail->send();
                    header('Location: password_recovery.php?step=verify');
                    exit();
                } catch (Exception $e) {
                    $_SESSION['error'] = "Message could not be sent. Please try again.";
                    header('Location: password_recovery.php');
                    exit();
                }
            } else {
                $_SESSION['error'] = "Database error. Please try again.";
                header('Location: password_recovery.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "No account found with that email address";
            header('Location: password_recovery.php');
            exit();
        }
    }
    elseif (isset($_POST['verify_code'])) {
        // Step 2: Verify code
        $entered_code = implode('', [
            $_POST['digit1'], $_POST['digit2'], $_POST['digit3'],
            $_POST['digit4'], $_POST['digit5'], $_POST['digit6']
        ]);
        
        $email = $_SESSION['recovery_email'] ?? '';
        
        if (empty($email)) {
            $_SESSION['error'] = "Session expired. Please start the process again.";
            header('Location: password_recovery.php');
            exit();
        }
        
        // Check if code matches and isn't expired
        $query = "SELECT reset_code, reset_code_expiry FROM staff 
                 WHERE (email = '$email' OR personal_email = '$email')
                 AND reset_code = '$entered_code'";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $expiry_time = strtotime($row['reset_code_expiry']);
            $current_time = time();
            
            if ($current_time > $expiry_time) {
                $_SESSION['error'] = "Verification code has expired. Please request a new one.";
            } else {
                $_SESSION['verified'] = true;
                header('Location: password_recovery.php?step=reset');
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid verification code";
        }
        
        header('Location: password_recovery.php?step=verify');
        exit();
    }
    elseif (isset($_POST['reset_password'])) {
        // Step 3: Reset password
        if (!isset($_SESSION['verified']) || empty($_SESSION['recovery_email'])) {
            header('Location: password_recovery.php');
            exit();
        }
        
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_SESSION['recovery_email'];
        
        $update = "UPDATE staff SET 
                  password = '$password',
                  reset_code = NULL,
                  reset_code_expiry = NULL
                  WHERE email = '$email' OR personal_email = '$email'";
        
        if ($conn->query($update)) {
            $_SESSION['success'] = "Password updated successfully! You can now login with your new password.";
            unset($_SESSION['recovery_email'], $_SESSION['verification_code'], $_SESSION['code_expiry'], $_SESSION['verified']);
            header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
            exit();
        } else {
            $_SESSION['error'] = "Failed to update password. Please try again.";
            header('Location: password_recovery.php?step=reset');
            exit();
        }
    }
    elseif (isset($_POST['resend_code'])) {
        // Handle resend code request
        $email = $_SESSION['recovery_email'] ?? '';
        
        if (!empty($email)) {
            // Generate new code with 30 minute expiry
            $verification_code = sprintf('%06d', mt_rand(0, 999999));
            $expiry = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            
            // Update session and database
            $_SESSION['verification_code'] = $verification_code;
            $_SESSION['code_expiry'] = $expiry;
            
            $update = "UPDATE staff SET 
                      reset_code = '$verification_code', 
                      reset_code_expiry = '$expiry' 
                      WHERE email = '$email' OR personal_email = '$email'";
            
            if ($conn->query($update)) {
                $query = "SELECT first_name FROM staff WHERE email = '$email' OR personal_email = '$email'";
                $result = $conn->query($query);
                $user = $result->fetch_assoc();
                
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->SMTPAuth = true;
                    $mail->Host = 'smtp.gmail.com';
                    $mail->Username = 'byaruhangaisamelk@gmail.com';
                    $mail->Password = 'aqvy jonz xoio nlxn';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    
                    $mail->setFrom('byaruhangaisamelk@gmail.com', 'MUST HRM System');
                    $mail->addAddress($email, $user['first_name']);
                    
                    $mail->isHTML(true);
                    $mail->Subject = 'New Password Reset Verification Code';
                    $mail->Body = "
                        <h2>New Verification Code</h2>
                        <p>Hello {$user['first_name']},</p>
                        <p>Your new verification code is: <strong>{$verification_code}</strong></p>
                        <p>This code will expire in 30 minutes.</p>
                        <p>Best regards,<br>MUST HRM Team</p>
                    ";
                    
                    $mail->send();
                    echo json_encode([
                        'success' => true,
                        'new_expiry' => $expiry
                    ]);
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => 'Failed to send email']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
        }
        exit();
    }
}

// Determine current step
$step = $_GET['step'] ?? 'request';

// Calculate remaining time for countdown
$countdown_text = '';
if (isset($_SESSION['code_expiry'])) {
    $remaining = strtotime($_SESSION['code_expiry']) - time();
    if ($remaining > 0) {
        $minutes = floor($remaining / 60);
        $seconds = $remaining % 60;
        $countdown_text = "Code expires in $minutes:".str_pad($seconds, 2, '0', STR_PAD_LEFT);
    } else {
        $countdown_text = "Code expired";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST HRM - Password Recovery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="password.css">
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
                    <div class="step <?= in_array($step, ['request', 'verify', 'reset']) ? 'completed' : '' ?> <?= $step === 'request' ? 'active' : '' ?>">
                        <div class="step-number">1</div>
                        <div class="step-label">Request Reset</div>
                    </div>
                    <div class="step <?= in_array($step, ['verify', 'reset']) ? 'completed' : '' ?> <?= $step === 'verify' ? 'active' : '' ?>">
                        <div class="step-number">2</div>
                        <div class="step-label">Verify Identity</div>
                    </div>
                    <div class="step <?= $step === 'reset' ? 'active' : '' ?>">
                        <div class="step-number">3</div>
                        <div class="step-label">New Password</div>
                    </div>
                </div>
                
                <!-- Error/Success Messages -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $_SESSION['error']; unset($_SESSION['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $_SESSION['success']; unset($_SESSION['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Step 1: Request Reset -->
                <?php if ($step === 'request'): ?>
                    <form method="POST">
                        <div class="mb-4 text-center">
                            <i class="fas fa-key fa-3x mb-3" style="color: #006837;"></i>
                            <h4>Reset Your Password</h4>
                            <p class="text-muted">Enter your email address to receive a verification code</p>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                   placeholder="Enter your email address">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="request_reset" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i> Send Verification Code
                            </button>
                            <a href="/EMPLOYEE-TRACKING-SYSTEM/registration/register.php" class="btn btn-link text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i> Back to Login
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
                
                <!-- Step 2: Verify Identity -->
                <?php if ($step === 'verify'): ?>
                    <form method="POST" id="verifyForm">
                        <div class="mb-4 text-center">
                            <i class="fas fa-mobile-alt fa-3x mb-3" style="color: #005baa;"></i>
                            <h4>Verify Your Identity</h4>
                            <p class="text-muted">We sent a 6-digit verification code to your email address</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Verification Code</label>
                            <div class="verification-input">
                                <input type="text" name="digit1" maxlength="1" pattern="\d" required class="verification-digit">
                                <input type="text" name="digit2" maxlength="1" pattern="\d" required class="verification-digit">
                                <input type="text" name="digit3" maxlength="1" pattern="\d" required class="verification-digit">
                                <input type="text" name="digit4" maxlength="1" pattern="\d" required class="verification-digit">
                                <input type="text" name="digit5" maxlength="1" pattern="\d" required class="verification-digit">
                                <input type="text" name="digit6" maxlength="1" pattern="\d" required class="verification-digit">
                            </div>
                            
                            <div class="text-center mt-3">
                                <button type="button" id="resendCode" class="btn btn-link text-decoration-none p-0">Resend Code</button>
                                <div id="countdown" data-expiry="<?= htmlspecialchars($_SESSION['code_expiry'] ?? '') ?>" class="text-muted small mt-1">
                                    <?= $countdown_text ?>
                                </div>
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
                            <i class="fas fa-lock fa-3x mb-3" style="color: #006837;"></i>
                            <h4>Create New Password</h4>
                            <p class="text-muted">Your new password must be different from previous passwords</p>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required
                                   placeholder="Enter your new password">
                            <div class="password-strength mt-2">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" id="passwordStrengthBar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="password-criteria mt-2">
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
        document.getElementById('password')?.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            let strength = 0;
            
            // Check length
            const hasLength = password.length >= 8;
            document.getElementById('lengthCheck').className = hasLength ? 'fas fa-check-circle text-success' : 'fas fa-circle text-secondary';
            if (hasLength) strength += 25;
            
            // Check for numbers
            const hasNumber = /\d/.test(password);
            document.getElementById('numberCheck').className = hasNumber ? 'fas fa-check-circle text-success' : 'fas fa-circle text-secondary';
            if (hasNumber) strength += 25;
            
            // Check for special chars
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            document.getElementById('specialCheck').className = hasSpecial ? 'fas fa-check-circle text-success' : 'fas fa-circle text-secondary';
            if (hasSpecial) strength += 25;
            
            // Check for uppercase
            const hasUpper = /[A-Z]/.test(password);
            if (hasUpper) strength += 25;
            
            // Update strength bar
            strengthBar.style.width = strength + '%';
            strengthBar.className = 'progress-bar ' + 
                (strength < 50 ? 'bg-danger' : 
                 strength < 75 ? 'bg-warning' : 'bg-success');
        });
        
        // Password confirmation check
        document.getElementById('confirm_password')?.addEventListener('input', function() {
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
        document.getElementById('resetForm')?.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                document.getElementById('confirm_password').classList.add('is-invalid');
                document.getElementById('passwordMatchError').style.display = 'block';
            }
        });
        
        // Verification code input auto-focus
        const verificationDigits = document.querySelectorAll('.verification-digit');
        if (verificationDigits.length > 0) {
            verificationDigits[0].focus();
            
            verificationDigits.forEach((digit, index) => {
                digit.addEventListener('input', function() {
                    if (this.value.length === 1 && index < verificationDigits.length - 1) {
                        verificationDigits[index + 1].focus();
                    }
                });
                
                digit.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                        verificationDigits[index - 1].focus();
                    }
                });
            });
            
            // Countdown timer
            function updateCountdown() {
                const countdownElement = document.getElementById('countdown');
                if (!countdownElement) return;
                
                const expiryTime = countdownElement.dataset.expiry;
                if (!expiryTime) return;
                
                const now = new Date();
                const expiryDate = new Date(expiryTime);
                const remaining = Math.floor((expiryDate - now) / 1000);
                
                if (remaining <= 0) {
                    countdownElement.textContent = "Code expired";
                    return;
                }
                
                const minutes = Math.floor(remaining / 60);
                const seconds = remaining % 60;
                countdownElement.textContent = `Code expires in ${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
            
            // Initialize countdown
            updateCountdown();
            const countdownInterval = setInterval(updateCountdown, 1000);
            
            // Resend code functionality
            document.getElementById('resendCode')?.addEventListener('click', function(e) {
                e.preventDefault();
                const btn = this;
                btn.disabled = true;
                
                fetch('password_recovery.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'resend_code=true'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the expiry time
                        document.getElementById('countdown').dataset.expiry = data.new_expiry;
                        // Reset countdown
                        clearInterval(countdownInterval);
                        updateCountdown();
                        setInterval(updateCountdown, 1000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    setTimeout(() => {
                        btn.disabled = false;
                    }, 60000); // 60 second cooldown
                });
            });
        }
    </script>
</body>
</html>