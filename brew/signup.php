<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['message'] = "All fields are required!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $checkEmail->store_result();

        if ($checkEmail->num_rows > 0) {
            $_SESSION['message'] = "Email already registered!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Registration successful!";
            } else {
                $_SESSION['message'] = "Registration failed: " . $stmt->error;
            }
            $stmt->close();
        }
        $checkEmail->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrewLane - Sign Up</title>
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
            color:rgb(252, 252, 251);
            margin: 0;
        }
        .container {
            background: #F7F7F7;
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
        .message {
            background: #D4EDDA;
            color: #155724;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            text-align: center;
            display: <?php echo isset($_SESSION['message']) ? 'block' : 'none'; ?>;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="Logo1.png" alt="BrewLane Logo">
        <h1>BrewLane Cafe</h1>
    </div>
    <div class="container">
        <h3>SIGN UP</h3>
        
        <!-- Notification message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <form action="signup.php" method="POST">
            <input type="text" name="name" placeholder="Full Name" class="input-field" required>
            <input type="email" name="email" placeholder="Email" class="input-field" required>
            <input type="password" name="password" placeholder="Password" class="input-field" required>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <div class="links">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
