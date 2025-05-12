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
            margin: 0;
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
            color:rgb(250, 249, 248);
            margin: 0;
        }

        .nav-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .nav-buttons a {
            text-decoration: none;
            color: white;
            background-color: #854836;
            padding: 8px 15px;
            border-radius: 15px;
            margin: 0 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .nav-buttons a:hover {
            background-color: #ffffff;
            color: #854836;
            border: 1px solid #854836;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
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
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #6d3c28;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="Logo1.png" alt="BrewLane Logo">
        <h1>Brewlane Cafe</h1>
    </div>

    <div class="nav-buttons">
        <a href="neworder.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="login.php">Sign up</a>
        <a href="index.php">Log out</a>
    </div>

    <div class="container">
        <button class="btn" onclick="location.href='menu.php'">New Order</button>
        <button class="btn" onclick="location.href='addmenu.php'">Add Menu</button>
    </div>
</body>
</html>
