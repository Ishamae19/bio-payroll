<?php
include '../../db/conn.php'; // Adjusted path
include '../../db/checker.php';  // Adjusted path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cmt_id = htmlspecialchars($_POST['CMT_Id']); // CMT table name
    $part_name = htmlspecialchars($_POST['part']); // Updated part name
    $price = htmlspecialchars($_POST['price']); // Updated price
    $part_id = htmlspecialchars($_POST['part_id']);
    $bundle_values = $_POST['bundle'] ?? []; // Updated bundle quantities
    if (empty($part_id)) {
        echo "<script>alert('Part ID is required.'); window.history.back();</script>";
        exit;
    }

    // Sanitize CMT ID for table usage
    $cmt_table = preg_replace('/[^A-Za-z0-9_]/', '_', $cmt_id);

    // Debugging: Check sanitized table name
    //echo "Sanitized table name: $cmt_table<br>";

    // Fetch existing column names from the CMT table
    $columns_result = $conn->query("SHOW COLUMNS FROM `$cmt_table`");
    if (!$columns_result) {
        die("Error fetching columns: " . $conn->error);
    }

    $existing_columns = [];
    while ($column = $columns_result->fetch_assoc()) {
        $existing_columns[] = $column['Field'];
    }

    // Determine the valid bundle columns in the table (bdl1, bdl2, bdl3, etc.)
    $valid_bundle_columns = array_filter($existing_columns, function ($col) {
        return preg_match('/^bdl\d+$/', $col);
    });

    // Debugging: Check valid bundle columns
    //echo "Valid bundle columns: " . implode(", ", $valid_bundle_columns) . "<br>";

    // Validate the provided bundles do not exceed available columns
    $provided_bundles = count($bundle_values);
    $max_bundles = count($valid_bundle_columns);
    if ($provided_bundles > $max_bundles) {
        echo "<script>
                alert('Error: Number of provided bundles ($provided_bundles) exceeds the allowed number ($max_bundles).');
                window.history.back();
              </script>";
        exit;
    }

    // Fetch the total allowed quantity from the job order
    $job_order_query = $conn->prepare("SELECT quantity FROM job_orders WHERE cmt = ?");
    $job_order_query->bind_param("s", $cmt_id);
    $job_order_query->execute();
    $job_order_result = $job_order_query->get_result();

    if ($job_order_result->num_rows === 0) {
        echo "<script>alert('Job order not found for CMT ID: $cmt_id'); window.history.back();</script>";
        exit;
    }

    $job_order = $job_order_result->fetch_assoc();
    $total_quantity = (int)$job_order['quantity'];

    // Calculate the total bundle quantity
    $total_bundle_quantity = array_sum($bundle_values);

    // Validate that the total bundle quantity does not exceed the allowed total quantity
    if ($total_bundle_quantity > $total_quantity) {
        echo "<script>
                alert('Error: Total bundle quantity ($total_bundle_quantity) exceeds the allowed quantity ($total_quantity) for this job order.');
                window.history.back();
              </script>";
        exit;
    }

    // Prepare the SQL SET clause with dynamic bundle columns
    $set_clauses = ["part_name = ?", "price = ?"];
    $bind_params = [$part_name, $price]; // Start with part_name and price in bind params
    $types = "sd"; // Types for part_name (string) and price (double)

    // Map each bundle to its corresponding column
    foreach ($bundle_values as $index => $bundle_quantity) {
        // Map bundle index to database column (e.g., bundle[0] -> bdl1, bundle[1] -> bdl2)
        $bundle_column = "bdl" . ($index + 1); // Correctly map to bdl1, bdl2, etc.
        
        if (in_array($bundle_column, $valid_bundle_columns)) { // Ensure column exists
            $set_clauses[] = "$bundle_column = ?";
            $bind_params[] = (int)$bundle_quantity; // Safely cast to integer
            $types .= "i"; // Add integer type for bundle
        }
    }

    // Build the SQL query for updating the part
    $set_clause = implode(", ", $set_clauses);
    $query = "UPDATE `$cmt_table` SET $set_clause WHERE id = ?";
    $bind_params[] = $part_id; // Add part_id for the WHERE clause
    $types .= "i"; // Add integer type for part_id

    // Prepare, bind, and execute the statement
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param($types, ...$bind_params);

    if ($stmt->execute()) {
        // Update the job order price dynamically using curl
        $cmt_id = urlencode($cmt_id);
        $curl = curl_init();
    
        curl_setopt_array($curl, [
            CURLOPT_URL => "../../job_order/calculation.php?cmt=$cmt_id", // Call calculation.php with the CMT
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true, // Follow redirects if any
            CURLOPT_SSL_VERIFYPEER => false, // Only if you're using SSL and want to skip verification
        ]);
    
        // Execute curl and close it
        $response = curl_exec($curl);
        if ($response === false) {
            echo "Curl error: " . curl_error($curl);
        }
    
        curl_close($curl);
    
        echo "<script>
                alert('Part updated successfully!');
                window.location.href = '../operation_breakdown.php?cmt=" . urlencode($cmt_id) . "&price=" . urlencode($price) . "&bundle=" . urlencode($provided_bundles) . "';
              </script>";
    } else {
        echo "<script>
                alert('Error updating part: " . $stmt->error . "' );
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>
            alert('Invalid request method. Please submit the form via POST.');
            window.history.back();
          </script>";
}
?>
