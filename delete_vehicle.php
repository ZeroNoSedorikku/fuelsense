<?php
session_start();
include 'db.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    exit("Access denied");
}

$user_id = $_SESSION['user_id'];

// Get vehicle ID
$vehicle_id = $_GET['vehicle_id'] ?? null;

if (!$vehicle_id) {
    exit("No vehicle selected");
}

// 🔥 IMPORTANT: Delete related data first (to avoid foreign key error)

// delete distance logs
pg_query_params($conn,
    "DELETE FROM distance_logs WHERE vehicle_id = $1 AND user_id = $2",
    [$vehicle_id, $user_id]
);

// delete fuel logs
pg_query_params($conn,
    "DELETE FROM fuel_logs WHERE vehicle_id = $1 AND user_id = $2",
    [$vehicle_id, $user_id]
);

// delete vehicle
pg_query_params($conn,
    "DELETE FROM vehicles WHERE vehicle_id = $1 AND user_id = $2",
    [$vehicle_id, $user_id]
);

// redirect back
header("Location: dashboard.php");
exit();
?>