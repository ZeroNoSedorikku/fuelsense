<?php
session_start();
include 'db.php';

// ✅ Only admin allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    exit("Access denied");
}

if (isset($_GET['id'])) {

    $user_id = $_GET['id'];

    // ❌ Prevent deleting yourself
    if ($user_id == $_SESSION['user_id']) {
        exit("You cannot delete your own account!");
    }

    // =========================
    // DELETE IN CORRECT ORDER
    // =========================

    // 1. Distance logs
    pg_query_params($conn,
        "DELETE FROM distance_logs WHERE user_id = $1",
        [$user_id]
    );

    // 2. Fuel logs
    pg_query_params($conn,
        "DELETE FROM fuel_logs WHERE user_id = $1",
        [$user_id]
    );

    // 3. Vehicles
    pg_query_params($conn,
        "DELETE FROM vehicles WHERE user_id = $1",
        [$user_id]
    );

    // 4. User (LAST)
    pg_query_params($conn,
        "DELETE FROM users WHERE user_id = $1",
        [$user_id]
    );
}

// ✅ Redirect AFTER everything
header("Location: view_users.php");
exit();
?>