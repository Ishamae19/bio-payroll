
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../dashboard/db/conn.php';
include '../dashboard/db/checker.php'; // Redirect if already logged in

// Define the validate function
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// CSRF Token Setup
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if admin and secretary roles are filled
$sql = "SELECT role FROM account WHERE role IN ('admin', 'secretary')";
$result = $conn->query($sql);

$adminExists = false;
$secretaryExists = false;
while ($row = $result->fetch_assoc()) {
    if ($row['role'] === 'admin') $adminExists = true;
    if ($row['role'] === 'secretary') $secretaryExists = true;
}

$disableSignup = $adminExists && $secretaryExists;

// Handle Sign-In
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signin'])) {
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $csrf_token = $_POST['csrf_token'] ?? '';

    // CSRF Validation
    if ($csrf_token !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and Password are required.";
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT * FROM account WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['account_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;

        echo "<script>alert('Login successful!'); window.location.href = '../dashboard/index.php';</script>";
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}

// Handle Sign-Up
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    $fullName = validate($_POST['full_name']);
    $address = validate($_POST['address']);
    $cellphone = validate($_POST['cellphone']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $confirmPassword = validate($_POST['confirm_password']);
    $role = $_POST['role']; 

    if (!in_array($role, ['admin', 'secretary'])) {
        echo "<script>alert('Invalid role selected.');</script>";
        exit;
    }

    if (($role === 'admin' && $adminExists) || ($role === 'secretary' && $secretaryExists)) {
        echo "<script>alert('This role has already been filled.');</script>";
        exit;
    }

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.');</script>";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO account (full_name, address, cellphone, email, password_hash, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $fullName, $address, $cellphone, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Account created successfully!'); window.location.href = 'login.php';</script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
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

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="POST" action="">
                <h1>Create Account</h1>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="cellphone" placeholder="Cellphone no." required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <select name="role" required>
                    <option value="admin" <?php echo $adminExists ? 'disabled' : ''; ?>>Admin</option>
                    <option value="secretary" <?php echo $secretaryExists ? 'disabled' : ''; ?>>Secretary</option>
                </select>
                <button type="submit" name="signup">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="POST" action="">
                <h1>Sign In</h1>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="signin">Sign In</button>
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

    <script src="script.js"></script>
</body>

</html>