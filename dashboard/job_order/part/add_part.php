<?php
include '../../db/conn.php'; // Adjusted path
include '../../db/checker.php';  // Adjusted path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cmt_id = htmlspecialchars($_POST['CMT_id']);
    $part_name = htmlspecialchars($_POST['part']);
    $price = htmlspecialchars($_POST['price']);
    $bundle_values = $_POST['bundle'] ?? [];

    // Sanitize CMT ID for table usage
    $cmt_table = preg_replace('/[^A-Za-z0-9_]/', '_', $cmt_id);

    // Fetch existing column names from the CMT table
    $columns_result = $conn->query("SHOW COLUMNS FROM `$cmt_table`");
    $existing_columns = [];
    while ($column = $columns_result->fetch_assoc()) {
        $existing_columns[] = $column['Field'];
    }

    // Determine the valid bundle columns in the table
    $valid_bundle_columns = array_filter($existing_columns, function ($col) {
        return preg_match('/^bdl\d+$/', $col);
    });

    $max_bundles = count($valid_bundle_columns); // Maximum allowed bundle columns
    $provided_bundles = count($bundle_values);  // Number of bundles provided

    // Validate that the provided bundles do not exceed the available columns
    if ($provided_bundles > $max_bundles) {
        echo "<script>
                alert('Error: Number of provided bundles ($provided_bundles) exceeds the allowed number ($max_bundles) for this job order.');
                window.history.back();
              </script>";
        exit;
    }

    // Calculate the total quantity of the bundles
    $total_bundle_quantity = array_sum($bundle_values);

    // Fetch the total allowed quantity for the job order
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

    // Validate that the total bundle quantity does not exceed the allowed total quantity
    if ($total_bundle_quantity > $total_quantity) {
        echo "<script>
                alert('Error: Total bundle quantity ($total_bundle_quantity) exceeds the allowed quantity ($total_quantity) for this job order.');
                window.history.back();
              </script>";
        exit;
    }

    // Prepare the SQL insertion dynamically
    $bundle_columns = [];
    $bundle_values_safe = [];
    foreach ($bundle_values as $index => $bundle_quantity) {
        if (empty($bundle_quantity)) {
            echo "<script>
                    alert('Bundle $index has no value! Please provide a valid quantity.');
                    window.history.back();
                  </script>";
            exit;
        }
    
        $bundle_columns[] = "bdl" . $index; // Correctly map to bdl1, bdl2, etc.
        $bundle_values_safe[] = (int)$bundle_quantity; // Safely cast to integer
    }
    
    // Construct query with dynamic columns
    $columns = implode(", ", array_merge(['part_name', 'price'], $bundle_columns));
    $placeholders = implode(", ", array_fill(0, count($bundle_values_safe) + 2, '?'));
    
    $query = "INSERT INTO `$cmt_table` ($columns) VALUES ($placeholders)";
    $stmt = $conn->prepare($query);
    
    // Bind parameters dynamically
    $types = "sd" . str_repeat("i", count($bundle_values_safe));
    $params = array_merge([$part_name, $price], $bundle_values_safe);
    $stmt->bind_param($types, ...$params);

    // Execute and handle success or error
    //alert('Part added successfully!');
    
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
                window.location.href = '../operation_breakdown.php?cmt=" . urlencode($cmt_id) . "&price=" . urlencode($price) . "&bundle=" . urlencode($max_bundles) . "';
              </script>";
    } else {
        echo "<script>
                alert('Error adding part: " . $stmt->error . "');
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
