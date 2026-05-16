<?php
require 'signin_signup.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>JDO</title>
</head>

<body>
    <div class="back">
        <a href="../landing.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="POST" action="">
                <h1>Create Account</h1>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="text" name="full_name" placeholder="Full Name"
                    onkeydown="this.value = this.value.replace(/[^a-zA-Z.\s]/g, '').replace(/\b\w/g, char => char.toUpperCase())"
                    required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="cellphone" placeholder="Cellphone no."
                    onkeydown="return (event.key >= '0' && event.key <= '9') || event.key === 'Backspace' || event.key === 'Tab';"
                    pattern="09\d{9}" maxlength="11" required
                    title="Please enter a valid 11-digit cellphone number starting with 09">
                <input type="email" name="email" placeholder="Email" required
                    pattern="^[a-zA-Z0-9._%+-]+@(gmail\.com|hotmail\.com|yahoo\.com)$"
                    title="Please enter an email ending with @gmail.com, @hotmail.com, or @yahoo.com">
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <div class="select-container">
                    <select class="select-box" name="role" required>
                        <option value="" disabled selected>Position</option>
                        <option value="admin" <?php echo $adminExists ? 'disabled' : ''; ?>>Admin</option>
                        <option value="secretary" <?php echo $secretaryExists ? 'disabled' : ''; ?>>Secretary</option>
                    </select>
                </div>
                <button type="submit" name="signup">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="POST" action="">
                <h1>Sign In</h1>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                </div>
                <?php endif; ?>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="signin">Sign In</button>
                <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?php echo $_SESSION['error'];
                                        unset($_SESSION['error']); ?></p>
                <?php endif; ?>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>JDO GARMENTS</h1>
                    <p>Enter your personal details to use all site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>JDO GARMENTS</h1>
                    <p>Register with your personal details to use all site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');

    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });

    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });
    </script>

</body>

</html>