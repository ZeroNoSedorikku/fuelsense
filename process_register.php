<?php
session_start();
include 'db.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

/* ---------------- VALIDATION ---------------- */

// check empty
if (empty($name) || empty($email) || empty($password)) {
    $_SESSION['error'] = "All fields are required!";
    header("Location: register.php");
    exit();
}

// password match
if ($password !== $confirm) {
    $_SESSION['error'] = "Passwords do not match!";
    header("Location: register.php");
    exit();
}

/* ---------------- CHECK EMAIL ---------------- */

$check = pg_query_params(
    $conn,
    "SELECT user_id FROM users WHERE email = $1",
    [$email]
);

if (pg_num_rows($check) > 0) {
    $_SESSION['error'] = "Email already exists!";
    header("Location: register.php");
    exit();
}

/* ---------------- HASH PASSWORD ---------------- */

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

/* ---------------- INSERT USER ---------------- */

$query = "
    INSERT INTO users (name, email, password, role)
    VALUES ($1, $2, $3, 'user')
";

$result = pg_query_params(
    $conn,
    $query,
    [$name, $email, $hashed_password]
);

if ($result) {
    $_SESSION['success'] = "Registration successful!";
    header("Location: login.php");
    exit();
} else {
    $_SESSION['error'] = "Something went wrong.";
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Result - FuelSense</title>

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
            align-items: center;
            height: 100vh;
        }

        .box {
            background: rgba(10,10,10,0.9);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #0ff;
            box-shadow: 0 0 20px #0ff;
            width: 350px;
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
        }

        .success {
            border: 1px solid #0f0;
            color: #0f0;
            box-shadow: 0 0 10px #0f0;
        }

        .error {
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
    <h2>⚡ Registration Status</h2>

    <div class="message <?php echo $success ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
    </div>

    <?php if ($success): ?>
        <a href="login.php">Go to Login</a>
    <?php else: ?>
        <a href="register.php">Try Again</a>
    <?php endif; ?>

</div>

</body>
</html>

$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if ($password !== $confirm) {
    header("Location: register.php?error=Passwords do not match");
    exit();
}

// hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// prevent duplicate email
$check = pg_query_params($conn,
    "SELECT * FROM users WHERE email = $1",
    [$_POST['email']]
);

if (pg_num_rows($check) > 0) {
    header("Location: register.php?error=Email already exists");
    exit();
}