<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    exit("Access denied");
}

$user_id = $_SESSION['user_id'];

$result = pg_query_params($conn,
    "SELECT * FROM fuel_logs WHERE user_id = $1 ORDER BY date DESC",
    [$user_id]
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fuel Logs</title>

    <style>
        body { font-family: Arial; background:#111; color:white; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:10px; border:1px solid cyan; text-align:center; }
        th { color:cyan; }
        a { color:red; }
    </style>
</head>
<body>

<h2>Your Fuel Logs</h2>

<table>
<tr>
    <th>Date</th>
    <th>Liters</th>
    <th>Cost</th>
    <th>Action</th>
</tr>

<?php while ($row = pg_fetch_assoc($result)): ?>
<tr>
    <td><?= $row['date'] ?></td>
    <td><?= $row['liters'] ?></td>
    <td><?= $row['cost'] ?></td>
    <td>
        <a href="delete_fuel.php?id=<?= $row['id'] ?>"
           onclick="return confirm('Delete this record?')">
           Delete
        </a>
    </td>
</tr>
<?php endwhile; ?>
</table>

<br>
<a href="dashboard.php">⬅ Back</a>

</body>
</html>