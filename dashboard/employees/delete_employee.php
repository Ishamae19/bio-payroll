<?php
include '../db/conn.php';
include '../db/checker.php';


if (isset($_POST['id'])) {
    $employeeId = $_POST['id'];

    // Delete the employee
    $sql = "UPDATE employees SET del_fingerid=1 WHERE fingerprint_select=1";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        header("Location: ../employees.php");
        //echo "<script>alert('Employee deleted successfully!'); window.location.href = '../employees.php';</script>";
    } else {
        echo "<script>alert('Error deleting employee.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
