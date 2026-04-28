<?php
session_start();
include 'db.php';

// Protect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $date = $_POST['date'];
    $distance = $_POST['distance'];

    if (empty($date) || empty($distance)) {
        echo "All fields required!";
        exit();
    }

    $query = "INSERT INTO distance_logs (user_id, date, distance_km, mode)
              VALUES ($1, $2, $3, $4)";

    $result = pg_query_params($conn, $query, [
        $user_id,
        $date,
        $distance,
        'Manual'
    ]);

    if ($result) {
        header("Location: add_distance.php?success=1");
        exit();
    } else {
        echo "Error saving distance!";
    }
}
?>