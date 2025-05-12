<?php
session_start(); // Start session to store user data
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        die("<script>window.history.back();</script>");
    }

    // Prepare SQL statement to check user existence
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    if (!$stmt) {
        die("Error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Successful login, store session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;

            // Redirect to neworder.php without alert
            header("Location: neworder.php");
            exit();
        } else {
            echo "<script>window.history.back();</script>";
        }
    } else {
        echo "<script>window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrewLane - Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            background: url(cc.jpg) no-repeat center center/cover;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .header {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
        }
        .header img {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }
        .header h1 {
            font-size: 24px;
            color:rgb(254, 254, 254);
            margin: 0;
        }
        .container {
            background: #F6F0F0;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        .container h3 {
            margin-bottom: 20px;
            font-size: 20px;
            color: #854836;
        }
        .input-field {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 25px;
            background-color: #faebd7;
            font-size: 16px;
            text-align: left;
            box-sizing: border-box;
        }
        .btn {
            background-color: #854836;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #733d2b;
        }
        .links {
            margin-top: 15px;
            font-size: 14px;
        }
        .links a {
            color: #854836;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="Logo1.png" alt="BrewLane Logo">
        <h1>BrewLane Cafe</h1>
    </div>
    <div class="container">
        <h3>LOG IN</h3>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" class="input-field" required>
            <input type="password" name="password" placeholder="Password" class="input-field" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="links">
            <p><a href="signup.php">SIGN UP</a></p>
        </div>
    </div>
</body>
</html>
