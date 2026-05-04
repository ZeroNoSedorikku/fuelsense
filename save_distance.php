<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) exit("Access denied");

$user_id = $_SESSION['user_id'];
$vehicle_id = $_POST['vehicle_id'];

$date = $_POST['date'];
$distance = (float) $_POST['distance'];
$mode = $_POST['mode'] ?? 'Manual';

if (!$vehicle_id) exit("Select vehicle");

$query = "INSERT INTO distance_logs (user_id, vehicle_id, date, distance_km, mode)
          VALUES ($1, $2, $3, $4, $5)";

pg_query_params($conn, $query, [
    $user_id,
    $vehicle_id,
    $date,
    $distance,
    $mode
]);

header("Location: view_distance.php?vehicle_id=$vehicle_id");
exit();
?>