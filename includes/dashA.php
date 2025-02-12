<?php
require_once '../classes/Admin.php';

if (!Admin::isLoggedIn()) {
    header("Location: loginA.php");
    exit;
}

echo "<h2>Welcome to the Admin Dashboard</h2>";
echo "<p>You are logged in as administrator.</p>";
echo "<a href='logout.php'>Logout</a>";
?>