<?php
session_start();
include 'db.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $date = $_POST['date'];
    $distance = (float) $_POST['distance'];

    // Detect mode
    if (isset($_POST['mode'])) {
        $mode = $_POST['mode']; // GPS
    } else {
        $mode = "Manual";
    }

    // Validate
    if (empty($date) || empty($distance)) {
        echo "All fields are required!";
        exit();
    }

    // Insert safely
    $query = "INSERT INTO distance_logs (user_id, date, distance_km, mode)
              VALUES ($1, $2, $3, $4)";

    $result = pg_query_params($conn, $query, [
        $user_id,
        $date,
        $distance,
        $mode
    ]);

    if ($result) {
        header("Location: view_distance.php");
        exit();
    } else {
        echo "Error saving distance!";
    }
}
?>