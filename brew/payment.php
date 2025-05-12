<?php
session_start();
require_once 'placeorder.php';  // contains your DB connection ($conn)

$cart = $_SESSION['cart'] ?? [];
$total_price = array_sum(array_column($cart, 'total'));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($cart)) {
    $payment_method = $_POST['payment_method'];
    $order_date = date('Y-m-d H:i:s');


    $insertOrder = "INSERT INTO orders (order_total, payment_method, order_date) 
                    VALUES ('$total_price', '$payment_method', '$order_date')";
    $orderResult = mysqli_query($conn, $insertOrder);

    if ($orderResult) {
        $order_id = mysqli_insert_id($conn);
        foreach ($cart as $item) {
            $item_name = mysqli_real_escape_string($conn, $item['item']);
            $quantity = (int) $item['quantity'];
            $price = (float) $item['price'];
            $total = (float) $item['total'];

            $insertItem = "INSERT INTO order_items (order_id, item_name, quantity, price, total) 
                           VALUES ('$order_id', '$item_name', '$quantity', '$price', '$total')";
            mysqli_query($conn, $insertItem);
        }

        $_SESSION['last_order_id'] = $order_id;
        $_SESSION['last_payment_method'] = $payment_method;
        unset($_SESSION['cart']);

        header("Location: payment_receipt_qr.php?order_id=$order_id");
        exit();
    } else {
        $error = "Something went wrong while placing the order. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BrewLane Cafe - Payment</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url(cc.jpg) no-repeat center center/cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 80px;
        }

        .header {
            position: fixed;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            z-index: 1000;
        }

        .header img {
            width: 50px;
            margin-right: 10px;
        }

        .header h1 {
            color:rgb(252, 250, 249);
            font-size: 26px;
            margin: 0;
        }

        .nav-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .nav-buttons a {
            text-decoration: none;
            color: white;
            background-color: #854836;
            padding: 10px 16px;
            border-radius: 20px;
            margin: 0 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .nav-buttons a:hover {
            background-color: #6d3c28;
        }

        .container {
            width: 100%;
            max-width: 650px;
            background-color: #ffffffee;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            margin: 40px 20px;
        }

        h2 {
            color: #854836;
            margin-bottom: 25px;
            font-size: 28px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #854836;
            color: white;
            font-size: 16px;
        }

        td {
            font-size: 15px;
        }

        .total {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 25px;
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .error {
            color: red;
        }

        form {
            text-align: center;
        }

        select {
            padding: 14px 18px;
            font-size: 16px; /* Slightly smaller font size */
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            width: 70%;
            max-width: 170px; /* Width limitation */
        }

        button {
            background-color: #854836;
            color: white;
            border: none;
            padding: 12px 28px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #6d3c28;
        }

        @media (max-width: 768px) {
            .container {
                padding: 25px;
                margin-top: 120px;
            }

            table, th, td {
                font-size: 13px;
                padding: 10px;
            }

            .nav-buttons a {
                padding: 6px 12px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <img src="Logo1.png" alt="BrewLane Logo">
    <h1>BrewLane Cafe</h1>
</div>

<div class="nav-buttons">
    <a href="neworder.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="login.php">Login</a>
    <a href="signup.php">Sign Up</a>
</div>

<div class="container">
    <h2>Payment</h2>

    <?php if (isset($error)): ?>
        <p class="message error"><?= $error ?></p>
    <?php endif; ?>

    <?php if (!empty($cart)): ?>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['item']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td>₱<?= number_format($item['total'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total">
            Total: ₱<?= number_format($total_price, 2) ?>
        </div>

        <form method="POST">
            <label for="payment_method">Choose Payment Method:</label><br>
            <select name="payment_method" required>
                <option value="Cash">Cash</option>
                <option value="GCash">GCash</option>
            </select><br>
            <button type="submit">Confirm Order</button>
        </form>
    <?php else: ?>
        <h3 style="text-align:center;">Your cart is empty.</h3>
    <?php endif; ?>
</div>

</body>
</html>
