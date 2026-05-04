<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) exit("Access denied");

$user_id = $_SESSION['user_id'];
$vehicle_id = $_POST['vehicle_id'];

$date = $_POST['date'];
$liters = (float) $_POST['liters'];
$cost = (float) $_POST['cost'];

if (!$vehicle_id) exit("Select vehicle");

$query = "INSERT INTO fuel_logs (user_id, vehicle_id, date, liters, cost)
          VALUES ($1, $2, $3, $4, $5)";

pg_query_params($conn, $query, [
    $user_id,
    $vehicle_id,
    $date,
    $liters,
    $cost
]);

header("Location: view_fuel.php?vehicle_id=$vehicle_id");
exit();
?>