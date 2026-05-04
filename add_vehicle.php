<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $type = $_POST['type'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $cc = (int) $_POST['cc'];

    $query = "INSERT INTO vehicles (user_id, type, brand, model, cc)
              VALUES ($1, $2, $3, $4, $5)";

    pg_query_params($conn, $query, [$user_id, $type, $brand, $model, $cc]);

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Vehicle</title>
</head>
<body>

<h2>Add Vehicle</h2>

<form method="POST">

<label>Type</label>
<select name="type" required>
    <option value="Motorcycle">Motorcycle</option>
    <option value="Car">Car</option>
</select>

<label>Brand</label>
<input type="text" name="brand" required>

<label>Model</label>
<input type="text" name="model" required>

<label>CC</label>
<input type="number" name="cc" required>

<button type="submit">Save Vehicle</button>

</form>

</body>
</html>