<?php
include '../db/conn.php';
include '../db/checker.php';

// Check if the ID is set in the query string
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get the CMT value associated with the job order ID to determine the table name
    $cmtQuery = $conn->prepare("SELECT cmt FROM job_orders WHERE id = ?");
    $cmtQuery->bind_param("i", $id);
    $cmtQuery->execute();
    $cmtQuery->bind_result($cmt);
    $cmtQuery->fetch();
    $cmtQuery->close();

    // Sanitize the CMT value to use it as a table name
    $cmt_table = preg_replace('/[^A-Za-z0-9_]/', '_', $cmt);

    // Prepare and execute the delete statement for the job order
    $deleteQuery = $conn->prepare("DELETE FROM job_orders WHERE id = ?");
    $deleteQuery->bind_param("i", $id);

    if ($deleteQuery->execute()) {
        // Drop the associated CMT table
        $dropTableQuery = "DROP TABLE IF EXISTS `$cmt_table`";
        if ($conn->query($dropTableQuery) === TRUE) {
            header("Location: ../job_order.php?message=Job order and associated table deleted successfully.");
            exit;
        } else {
            echo "Error dropping associated table: " . $conn->error;
        }
    } else {
        echo "Error deleting job order.";
    }

    $deleteQuery->close();
} else {
    echo "No job order ID provided.";
}

$conn->close();
?>
