<?php
include '../db/conn.php';
include 'db/checker.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serial = $_POST['sno'];
    $name = $_POST['name'];
    $operation = $_POST['operation'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date_hired = $_POST['date_hired'];

    // Update employee details
    $sql = "UPDATE employees SET name=?, operation=?, email=?, phone=?, date_hired=? WHERE fingerprint_select=1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $operation, $email, $phone, $date_hired);

    if ($stmt->execute()) {
        header("Location: ../employees.php");
        //echo "<script>alert('Employee updated successfully!'); window.location.href = '../employees.php';</script>";
    } else {
        echo "<script>alert('Error updating employee.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
