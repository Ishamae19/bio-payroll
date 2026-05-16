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
    $username = validate($_POST['username']);
    $password = validate($_POST['password']);
    $csrf_token = $_POST['csrf_token'] ?? '';

    // CSRF Validation
    if ($csrf_token !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Email and Password are required.";
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT * FROM account WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
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
    $username = validate($_POST['username']);
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
        echo "<script>alert('Passwords do not match.'); window.location.href='login.php' ;</script>";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO account (full_name, username, address, cellphone, email, password_hash, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $fullName, $username, $address, $cellphone, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Account created successfully!'); window.location.href = 'login.php';</script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>