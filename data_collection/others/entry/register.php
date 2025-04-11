<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LetsChat</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .shake {
            animation: shake 0.5s;
        }
        @keyframes shake {
            0% { transform: translate(0); }
            25% { transform: translate(-5px); }
            50% { transform: translate(5px); }
            75% { transform: translate(-5px); }
            100% { transform: translate(0); }
        }
        .message {
            color: red;
            display: none;
        }
    </style>
    <script>
    window.onload = function() {
        let alertMessage = ""; // Initialize an empty alert message

        // Check for registration errors
        <?php if (isset($_SESSION['registration_errors'])): ?>
            alertMessage += "<?php echo addslashes($_SESSION['registration_errors']); ?>\n"; // Append registration error message
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
            
            <!-- <span>or use your email for registration</span> -->
            <input type="text" name="employee_id" id="fname" placeholder="Employee_ID" required />
            <input type="text" name="username" id="username" placeholder="Username" required />
            <input type="email" name="email" id="email" placeholder="Email" required />
            <input type="text" name="role" id="role" placeholder="role" required />
            <input type="password" name="password" placeholder="Password" required id="password" />
            <input type="password" name="confirm_password" placeholder="Confirm Password" required id="confirm-password" />
            <p class="message" id="error-message">Passwords do not match!</p>
            <button type="submit" name="signup">Sign Up</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form action="process.php" method="POST">
            <h1>Sign in</h1>
            <input type="user" name="user" placeholder="username / Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <p class="message" id="login-error-message" style="display:none;"></p> <!-- Error message -->
            <a href="#">Forgot your password?</a>
            <button type="submit" name="login">Sign In</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Expart ScoreCard</h1>
                <p>Progress tracking is a key to suceed</p>
                <p>Suceed We MUST</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>HRM Tracker, </h1>
                <p>Enter your personal details to upload some of vital information from your office</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>
<script src="script.js"></script>
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
