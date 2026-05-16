<?php
include '../db/conn.php';

if (isset($_GET['cmt'])) {
    $cmt = htmlspecialchars($_GET['cmt']);
    $cmt_table = preg_replace('/[^A-Za-z0-9_]/', '_', $cmt);

    // Calculate the total price for the CMT table
    $query = "SELECT SUM(price) AS total_price FROM `$cmt_table`";
    $result = $conn->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        $total_price = $row['total_price'];

        // Update the job_orders table with the new total price
        $update_query = $conn->prepare("UPDATE job_orders SET price = ? WHERE cmt = ?");
        $update_query->bind_param("ds", $total_price, $cmt);
        $update_query->execute();

        // Return the total price as JSON
        echo json_encode(['total_price' => $total_price]);
        exit;
    }
}

// Return an error if the CMT is invalid or query fails
echo json_encode(['error' => 'Invalid request or no prices found']);
exit;
?>
