<?php


$conn = new mysqli("localhost", "root", "", "cmt");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>