<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    exit("Access denied");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'];

    // safer inputs
    $date = $_POST['date'] ?? date('Y-m-d');
    $distance = isset($_POST['distance']) ? (float) $_POST['distance'] : 0;
    $mode = $_POST['mode'] ?? 'Manual'; // supports GPS + Manual

    // validation
    if (empty($date)) {
        exit("Date is required");
    }

    if ($distance <= 0) {
        exit("Invalid distance");
    }

    // insert
    $query = "INSERT INTO distance_logs (user_id, date, distance_km, mode)
              VALUES ($1, $2, $3, $4)";

    $result = pg_query_params($conn, $query, [
        $user_id,
        $date,
        $distance,
        $mode
    ]);

    if (!$result) {
        exit("Database error");
    }

    header("Location: view_distance.php");
    exit();
}
?>