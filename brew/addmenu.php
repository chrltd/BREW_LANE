<?php
session_start();
require_once 'placeorder.php'; // should include $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (float) $_POST['price'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);

    $query = "INSERT INTO menu (name, price, category, size, type) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sdsss", $name, $price, $category, $size, $type);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: menu.php?added=1");
        exit();
    } else {
        $error = mysqli_stmt_error($stmt);
        header("Location: menu.php?error=" . urlencode($error));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Menu Item</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            flex-direction: column;
            background: url(cc.jpg) no-repeat center center/cover;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
        }

        .header {
            position: fixed;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
        }

        .header img {
            width: 50px;
            margin-right: 10px;
        }

        .header h1 {
            color:rgb(255, 252, 252);
            font-size: 24px;
            margin: 0;
        }

        .nav-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
        }

        .nav-buttons a {
            text-decoration: none;
            color: white;
            background-color: #854836;
            padding: 8px 15px;
            border-radius: 20px;
            margin: 0 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .nav-buttons a:hover {
            background-color: #6d3c28;
        }

        .container {
            width: 90%;
            max-width: 500px;
            margin: 140px auto 40px;
            background-color: #f6f0f0;
            padding: 30px 20px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .container h2 {
            text-align: center;
            color: #854836;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #854836;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #6d3c28;
        }

        @media (max-width: 400px) {
            .container {
                padding: 20px 15px;
            }
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
    <h2>Add New Menu</h2>
    <form method="post" action="addmenu.php">
        <label for="name">Item Name:</label>
        <input type="text" name="name" placeholder="Enter item name" required>

        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" placeholder="Enter price" required>

        <label for="category">Category:</label>
        <select name="category" required>
            <option value="Desserts">Desserts</option>
            <option value="Drinks">Drinks</option>
            <option value="Coffee">Coffee</option>
        </select>

        <label for="size">Size:</label>
        <select name="size" required>
            <option value="Small">Small</option>
            <option value="Medium">Medium</option>
            <option value="Large">Large</option>
        </select>

        <label for="type">Type:</label>
        <select name="type" required>
            <option value="Hot">Hot</option>
            <option value="Iced">Iced</option>
        </select>

        <button type="submit">Add Item</button>
    </form>
</div>

</body>
</html>