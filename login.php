<!DOCTYPE html>
<html>
<head>
    <title>Login - FuelSense</title>

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
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: rgba(10,10,10,0.9);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #0ff;
            box-shadow: 0 0 20px #0ff;
            width: 320px;
            text-align: center;
        }

        h2 {
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
            margin-bottom: 25px;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: #ff00ff;
        }

        input {
            width: 100%;
            padding: 10px;
            background: transparent;
            border: 1px solid #0ff;
            border-radius: 8px;
            color: white;
            outline: none;
        }

        input:focus {
            box-shadow: 0 0 10px #0ff;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: transparent;
            border: 1px solid #ff00ff;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #ff00ff;
            color: black;
            box-shadow: 0 0 15px #ff00ff;
        }

        .register {
            margin-top: 15px;
            font-size: 13px;
        }

        .register a {
            color: #0ff;
            text-decoration: none;
        }

        .register a:hover {
            text-shadow: 0 0 10px #0ff;
        }

    </style>
</head>
<body>

<div class="login-box">
    <h2>⚡ FuelSense Login</h2>

    <form method="POST" action="process_login.php">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <div class="register">
        <p>Don't have an account?</p>
        <a href="register.php">Register here</a>
    </div>
</div>

</body>
</html>