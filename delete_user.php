<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'admin') {
    exit("Access denied");
}

if (isset($_GET['id'])) {

    $user_id = $_GET['id'];

    // ❌ Prevent deleting yourself
    if ($user_id == $_SESSION['user_id']) {
        echo "You cannot delete your own account!";
        exit();
    }

    pg_query_params($conn,
        "DELETE FROM users WHERE user_id = $1",
        [$user_id]
    );
}

header("Location: view_users.php");
exit();
?>