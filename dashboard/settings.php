<?php
session_start();
include 'db/conn.php'; // Include your database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT full_name, username, address, cellphone, email, role FROM account WHERE account_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user information
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $address = $_POST['address'];
    $cellphone = $_POST['cellphone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $errors = [];

    // Validate inputs
    if (empty($full_name) || empty($username) || empty($address) || empty($cellphone) || empty($email)) {
        $errors[] = "All fields except password are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        // Hash password if updated
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE account SET full_name = ?, username = ?, address = ?, cellphone = ?, email = ?, password_hash = ? WHERE account_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssi', $full_name, $username, $address, $cellphone, $email, $password_hash, $user_id);
        } else {
            $sql = "UPDATE account SET full_name = ?, username = ?, address = ?, cellphone = ?, email = ? WHERE account_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssssi', $full_name, $username, $address, $cellphone, $email, $user_id);
        }

        if ($stmt->execute()) {
            $sql = "SELECT full_name, username, address, cellphone, email, role FROM account WHERE account_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            $success_message = "Profile updated successfully.";
        } else {
            $errors[] = "Error updating profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        input:focus {
            border-color: #4caf50;
            outline: none;
        }
        button {
            background: #4caf50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #45a049;
        }
        .error {
            background: #f8d7da;
            color: #842029;
            padding: 10px;
            border: 1px solid #f5c2c7;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .success {
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }

        .success.hidden {
            opacity: 0;
            display: none;
        }
        
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-business'></i>
            <span class="text">JDO Garments</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="index.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="attendance.php">
                    <i class='bx bx-calendar'></i>
                    <span class="text">Attendance</span>
                </a>
            </li>
            <li>
                <a href="employees.php">
                    <i class='bx bx-group'></i>
                    <span class="text">Employees</span>
                </a>
            </li>
            <li>
                <a href="report.php">
                    <i class='bx bx-file'></i>
                    <span class="text">Report</span>
                </a>
            </li>
            <li>
                <a href="job_order.php">
                    <i class='bx bx-briefcase-alt'></i>
                    <span class="text">Job Order</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li class="active">
                <a href="settings.php">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="../login/logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">8</span>
            </a>
            <a href="#" class="profile">
                <img src="user.png">
            </a>
        </nav>
        <main>
            <h1>Settings</h1>
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php elseif (!empty($success_message)): ?>
                <div class="success" id="success-message">
                    <p><?php echo htmlspecialchars($success_message); ?></p>
                </div>
            <?php endif; ?>
            <form method="POST" action="settings.php">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <label>Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>

                <label>Cellphone</label>
                <input type="text" name="cellphone" value="<?php echo htmlspecialchars($user['cellphone']); ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label>New Password (leave blank to keep current password)</label>
                <input type="password" name="password">

                <button type="submit">Update</button>
            </form>
        </main>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const successMessage = document.getElementById("success-message");
            if (successMessage) {
                setTimeout(() => {
                    successMessage.classList.add("hidden");
                }, 3000);
            }
        });
    </script>
    <!-- CONTENT -->
    <script src="script.js"></script>
</body>
</html>
