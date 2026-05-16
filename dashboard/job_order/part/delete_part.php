<?php
include '../../db/conn.php';
include '../../db/checker.php';

// Check if the required GET parameters are set
if (isset($_GET['Id']) && !empty($_GET['Id']) && isset($_GET['CMT_id']) && !empty($_GET['CMT_id'])) {
    $part_id = htmlspecialchars($_GET['Id']);
    $cmt_id = htmlspecialchars($_GET['CMT_id']);

    // Sanitize the CMT table name based on the CMT ID
    $cmt_table = preg_replace('/[^A-Za-z0-9_]/', '_', $cmt_id);

    // Delete part from the CMT table
    $delete_query = "DELETE FROM `$cmt_table` WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    
    if ($stmt) {
        $stmt->bind_param('i', $part_id);
        if ($stmt->execute()) {
            echo "Part deleted successfully!";  // Return success message
        } else {
            echo "Error deleting part: " . $stmt->error;  // Return error message
        }
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;  // Return prepare error
    }
    $conn->close();
} else {
    echo "Invalid part ID or CMT ID.";  // Return error if parameters are missing
}
?>
