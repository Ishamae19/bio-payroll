<?php
session_start();
include '../db/conn.php';
include '../db/checker.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cmt = $_POST['cmt'];
    $quantity = $_POST['quantity'];
    //$price = $_POST['price'];
    $price = 0;
    $bundle = $_POST['bundle']; // New bundle field

    // Insert into job_orders table
    $stmt = $conn->prepare("INSERT INTO job_orders (cmt, quantity, price, bundle) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sddi", $cmt, $quantity, $price, $bundle);

    if ($stmt->execute()) {
        // Sanitize the CMT value for table name
        $cmt_table = preg_replace('/[^A-Za-z0-9_]/', '_', $cmt);

        // Switch to the CMT database
        //$conn->query("USE CMT"); // Replace "CMT" with your actual database name

        // Create the main CMT table with bundle columns
        $createTableSql = "CREATE TABLE `$cmt_table` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            part_name VARCHAR(100) NOT NULL,
            price DECIMAL(10, 2) NOT NULL";

        // Dynamically add bundle columns to the main CMT table
        for ($i = 1; $i <= $bundle; $i++) {
            $createTableSql .= ", bdl$i INT DEFAULT 0"; // Initialize each bundle column
        }

        $createTableSql .= ")";

        if ($conn->query($createTableSql) === TRUE) {
            // Redirect to job_order.php on successful creation
            header("Location: ../job_order.php");
            exit();
        } else {
            echo "Error creating CMT table with bundle columns: " . $conn->error;
        }
    } else {
        echo "Error inserting into job_orders: " . $stmt->error;
    }

    // Close prepared statements and connection
    $stmt->close();
}
$conn->close();
?>