<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    exit("Access denied");
}

$result = pg_query($conn, "SELECT user_id, email, role FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <div class="page-wrapper">
    <title>Users - Admin</title>

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

            display: flex;
            justify-content: center;   /* horizontal center */
            align-items: flex-start;   /* top align (better for scrolling) */
            min-height: 100vh;
        }
        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #ff004c;
            box-shadow: 0 0 15px #ff004c;
        }

        .header h2 {
            margin: 0;
            color: #ff004c;
            text-shadow: 0 0 10px #ff004c;
        }

        .container {
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(10,10,10,0.9);
            border: 1px solid #ff004c;
            box-shadow: 0 0 20px #ff004c;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            color: #ff004c;
            border-bottom: 1px solid #ff004c;
            text-shadow: 0 0 10px #ff004c;
        }

        td {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        tr:hover {
            background: rgba(255, 0, 76, 0.1);
            box-shadow: 0 0 10px #ff004c inset;
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

        .you {
            color: #888;
            font-size: 12px;
        }

        .back {
            margin-top: 20px;
            text-align: center;
        }

        .back a {
            display: inline-block;
            padding: 10px 15px;
            border: 1px solid #ff004c;
            border-radius: 8px;
            color: #ff004c;
            text-decoration: none;
            transition: 0.3s;
        }

        .back a:hover {
            background: #ff004c;
            color: black;
            box-shadow: 0 0 15px #ff004c;
        }
        .page-wrapper {
            width: 100%;
            max-width: 1000px;  /* controls centered width */
            padding: 20px;
        }

    </style>
    </div>
</head>

<div class="page-wrapper">
<div class="header">
    <h2>⚠ Admin - User Management</h2>
</div>

<div class="container">

<table>
<tr>
    <th>ID</th>
    <th>Email</th>
    <th>Role</th>
    <th>Action</th>
</tr>

<?php while ($row = pg_fetch_assoc($result)): ?>
<tr>
    <td><?= htmlspecialchars($row['user_id']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['role']) ?></td>
    <td>
        <?php if ($row['user_id'] != $_SESSION['user_id']): ?>
            <a class="delete-btn"
               href="delete_user.php?id=<?= $row['user_id'] ?>"
               onclick="return confirm('⚠ Delete this user?')">
               Delete
            </a>
        <?php else: ?>
            <span class="you">You</span>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>

</table>

<div class="back">
    <a href="admin_dashboard.php">⬅ Back to Admin Panel</a>
</div>

</div>
