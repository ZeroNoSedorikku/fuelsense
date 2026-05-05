<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $type = trim($_POST['type'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $cc = (int) ($_POST['cc'] ?? 0);

    // =====================
    // VALIDATION
    // =====================
    if (empty($type) || empty($brand) || empty($model) || $cc <= 0) {
        header("Location: add_vehicle.php?error=Invalid input!");
        exit();
    }

    // =====================
    // DUPLICATE CHECK
    // =====================
    $check = pg_query_params(
        $conn,
        "SELECT COUNT(*) FROM vehicles 
         WHERE user_id = $1 
         AND LOWER(brand) = LOWER($2)
         AND LOWER(model) = LOWER($3)
         AND cc = $4",
        [$user_id, $brand, $model, $cc]
    );

    $exists = pg_fetch_result($check, 0, 0);

    if ($exists > 0) {
        header("Location: add_vehicle.php?error=Vehicle already exists!");
        exit();
    }

    // =====================
    // INSERT
    // =====================
    $query = "INSERT INTO vehicles (user_id, type, brand, model, cc)
              VALUES ($1, $2, $3, $4, $5)";

    $result = pg_query_params($conn, $query, [
        $user_id,
        $type,
        $brand,
        $model,
        $cc
    ]);

    if ($result) {
        header("Location: dashboard.php?success=Vehicle added!");
        exit();
    } else {
        header("Location: add_vehicle.php?error=Failed to save!");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Vehicle</title>

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
    min-height: 100vh;
    padding: 15px;
}

* {
    box-sizing: border-box;
}

.container {
    background: rgba(10,10,10,0.9);
    padding: 25px;
    border-radius: 15px;
    border: 1px solid #0ff;
    box-shadow: 0 0 20px #0ff;
    width: 90%;
    max-width: 400px;
}
h2 {
    text-align: center;
    color: #0ff;
}

label {
    display: block;
    margin-top: 10px;
    color: #ff00ff;
    text-align: left;
}

input, select {
    width: 100%;
    padding: 12px;
    margin-top: 6px;
    margin-bottom: 12px;
    background: transparent;
    border: 1px solid #0ff;
    border-radius: 8px;
    color: white;
    outline: none;
    display: block;
}

button {
    width: 100%;
    margin-top: 20px;
    padding: 12px;
    border: 1px solid #ff00ff;
    background: transparent;
    color: white;
    border-radius: 8px;
    cursor: pointer;
}

button:hover {
    background: #ff00ff;
    color: black;
}

.msg {
    text-align: center;
    margin-bottom: 10px;
}
.error {
    color: red;
}
.success {
    color: #0f0;
}
.back {
    margin-top: 15px;
    text-align: center;
}

.back a {
    display: inline-block;
    padding: 8px 12px;
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
</style>

</head>
<body>

<div class="container">

<h2>🚗 Add Vehicle</h2>

<?php if (isset($_GET['error'])): ?>
    <p class="msg error">⚠ <?= htmlspecialchars($_GET['error']) ?></p>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <p class="msg success">✔ <?= htmlspecialchars($_GET['success']) ?></p>
<?php endif; ?>

<form method="POST">
<label>Type</label>
<select name="type" required>
    <option value="">Select Type</option>
    <option value="Motorcycle">Motorcycle</option>
    <option value="Car">Car</option>
</select>

<label>Brand</label>
<input type="text" name="brand" placeholder="e.g. Yamaha" required>

<label>Model</label>
<input type="text" name="model" placeholder="e.g. YTX 125" required>

<label>CC</label>
<input type="number" name="cc" placeholder="e.g. 125" required>

<button type="submit">Save Vehicle</button>

</form>
<div class="back">
    <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>
</div>

</body>
</html>