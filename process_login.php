<?php
session_start();
include 'db.php';

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Get user safely
    $query = "SELECT * FROM users WHERE email = $1";
    $result = pg_query_params($conn, $query, [$email]);

    if (pg_num_rows($result) > 0) {

        $user = pg_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

    // Store session data
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    // Redirect based on role
    if ($user['role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();

    } else {
            $message = "❌ Invalid password!";
        }

    } else {
        $message = "❌ User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Status - FuelSense</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at top, #0d0d0d, #000);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .box {
            background: rgba(10,10,10,0.9);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #0ff;
            box-shadow: 0 0 20px #0ff;
            width: 340px;
            text-align: center;
        }

        h2 {
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
        }

        .message {
            margin: 20px 0;
            padding: 15px;
            border-radius: 10px;
            font-size: 14px;
            border: 1px solid #ff004c;
            color: #ff004c;
            box-shadow: 0 0 10px #ff004c;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            border: 1px solid #ff00ff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        a:hover {
            background: #ff00ff;
            color: black;
            box-shadow: 0 0 15px #ff00ff;
        }

    </style>
</head>
<body>

<div class="box">
    <h2>⚡ Login Status</h2>

    <div class="message">
        <?php echo $message; ?>
    </div>

    <a href="login.php">Try Again</a>
</div>

</body>
</html>