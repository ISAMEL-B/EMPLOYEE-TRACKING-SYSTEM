<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Signup</title>
    <link rel="icon" type="image/png" href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png">
    <link rel="stylesheet" href="src/fontawesome-5.15.4/css/all.min.css">
    <link rel="stylesheet" href="src/css/style.css">
    <style>
        .shake {
            animation: shake 0.5s;
        }

        @keyframes shake {
            0% {
                transform: translate(0);
            }

            25% {
                transform: translate(-5px);
            }

            50% {
                transform: translate(5px);
            }

            75% {
                transform: translate(-5px);
            }

            100% {
                transform: translate(0);
            }
        }

        .message {
            color: red;
            display: none;
        }


        .social {
            font-size: 24px;
            margin: 0 10px;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .social:hover {
            transform: scale(1.2);
        }

        .social.facebook i {
            color: #3b5998;
            /* Facebook blue */
        }

        .social.google i {
            color: #dd4b39;
            /* Google Plus red */
        }

        .social.linkedin i {
            color: #0077b5;
            /* LinkedIn blue */
        }
    </style>
    <script>
        window.onload = function() {
            let alertMessage = ""; // Initialize an empty alert message

            // Check for registration errors
            <?php if (isset($_SESSION['registration_errors'])): ?>
                alertMessage +=
                    "<?php echo addslashes($_SESSION['registration_errors']); ?>\n"; // Append registration error message
                <?php unset($_SESSION['registration_errors']); ?> // Unset after use
            <?php endif; ?>

            // Check for signup success message
            <?php if (isset($_SESSION['signup_message'])): ?>
                alertMessage += "<?php echo addslashes($_SESSION['signup_message']); ?>\n"; // Append signup success message
                <?php unset($_SESSION['signup_message']); ?> // Unset after use
            <?php endif; ?>

            // Show alert if there are any messages
            if (alertMessage) {
                alert(alertMessage.trim()); // Show the alert with the combined messages
            }

            // Display login error message
            <?php if (isset($_SESSION['login_error'])): ?>
                const errorMessage = "<?php echo addslashes($_SESSION['login_error']); ?>";
                const loginErrorMessageElement = document.getElementById('login-error-message');
                loginErrorMessageElement.textContent = errorMessage;
                loginErrorMessageElement.style.color = 'red';
                loginErrorMessageElement.style.display = 'block';

                // Shake effect
                loginErrorMessageElement.classList.add('shake');
                setTimeout(() => {
                    loginErrorMessageElement.classList.remove('shake');
                }, 500);

                // Hide the message after 5 seconds
                setTimeout(() => {
                    loginErrorMessageElement.style.display = 'none';
                }, 5000); // 5000 milliseconds = 5 seconds

                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>
        };
    </script>

</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="process.php" method="POST" id="sign-up-form">
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="#" class="social facebook" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social google" title="Google Plus">
                        <i class="fab fa-google-plus-g"></i>
                    </a>
                    <a href="#" class="social linkedin" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>

                </div>
                <!-- <span>or use your email for registration</span> -->
                <input type="text" name="employee_id" id="employee_id" placeholder="Employee ID" required />
                <input type="email" name="email" id="email" placeholder="Email" required />
                <input type="text" name="role" id="role" placeholder="role" required />
                <input type="password" name="password" placeholder="Password" required id="password" />
                <input type="password" name="confirm_password" placeholder="Confirm Password" required
                    id="confirm-password" />
                <p class="message" id="error-message">Passwords do not match!</p>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'hrm'): ?>
                    <button type="submit" name="signup">Register</button>
                <?php endif; ?>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="process.php" method="POST">
                <h1>Sign in</h1>
                <div class="social-container">
                    <a href="#" class="social facebook" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social google" title="Google Plus">
                        <i class="fab fa-google-plus-g"></i>
                    </a>
                    <a href="#" class="social linkedin" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>

                </div>
                <span>or use your account</span>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <p class="message" id="login-error-message" style="display:none;"></p> <!-- Error message -->
                <a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/mailing/password_recovery.php">Forgot your password?</a>
                <button type="submit" name="login">Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>MUST-Welcomes You Back!</h1>
                    <p>To keep connected with us please login with your MUST Credentials</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, MUST Associate!</h1>
                    <p>Enter personal details and start journey with MUST</p>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'hrm'): ?>
                        <button class="ghost" id="signUp">Register</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>
            Copyright (c) Wise-men All rights reserved @ <?php echo date('Y'); ?>
        </p>
    </footer>
    <script src="src/js/script.js"></script>
    <script>
        document.getElementById('sign-up-form').addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const errorMessage = document.getElementById('error-message');

            if (password !== confirmPassword) {
                event.preventDefault(); // Prevent form submission
                errorMessage.style.display = 'block';
                document.getElementById('confirm-password').classList.add('shake');
                setTimeout(() => {
                    document.getElementById('confirm-password').classList.remove('shake');
                }, 500);

                // Hide the message after 5 seconds
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000); // 5000 milliseconds = 5 seconds
            } else {
                errorMessage.style.display = 'none';
            }
        });
    </script>
</body>

</html>