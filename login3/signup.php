<?php 

    include '../dashboard/db/conn.php';

    // Check if roles are already filled
    $sql = "SELECT role FROM account WHERE role IN ('admin', 'secretary')";
    $result = $conn->query($sql);

    // Track whether admin and secretary roles are filled
    $adminExists = false;
    $secretaryExists = false;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['role'] === 'admin') {
                $adminExists = true;
            } elseif ($row['role'] === 'secretary') {
                $secretaryExists = true;
            }
        }
    }

    // If both admin and secretary exist, redirect or disable signup
    if ($adminExists && $secretaryExists) {
        echo "<script>alert('Both Admin and Secretary roles are filled. No further accounts can be created.'); window.location.href = 'login.php';</script>";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullName = $_POST['full_name'];
        $address = $_POST['address'];
        $cellphone = $_POST['cellphone'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $role = $_POST['role']; 

        // Validate role
        if (!in_array($role, ['admin', 'secretary'])) {
            echo "<script>alert('Invalid role selected.');</script>";
            exit;
        }

        // Check if the role has already been filled
        if (($role === 'admin' && $adminExists) || ($role === 'secretary' && $secretaryExists)) {
            echo "<script>alert('This role has already been filled.');</script>";
            exit;
        }

        // Check if passwords match
        if ($password !== $confirmPassword) {
            echo "<script>alert('Passwords do not match.');window.history.back();</script>";
            exit;
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $sql = "INSERT INTO account (full_name, address, cellphone, email, password_hash, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $fullName, $address, $cellphone, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            echo "<script>alert('Account created successfully!'); window.location.href = 'login.php';</script>";
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sign Up | JDO</title>
</head>
<body>
    <div class="jdo-box">
        <form method="POST">
            <div class="input-box">
                <input type="text" name="full_name" class="input-field" placeholder="Full Name" onkeydown="return /[a-zA-Z]/i.test(event.key)" required>
            </div>
            <div class="input-box">
                <input type="text" name="address" class="input-field" placeholder="Address" required>
            </div>
            <div class="input-box">
            <input type="text" name="cellphone" class="input-field" placeholder="Cellphone no." onkeydown="return event.key >= '0' && event.key <= '9' || event.key === 'Backspace';" required>

            </div>
            <div class="input-box">
                <input type="email" name="email" class="input-field" placeholder="Email" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" class="input-field" placeholder="Password" required>
            </div>
            <div class="input-box">
                <input type="password" name="confirm_password" class="input-field" placeholder="Confirm Password" required>
            </div>
            <div class="input-box">
                <select name="role" class="input-field" required>
                    <option value="admin" <?php echo $adminExists ? 'disabled' : ''; ?>>Admin</option>
                    <option value="secretary" <?php echo $secretaryExists ? 'disabled' : ''; ?>>Secretary</option>
                </select>
            </div>
            <div class="input-submit">
                <button class="submit-btn" id="submit" type="submit">Sign Up</button>
            </div>
        </form>
    </div>
</body>
</html>
