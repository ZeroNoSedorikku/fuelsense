<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'admin') {
    exit("Access denied");
}

$query = "
SELECT f.*, u.email 
FROM fuel_logs f
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
        @media (max-width: 600px) {

    h2 {
        font-size: 18px;
    }

    .card {
        width: 100%;
    }

    .header h2 {
        font-size: 18px;
    }

    .logout {
        top: 5px;
        right: 5px;
    }

}
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at top, #0d0d0d, #000);
            color: white;
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
        }

        .container {
            padding: 30px;
        }

        table {
            width: 100%;
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
            padding: 10px;
            text-decoration: none;
        }

        .back a:hover {
            background: red;
            color: black;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>⛽ Fuel Logs</h2>
</div>

<div class="container">

<table>
<tr>
    <th>User</th>
    <th>Date</th>
    <th>Liters</th>
    <th>Cost</th>
</tr>

<?php while ($row = pg_fetch_assoc($result)): ?>
<tr>
    <td><?= $row['email'] ?></td>
    <td><?= $row['date'] ?></td>
    <td><?= $row['liters'] ?></td>
    <td>₱<?= $row['cost'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<div class="back">
    <a href="admin_dashboard.php">⬅ Back</a>
</div>

</div>

</body>
</html>