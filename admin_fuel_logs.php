<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    exit("Access denied");
}

$query = "
SELECT 
    f.*, 
    v.type,
    v.brand,
    v.model,
    v.cc,
    u.name AS user_name
FROM fuel_logs f
JOIN vehicles v ON f.vehicle_id = v.vehicle_id
JOIN users u ON f.user_id = u.user_id
ORDER BY f.date DESC
";

$result = pg_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fuel Logs</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at top, #0d0d0d, #000);
            color: white;

            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .page-wrapper {
            width: 100%;
            max-width: 1000px;
            padding: 20px;
        }

        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid red;
            box-shadow: 0 0 15px red;
        }

        h2 {
            color: red;
            text-shadow: 0 0 10px red;
            margin: 0;
        }

        .container {
            margin-top: 20px;
        }

        /* ✅ IMPORTANT: mobile fix */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 500px; /* prevents squeezing */
            border-collapse: collapse;
            background: rgba(10,10,10,0.9);
            border: 1px solid red;
            box-shadow: 0 0 15px red;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            color: #ff4d4d;
            border-bottom: 1px solid red;
        }

        tr:hover {
            background: rgba(255,0,0,0.1);
        }

        .back {
            margin-top: 20px;
            text-align: center;
        }

        .back a {
            color: white;
            border: 1px solid red;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
        }

        .back a:hover {
            background: red;
            color: black;
        }

        /* ✅ mobile polish */
        @media (max-width: 600px) {
            h2 {
                font-size: 18px;
            }

            th, td {
                font-size: 12px;
                padding: 8px;
            }
        }
    </style>
</head>

<body>

<div class="page-wrapper">

    <div class="header">
        <h2>⛽ Fuel Logs</h2>
    </div>

    <div class="container">

        <div class="table-wrapper">
            <table>
        <tr>
            <th>User</th>
            <th>Vehicle</th>
            <th>Type</th>
            <th>CC</th>
            <th>Date</th>
            <th>Liters</th>
            <th>Cost</th>
        </tr>

        <?php while ($row = pg_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['brand'] . ' ' . $row['model']) ?></td>
            <td><?= htmlspecialchars($row['type']) ?></td>
            <td><?= htmlspecialchars($row['cc']) ?>cc</td>
            <td><?= htmlspecialchars($row['date']) ?></td>
            <td><?= htmlspecialchars($row['liters']) ?> L</td>
            <td>₱<?= htmlspecialchars($row['cost']) ?></td>
        </tr>
        <?php endwhile; ?>
            </table>
        </div>

        <div class="back">
            <a href="admin_dashboard.php">⬅ Back</a>
        </div>

    </div>

</div>

</body>
</html>