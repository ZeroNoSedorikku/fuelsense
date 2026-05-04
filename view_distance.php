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
    SELECT *
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
        body {
            margin: 0;
            font-family: Arial;
            background: #000;
            color: white;
        }

        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #0ff;
        }

        .header h2 {
            margin: 0;
            color: #0ff;
        }

        .container {
            padding: 15px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 500px;
            border-collapse: collapse;
            background: #111;
        }

        th, td {
            padding: 10px;
            text-align: center;
            font-size: 13px;
        }

        th {
            color: #0ff;
            border-bottom: 1px solid #0ff;
        }

        tr:hover {
            background: #222;
        }

        .delete-btn {
            color: red;
            text-decoration: none;
            border: 1px solid red;
            padding: 5px 8px;
            border-radius: 5px;
        }

        .delete-btn:hover {
            background: red;
            color: black;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #888;
        }

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
        }

        .back a:hover {
            background: #0ff;
            color: black;
        }
    </style>
</head>

<body>

<div class="header">
    <h2>📏 Distance Logs</h2>

    <p style="color:#ff00ff;">
        <?= htmlspecialchars($vehicle['brand']) ?> <?= htmlspecialchars($vehicle['model']) ?>
    </p>

    <p style="color:#0ff;">
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
    <th>Action</th>
</tr>

<?php if (pg_num_rows($result) > 0): ?>
    <?php while ($row = pg_fetch_assoc($result)): ?>
    <tr>
        <td><?= htmlspecialchars($row['date']) ?></td>
        <td><?= htmlspecialchars($row['distance_km']) ?></td>
        <td><?= htmlspecialchars($row['mode']) ?></td>
        <td>
            <a class="delete-btn"
               href="delete_distance.php?id=<?= $row['distance_id'] ?>"
               onclick="return confirm('⚠ Delete this record?')">
               Delete
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="empty">No distance records found</td>
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