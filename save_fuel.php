<?php
session_start();
include 'db.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $date = $_POST['date'];
    $liters = $_POST['liters'];
    $cost = $_POST['cost'];

    // Validation
    if (empty($date) || empty($liters) || empty($cost)) {
        echo "All fields are required!";
        exit();
    }

    // Secure query
    $query = "INSERT INTO fuel_logs (user_id, date, liters, cost)
              VALUES ($1, $2, $3, $4)";

    $result = pg_query_params($conn, $query, [
        $user_id,
        $date,
        $liters,
        $cost
    ]);

    if ($result) {
        header("Location: fuel_form.php?success=1");
        exit();
    } else {
        echo "Error saving fuel!";
    }
}
?>