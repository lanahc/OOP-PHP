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

if (isset($_POST['otp'])) {
    $enteredOtp = $_POST['otp'];

    // Retrieve the stored OTP from the session (or database)
    $storedOtp = $_SESSION['otp'] ?? null;

    if ($storedOtp === $enteredOtp) {
        // OTP verification successful
        $_SESSION['two_factor_authenticated'] = true; 

        // Redirect to the protected area
        header("Location: protected_area.php"); 
        exit;
    } else {
        // OTP verification failed
        // Display an error message to the user
        echo "Invalid OTP.";
    }
}

$conn->close();

?>