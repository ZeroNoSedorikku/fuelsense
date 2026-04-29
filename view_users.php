<?php
session_start();
include 'db.php';

if ($_SESSION['role'] != 'admin') {
    exit("Access denied");
}

$result = pg_query($conn, "SELECT user_id, email, role FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users</title>

    <style>
        body { font-family: Arial; background:#111; color:white; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:10px; border:1px solid red; text-align:center; }
        th { background:black; color:red; }
        a { color:red; text-decoration:none; }
    </style>
</head>
<body>

<h2>Users</h2>

<table>
<tr>
    <th>ID</th>
    <th>Email</th>
    <th>Role</th>
    <th>Action</th>
</tr>

<?php while ($row = pg_fetch_assoc($result)): ?>
<tr>
    <td><?= $row['user_id'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['role'] ?></td>
    <td>
        <?php if ($row['user_id'] != $_SESSION['user_id']): ?>
            <a href="delete_user.php?id=<?= $row['user_id'] ?>"
               onclick="return confirm('Delete this user?')">
               Delete
            </a>
        <?php else: ?>
            <span style="color:gray;">You</span>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>

</table>

<br>
<a href="admin_dashboard.php">⬅ Back</a>

</body>
</html>