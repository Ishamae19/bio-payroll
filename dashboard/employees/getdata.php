<?php
session_start();
include "../db/conn.php";



date_default_timezone_set('Asia/Manila');
$d = date("Y-m-d");
$t = date("H:i:sa");

if (isset($_POST['FingerID'])) {

    $fingerID = $_POST['FingerID'];

    $sql = "SELECT * FROM employees WHERE fingerprint_id=?";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error_Select_card";
        exit();
    } else {
        mysqli_stmt_bind_param($result, "s", $fingerID);
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)) {
            //*****************************************************
            //An existed fingerprint has been detected for Login or Logout
            if ($row['add_fingerid'] != 1) {

                $name = $row['name'];
                $Number = $row['serialnumber'];
                $sql = "SELECT * FROM attendance WHERE fingerprint_id=? AND checkindate=? AND timeout=''";
                $result = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($result, $sql)) {
                    echo "SQL_Error_Select_logs";
                    exit();
                } else {
                    mysqli_stmt_bind_param($result, "ss", $fingerID, $d);
                    mysqli_stmt_execute($result);
                    $resultl = mysqli_stmt_get_result($result);
                    //*****************************************************
                    //Login
                    if (!$row = mysqli_fetch_assoc($resultl)) {

                        $sql = "INSERT INTO attendance (name, serialnumber, fingerprint_id, checkindate, timein, timeout) VALUES (? ,?, ?, ?, ?, ?)";
                        $result = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($result, $sql)) {
                            echo "SQL_Error_Select_login1";
                            exit();
                        } else {
                            $timeout = "0";
                            mysqli_stmt_bind_param($result, "sdisss", $name, $Number, $fingerID, $d, $t, $timeout);
                            mysqli_stmt_execute($result);

                            echo "login" . $name;
                            exit();
                        }
                    }
                    //*****************************************************
                    //Logout
                    else {
                        $sql = "UPDATE attendance SET timeout=? WHERE checkindate=? AND fingerprint_id=? AND timeout='0'";
                        $result = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($result, $sql)) {
                            echo "SQL_Error_insert_logout1";
                            exit();
                        } else {
                            mysqli_stmt_bind_param($result, "ssi", $t, $d, $fingerID);
                            mysqli_stmt_execute($result);

                            echo "logout" . $name;
                            exit();
                        }
                    }
                }
            }
            //*****************************************************
            //An available Fingerprint has been detected
            else {
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
                            echo "SQL_Error_insert";
                            exit();
                        } else {
                            mysqli_stmt_execute($result);

                            $sql = "UPDATE employees SET fingerprint_select=1 WHERE fingerprint_id=?";
                            $result = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($result, $sql)) {
                                echo "SQL_Error_insert_An_available_card";
                                exit();
                            } else {
                                mysqli_stmt_bind_param($result, "i", $fingerID);
                                mysqli_stmt_execute($result);

                                echo "available";
                                exit();
                            }
                        }
                    } else {
                        $sql = "UPDATE employees SET fingerprint_select=1 WHERE fingerprint_id=?";
                        $result = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($result, $sql)) {
                            echo "SQL_Error_insert_An_available_card";
                            exit();
                        } else {
                            mysqli_stmt_bind_param($result, "i", $finger_sel, $fingerID);
                            mysqli_stmt_execute($result);

                            echo "available";
                            exit();
                        }
                    }
                }
            }
        }
    }
}
if (isset($_POST['Get_Fingerid'])) {

    if ($_POST['Get_Fingerid'] == "get_id") {
        $sql = "SELECT fingerprint_id FROM employees WHERE add_fingerid=1";
        $result = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($result, $sql)) {
            echo "SQL_Error_Select";
            exit();
        } else {
            mysqli_stmt_execute($result);
            $resultl = mysqli_stmt_get_result($result);
            if ($row = mysqli_fetch_assoc($resultl)) {
                echo "add-id" . $row['fingerprint_id'];
                exit();
            } else {
                echo "Nothing";
                exit();
            }
        }
    } else {
        exit();
    }
}
if (!empty($_POST['confirm_id'])) {

    $fingerid = $_POST['confirm_id'];

    $sql = "UPDATE employees SET fingerprint_select=0 WHERE fingerprint_select=1";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error_Select";
        exit();
    } else {
        mysqli_stmt_execute($result);

        $sql = "UPDATE employees SET add_fingerid=0, fingerprint_select=1 WHERE fingerprint_id=?";
        $result = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($result, $sql)) {
            echo "SQL_Error_Select";
            exit();
        } else {
            mysqli_stmt_bind_param($result, "s", $fingerid);
            mysqli_stmt_execute($result);
            echo "Fingerprint has been added!";
            exit();
        }
    }
}
if (isset($_POST['DeleteID'])) {

    if ($_POST['DeleteID'] == "check") {
        $sql = "SELECT fingerprint_id FROM employees WHERE del_fingerid=1";
        $result = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($result, $sql)) {
            echo "SQL_Error_Select";
            exit();
        } else {
            mysqli_stmt_execute($result);
            $resultl = mysqli_stmt_get_result($result);
            if ($row = mysqli_fetch_assoc($resultl)) {

                echo "del-id" . $row['fingerprint_id'];

                $sql = "DELETE FROM employees WHERE del_fingerid=1";
                $result = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($result, $sql)) {
                    echo "SQL_Error_delete";
                    exit();
                } else {
                    mysqli_stmt_execute($result);
                    exit();
                }
            } else {
                echo "nothing";
                exit();
            }
        }
    } else {
        exit();
    }
}
if (isset($result) && $result instanceof mysqli_stmt) {
    mysqli_stmt_close($result);
}

// Close the database connection
mysqli_close($conn);
