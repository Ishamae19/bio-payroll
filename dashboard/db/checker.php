<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Check if trying to access a file within the dashboard folder
    if (strpos($_SERVER['PHP_SELF'], '/dashboard/') !== false) {
        header("Location: ../landing.php");
        exit();
    }
} else {
    // If logged in, prevent access to login.php
    if (basename($_SERVER['PHP_SELF']) === 'login.php') {
        header("Location: ../dashboard/index.php");
        exit();
    }
}
