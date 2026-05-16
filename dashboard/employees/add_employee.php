<?php
session_start();
include '../db/conn.php';
include '../db/checker.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serial = $_POST['sno'];
    $name = $_POST['name'];
    $operation = $_POST['operation'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date_hired'];

    function generateUniqueFingerprintID($conn)
    {
        do {
            $fingerprint_id = rand(1, 127);
            $result = $conn->query("SELECT COUNT(*) FROM employees WHERE fingerprint_id = $fingerprint_id");
            $row = $result->fetch_row();
        } while ($row[0] > 0);

        return $fingerprint_id;
    }

    $result = $conn->query("SELECT COUNT(*) FROM employees");
    $row = $result->fetch_row();
    $currentCount = $row[0];

    if ($currentCount >= 127) {
        echo "Error: Fingerprint sensor memory is full. Cannot add more fingerprints.";
        exit();
    }

    $fingerprint_id = generateUniqueFingerprintID($conn);

    $stmt = $conn->prepare("INSERT INTO employees (serialnumber, name, operation, email, phone, date_hired, fingerprint_id, add_fingerid) VALUES (?, ?, ?, ?, ?, ?, ?, '1')");
    $stmt->bind_param("dsssssd", $serial, $name, $operation, $email, $phone, $date, $fingerprint_id);

    if ($stmt->execute()) {
        header("Location: ../employees.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
