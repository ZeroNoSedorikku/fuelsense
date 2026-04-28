<?php
session_start();

// If logged in → go to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FuelSense</title>
</head>
<body style="text-align:center; margin-top:100px; font-family:Arial;">

    <h1>🚗 FuelSense</h1>
    <p>Welcome to your Smart Fuel Tracking System</p>

    <a href="login.php">Login</a> |
    <a href="register.php">Register</a>

</body>
</html>