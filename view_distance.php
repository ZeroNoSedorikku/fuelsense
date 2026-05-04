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

// =====================
// VEHICLE INFO
// =====================
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

// =====================
// DISTANCE LOGS
// =====================
$query = "
    SELECT date, distance_km, mode
    FROM distance_logs
    WHERE user_id = $1 AND vehicle_id = $2
    ORDER BY date DESC
";

$result = pg_query_params($conn, $query, [$user_id, $vehicle_id]);

// =====================
// TOTAL DISTANCE
// =====================
$total_query = "
    SELECT COALESCE(SUM(distance_km), 0) AS total
    FROM distance_logs
    WHERE user_id = $1 AND vehicle_id = $2
";

$total_result = pg_query_params($conn, $total_query, [$user_id, $vehicle_id]);
$total_data = pg_fetch_assoc($total_result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Distance Logs - FuelSense</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
       * {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Orbitron', sans-serif;
    background: radial-gradient(circle at top, #0d0d0d, #000);
    color: white;
}

/* ================= HEADER ================= */
.header {
    text-align: center;
    padding: 20px;
    border-bottom: 2px solid #0ff;
    box-shadow: 0 0 15px #0ff;
}

.header h2 {
    margin: 0;
    color: #0ff;
    text-shadow: 0 0 10px #0ff;
}

.vehicle-name {
    color: #ff00ff;
    margin-top: 5px;
    text-shadow: 0 0 10px #ff00ff;
}

.total {
    color: #0ff;
    margin-top: 5px;
}

/* ================= CONTAINER ================= */
.container {
    padding: 15px;
}

/* ================= TABLE ================= */
.table-wrapper {
    overflow-x: auto;
    margin-top: 15px;
}

table {
    width: 100%;
    min-width: 400px;
    border-collapse: collapse;
    background: rgba(10,10,10,0.9);
    border: 1px solid #0ff;
    box-shadow: 0 0 15px #0ff;
}

th, td {
    padding: 10px;
    text-align: center;
    font-size: 13px;
}

th {
    color: #ff00ff;
    border-bottom: 1px solid #ff00ff;
    text-shadow: 0 0 10px #ff00ff;
}

td {
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

tr:hover {
    background: rgba(255, 0, 255, 0.1);
}

/* ================= EMPTY ================= */
.empty {
    text-align: center;
    padding: 20px;
    color: #888;
}

/* ================= BACK BUTTON ================= */
.back {
    text-align: center;
    margin-top: 20px;
}

.back a {
    display: inline-block;
    padding: 10px 15px;
    border: 1px solid #0ff;
    border-radius: 8px;
    color: #0ff;
    text-decoration: none;
    transition: 0.3s;
}

.back a:hover {
    background: #0ff;
    color: black;
    box-shadow: 0 0 10px #0ff;
}

/* ================= MOBILE ================= */
@media (max-width: 480px) {

    .header h2 {
        font-size: 18px;
    }

    .vehicle-name,
    .total {
        font-size: 13px;
    }

    th, td {
        font-size: 12px;
        padding: 8px;
    }

    .back a {
        width: 100%;
        display: block;
    }
}
    </style>
</head>

<body>

<div class="header">
    <h2>📏 Distance Logs</h2>

    <p class="vehicle-name">
        <?= htmlspecialchars($vehicle['brand']) ?> <?= htmlspecialchars($vehicle['model']) ?>
    </p>

    <p class="total">
        Total: <?= number_format($total_data['total'], 2) ?> km
    </p>
</div>

<div class="container">

<div class="table-wrapper">
<table>

<tr>
    <th>Date</th>
    <th>Distance (km)</th>
    <th>Mode</th>
</tr>

<?php if (pg_num_rows($result) > 0): ?>
    <?php while ($row = pg_fetch_assoc($result)): ?>
    <tr>
        <td><?= htmlspecialchars($row['date']) ?></td>
        <td><?= htmlspecialchars($row['distance_km']) ?></td>
        <td><?= htmlspecialchars($row['mode']) ?></td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="3" class="empty">No distance records found</td>
    </tr>
<?php endif; ?>

</table>
</div>

<div class="back">
    <a href="dashboard.php?vehicle_id=<?= $vehicle_id ?>">⬅ Back to Dashboard</a>
</div>

</div>

</body>
</html>