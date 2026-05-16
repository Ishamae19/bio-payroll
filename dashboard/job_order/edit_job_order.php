<?php
include '../db/conn.php';
include '../db/checker.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cmt = $_POST['cmt'];              
    $quantity = $_POST['quantity'];    
    $price = $_POST['price'];           
    $original_bundle = $_POST['bundle']; 
    $id = $_POST['job_order_id'];

    // First, update the job_orders table with the new values
    $stmt = $conn->prepare("UPDATE job_orders SET cmt = ?, quantity = ?, price = ?, bundle = ? WHERE id = ?");
    $stmt->bind_param("sddii", $cmt, $quantity, $price, $original_bundle, $id);

    if ($stmt->execute()) {
        // Sanitize the CMT value for table name
        $cmt_table = preg_replace('/[^A-Za-z0-9_]/', '_', $cmt);

        // Fetch current bundle columns in the main CMT table
        $result = $conn->query("DESCRIBE `$cmt_table`");
        $current_columns = [];
        while ($row = $result->fetch_assoc()) {
            $current_columns[] = $row['Field'];
        }

        // Determine the existing bundle column count
        $current_bundle_count = 0;
        foreach ($current_columns as $column) {
            if (preg_match('/^bdl\d+$/', $column)) {
                $current_bundle_count++;
            }
        }

        // Determine if we need to add or drop bundle columns
        $bundle_diff = $original_bundle - $current_bundle_count;

        // Drop extra columns if the number of bundles has decreased
        if ($bundle_diff < 0) {
            for ($i = $current_bundle_count; $i > $original_bundle; $i--) {
                $column_to_drop = "bdl$i";
                $conn->query("ALTER TABLE `$cmt_table` DROP COLUMN `$column_to_drop`");
            }
        }

        // Add missing columns if the number of bundles has increased
        if ($bundle_diff > 0) {
            for ($i = $current_bundle_count + 1; $i <= $original_bundle; $i++) {
                $conn->query("ALTER TABLE `$cmt_table` ADD COLUMN `bdl$i` INT DEFAULT 0");
            }
        }

        // Redirect back to the job order page
        header("Location: ../job_order.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
