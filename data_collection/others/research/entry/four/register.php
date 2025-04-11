<?php
session_start();
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login || Registration Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    
</head>
<body>
    <div class="cont">
        <?php if (isset($message)): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="form sign-in">
            <h2>Sign In</h2>
            <form method="post" action="login_signup_process.php">
                <label>
                    <span>Username</span>
                    <input type="text" name="username" required>
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" name="password" required>
                </label>
                <button class="submit" type="submit" name="login">Sign In</button>
            </form>
        </div>

        <div class="sub-cont">
            <div class="img">
                <div class="img-text m-up">
                    <h1>New here?</h1>
                    <p>Sign up and discover more</p>
                </div>
                <div class="img-text m-in">
                    <h1>One of us?</h1>
                    <p>Just sign in</p>
                </div>
                <div class="img-btn">
                    <span class="m-up">Sign Up</span>
                    <span class="m-in">Sign In</span>
                </div>
            </div>
            <div class="form sign-up">
                <h2>Sign Up</h2>
                <form method="post" action="login_signup_process.php">
                    <label>
                        <span>Employee ID</span>
                        <input type="text" name="employee_id" required>
                    </label>
                    <label>
                        <span>Username</span>
                        <input type="text" name="username" required>
                    </label>
                    <label>
                        <span>Email</span>
                        <input type="email" name="email" required>
                    </label>
                    <label>
                        <span>role</span>
                        <input type="text" name="role" required>
                    </label>
                    <label>
                        <span>Password</span>
                        <input type="password" name="password" required>
                    </label>
                    <label>
                        <span>Confirm Password</span>
                        <input type="password" name="confirm_password" required>
                    </label>
                    <button type="submit" class="submit" name="signup">Sign Up Now</button>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>