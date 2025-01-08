<?php

// verify_otp.php

session_start();

// Database connection (replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = " ";
$dbname = "oop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->close();
?>