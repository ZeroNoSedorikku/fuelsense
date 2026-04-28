<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    exit("Access denied");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    pg_query_params($conn,
        "DELETE FROM distance_logs WHERE id = $1 AND user_id = $2",
        [$id, $user_id]
    );
}

header("Location: view_distance.php");
exit();
?>