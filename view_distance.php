<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    exit("Access denied");
}

$user_id = $_SESSION['user_id'];

$result = pg_query_params(
    $conn,
    "SELECT * FROM distance_logs WHERE user_id = $1 ORDER BY date DESC",
    [$user_id]
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Distance Logs - FuelSense</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at top, #0d0d0d, #000);
            color: white;
        }

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

        .container {
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(10,10,10,0.9);
            border: 1px solid #0ff;
            box-shadow: 0 0 20px #0ff;
        }

        th, td {
            padding: 12px;
            text-align: center;
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
            box-shadow: 0 0 10px #ff00ff inset;
        }

        .delete-btn {
            color: #ff004c;
            text-decoration: none;
            border: 1px solid #ff004c;
            padding: 6px 10px;
            border-radius: 6px;
            transition: 0.3s;
            font-size: 12px;
        }

        .delete-btn:hover {
            background: #ff004c;
            color: black;
            box-shadow: 0 0 10px #ff004c;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #888;
        }

        .back {
            margin-top: 20px;
            text-align: center;
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
            box-shadow: 0 0 15px #0ff;
        }

    </style>
</head>
<body>

<div class="header">
    <h2>📏 Your Distance Logs</h2>
</div>

<div class="container">

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
               href="delete_distance.php?id=<?= $row['id'] ?>"
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

<div class="back">
    <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</div>

</body>
</html>