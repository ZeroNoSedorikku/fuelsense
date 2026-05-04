<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    exit("Access denied");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get inputs safely
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $cc = (int) ($_POST['cc'] ?? 0);
    $type = trim($_POST['type'] ?? '');

    // =====================
    // VALIDATION
    // =====================
    if (empty($brand) || empty($model) || empty($cc) || empty($type)) {
        exit("⚠ All fields are required!");
    }

    if ($cc <= 0) {
        exit("⚠ Invalid CC value!");
    }

    // =====================
    // DUPLICATE CHECK
    // =====================
    $check_query = "
        SELECT COUNT(*) 
        FROM vehicles 
        WHERE user_id = $1 
        AND LOWER(brand) = LOWER($2)
        AND LOWER(model) = LOWER($3)
        AND cc = $4
    ";

    $check_result = pg_query_params($conn, $check_query, [
        $user_id,
        $brand,
        $model,
        $cc
    ]);

    $exists = pg_fetch_result($check_result, 0, 0);

    if ($exists > 0) {
        header("Location: add_vehicle.php?error=Vehicle already exists!");
        exit();
    }

    // =====================
    // INSERT VEHICLE
    // =====================
    $insert_query = "
        INSERT INTO vehicles (user_id, brand, model, cc, type)
        VALUES ($1, $2, $3, $4, $5)
    ";

    $result = pg_query_params($conn, $insert_query, [
        $user_id,
        $brand,
        $model,
        $cc,
        $type
    ]);

    if ($result) {
        header("Location: dashboard.php?success=Vehicle added!");
        exit();
    } else {
        header("Location: add_vehicle.php?error=Failed to add vehicle!");
        exit();
    }
}
?>