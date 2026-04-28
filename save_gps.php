<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];

$lat = $_POST['lat'];
$lon = $_POST['lon'];
$date = date("Y-m-d");

// First insert distance log (GPS mode)
$query1 = "INSERT INTO distance_logs (user_id, date, distance_km, mode)
           VALUES ('$user_id', '$date', 0, 'GPS') RETURNING log_id";

$result = pg_query($conn, $query1);
$row = pg_fetch_assoc($result);
$log_id = $row['log_id'];

// Then insert GPS data
$query2 = "INSERT INTO gps_logs (log_id, latitude, longitude, time)
           VALUES ('$log_id', '$lat', '$lon', NOW())";

pg_query($conn, $query2);

echo "GPS saved!";
?>