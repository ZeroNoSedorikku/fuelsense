<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    exit("Access denied");
}

$user_id = $_SESSION['user_id'];
$vehicle_id = $_GET['vehicle_id'] ?? null;

if (!$vehicle_id) {
    exit("No vehicle selected");
}

// Get vehicle info
$vehicle_query = "
    SELECT brand, model
    FROM vehicles
    WHERE vehicle_id = $1 AND user_id = $2
";

$vehicle_result = pg_query_params($conn, $vehicle_query, [$vehicle_id, $user_id]);
$vehicle = pg_fetch_assoc($vehicle_result);

if (!$vehicle) {
    exit("Invalid vehicle");
}

// Fuel logs (filtered)
$query = "
    SELECT *
    FROM fuel_logs
    WHERE user_id = $1 AND vehicle_id = $2
    ORDER BY date DESC
";

$result = pg_query_params($conn, $query, [$user_id, $vehicle_id]);

// Total fuel + cost
$total_query = "
    SELECT 
        COALESCE(SUM(liters),0) AS total_liters,
        COALESCE(SUM(cost),0) AS total_cost
    FROM fuel_logs
    WHERE user_id = $1 AND vehicle_id = $2
";

$total_result = pg_query_params($conn, $total_query, [$user_id, $vehicle_id]);
$total_data = pg_fetch_assoc($total_result);
?>

<!DOCTYPE html>
<html>
<head>
<title>Fuel Logs</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin:0;
    font-family: Arial;
    background:#000;
    color:#fff;
}

.header {
    text-align:center;
    padding:20px;
    border-bottom:2px solid #0ff;
}

.container {
    padding:15px;
}

.table-wrapper {
    overflow-x:auto;
}

table {
    width:100%;
    min-width:500px;
    border-collapse: collapse;
}

th, td {
    padding:10px;
    text-align:center;
}

th {
    color:#0ff;
}

tr:hover {
    background:#111;
}

.back {
    text-align:center;
    margin-top:20px;
}
</style>
</head>

<body>

<div class="header">
    <h2>⛽ Fuel Logs</h2>
    <p><?= htmlspecialchars($vehicle['brand']) ?> <?= htmlspecialchars($vehicle['model']) ?></p>

    <p style="color:#0ff;">
        Total: <?= number_format($total_data['total_liters'],2) ?> L |
        ₱<?= number_format($total_data['total_cost'],2) ?>
    </p>
</div>

<div class="container">
<div class="table-wrapper">
<table>

<tr>
    <th>Date</th>
    <th>Liters</th>
    <th>Cost</th>
</tr>

<?php if (pg_num_rows($result) > 0): ?>
    <?php while ($row = pg_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['date'] ?></td>
        <td><?= $row['liters'] ?></td>
        <td>₱<?= $row['cost'] ?></td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
<tr><td colspan="3">No records</td></tr>
<?php endif; ?>

</table>
</div>

<div class="back">
    <a href="dashboard.php?vehicle_id=<?= $vehicle_id ?>">⬅ Back</a>
</div>

</div>
</body>
</html>