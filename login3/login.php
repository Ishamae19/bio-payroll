
<?php 
    require 'signin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login | JDO</title>
</head>
<body>
    <div class="jdo-box">
        <div class="jdo-header">
            <header>JDO GARMENTS</header>
        </div>
        <form method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <!-- Error message displayed here -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']); 
                    ?>
                </div>
            <?php endif; ?>
            <div class="input-box">
                <input type="text" name="email" class="input-field" placeholder="Email" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" class="input-field" placeholder="Password" required>
            </div>
            <div class="input-submit">
                <button class="submit-btn" type="submit">Sign In</button>
            </div>
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>
        </form>
        <div class="sign-up-link">
            <p>Don't have an account?
                <?php if ($disableSignup): ?>
                    <a href="#" onclick="return false;">Sign Up</a> <!-- Disabled -->
                <?php else: ?>
                    <a href="signup.php">Sign Up</a>
                <?php endif; ?>
            </p>
        </div>
    </div>
</body>
</html>
