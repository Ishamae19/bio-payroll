<?php
//Connect to database
include '../db/conn.php';
include '../db/checker.php';

// select passenger 
if (isset($_GET['select'])) {

    $Finger_id = $_GET['Finger_id'];

    $sql = "SELECT fingerprint_select FROM employees WHERE fingerprint_select=1";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error_Select";
        exit();
    } else {
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)) {

            $sql = "UPDATE employees SET fingerprint_select=0";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo "SQL_Error_Select";
                exit();
            } else {
                mysqli_stmt_execute($result);

                $sql = "UPDATE employees SET fingerprint_select=1 WHERE fingerprint_id=?";
                $result = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($result, $sql)) {
                    echo "SQL_Error_select_Fingerprint";
                    exit();
                } else {
                    mysqli_stmt_bind_param($result, "s", $Finger_id);
                    mysqli_stmt_execute($result);

                    echo "User Fingerprint selected";
                    exit();
                }
            }
        } else {
            $sql = "UPDATE employees SET fingerprint_select=1 WHERE fingerprint_id=?";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo "SQL_Error_select_Fingerprint";
                exit();
            } else {
                mysqli_stmt_bind_param($result, "s", $Finger_id);
                mysqli_stmt_execute($result);

                echo "User Fingerprint selected";
                exit();
            }
        }
    }
}

mysqli_stmt_close($result);
mysqli_close($conn);
