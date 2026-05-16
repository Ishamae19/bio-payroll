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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

// Check if admin and secretary roles are filled
$sql = "SELECT role FROM account WHERE role IN ('admin', 'secretary')";
$result = $conn->query($sql);

$adminExists = $secretaryExists = false;
    while ($row = $result->fetch_assoc()) {
        if ($row['role'] === 'admin') $adminExists = true;
        if ($row['role'] === 'secretary') $secretaryExists = true;
    }

$disableSignup = $adminExists && $secretaryExists;

?>