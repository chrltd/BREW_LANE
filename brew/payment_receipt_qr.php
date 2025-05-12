<?php
require_once 'placeorder.php'; 

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    die("Invalid order ID.");
}

$orderQuery = "SELECT * FROM orders WHERE id = $order_id";
$orderResult = mysqli_query($conn, $orderQuery);
$order = mysqli_fetch_assoc($orderResult);

$itemsQuery = "SELECT * FROM order_items WHERE order_id = $order_id";
$itemsResult = mysqli_query($conn, $itemsQuery);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Receipt - BrewLane</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f5f5f5;
            text-align: center;
        }

        .receipt {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #854836;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background: #854836;
            color: #fff;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .qr {
            margin-top: 30px;
        }

        .qr img {
            max-width: 200px;
        }
    </style>
</head>
<body>

<div class="receipt">
    <h2>BrewLane Cafe</h2>
    <p><strong>Order ID:</strong> <?= $order['id'] ?></p>
    <p><strong>Payment Method:</strong> <?= $order['payment_method'] ?></p>
    <p><strong>Order Date:</strong> <?= $order['order_date'] ?></p>

    <table>
        <thead>
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($item = mysqli_fetch_assoc($itemsResult)): ?>
            <tr>
                <td><?= htmlspecialchars($item['item_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>₱<?= number_format($item['price'], 2) ?></td>
                <td>₱<?= number_format($item['total'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="total">
        Total: ₱<?= number_format($order['order_total'], 2) ?>
    </div>

    <?php if (strtolower($order['payment_method']) === 'gcash'): ?>
        <div class="qr">
            <p>Please scan the GCash QR code to pay:</p>
            <img src="gcash_qr.png" alt="GCash QR Code">
        </div>
    <?php endif; ?>
</div>

</body>
</html>
