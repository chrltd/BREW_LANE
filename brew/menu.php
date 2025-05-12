<?php
session_start();
require_once 'placeorder.php'; // Should include $conn

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $item = $_POST['item'];
    $quantity = intval($_POST['quantity']);
    $type = $_POST['type'];
    $size = $_POST['size'];
    $basePrice = floatval($_POST['price']);

    // Price adjustment based on size
    if ($size === 'Medium') {
        $price = $basePrice + 10;
    } elseif ($size === 'Large') {
        $price = $basePrice + 20;
    } else {
        $price = $basePrice;
    }

    $total = $price * $quantity;

    $_SESSION['cart'][] = [
        'item' => $item,
        'size' => $size,
        'type' => $type,
        'quantity' => $quantity,
        'price' => $price,
        'total' => $total
    ];
}

// Handle cart item deletion
if (isset($_GET['delete_item'])) {
    $item_index = $_GET['delete_item'];
    if (isset($_SESSION['cart'][$item_index])) {
        unset($_SESSION['cart'][$item_index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BrewLane Cafe Menu</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('cc.jpg') no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            position: fixed;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            z-index: 10;
        }

        .header img {
            width: 50px;
            margin-right: 10px;
        }

        .header h1 {
            color:rgb(250, 248, 248);
            font-size: 26px;
            margin: 0;
        }

        .nav-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10;
        }

        .nav-buttons a {
            text-decoration: none;
            color: white;
            background-color: #854836;
            padding: 10px 18px;
            border-radius: 25px;
            margin: 0 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .nav-buttons a:hover {
            background-color: #6d3c28;
        }

        .container {
            max-width: 1600px;
            margin-top: 120px;
            margin-bottom: 40px;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px 50px;
            border-radius: 20px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
        }

        h2 {
            text-align: center;
            color: #854836;
            font-size: 28px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px 14px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #854836;
            color: white;
            font-size: 16px;
        }

        td {
            background-color: #f8f6f5;
            border-radius: 10px;
        }

        td select, td input {
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .qty-input {
            width: 60px;
            padding: 6px;
            font-size: 13px;
            text-align: center;
        }

        button, .delete-btn {
            background-color: #854836;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover, .delete-btn:hover {
            background-color: #6d3c28;
        }

        .cart-summary {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .cart {
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 100px 10px 40px;
            }

            table, th, td {
                font-size: 13px;
            }

            .nav-buttons a {
                padding: 6px 10px;
                font-size: 12px;
            }

            .header h1 {
                font-size: 20px;
            }
        }
    </style>
    <script>
        function updatePrice(selectElement) {
            const row = selectElement.closest('tr');
            const priceCell = row.querySelector('.price-cell');
            const basePrice = parseFloat(priceCell.getAttribute('data-price'));

            let adjustedPrice = basePrice;
            const size = selectElement.value;

            if (size === 'Medium') {
                adjustedPrice += 10;
            } else if (size === 'Large') {
                adjustedPrice += 20;
            }

            priceCell.textContent = '₱' + adjustedPrice.toFixed(2);
        }
    </script>
</head>
<body>

<div class="header">
    <img src="Logo1.png" alt="BrewLane Logo">
    <h1>BrewLane Cafe</h1>
</div>

<div class="nav-buttons">
    <a href="neworder.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="login.php">Sign up</a>
    <a href="index.php">Log out</a>
</div>

<div class="container">
    <h2>Brewlane Menu</h2>
    <table>
        <tr>
            <th>Coffee</th>
            <th>Size</th>
            <th>Type</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM menu");
        while ($row = mysqli_fetch_assoc($result)) {
            $priceFormatted = number_format($row['price'], 2);
            echo "<tr>
                    <form method='post' action='menu.php'>
                        <td>{$row['name']}</td>
                        <td>
                            <select name='size' onchange='updatePrice(this)'>
                                <option value='None'>None</option>
                                <option value='Small'>Small</option>
                                <option value='Medium'>Medium</option>
                                <option value='Large'>Large</option>
                            </select>
                        </td>
                        <td>
                            <select name='type'>
                                <option value='None'>None</option>
                                <option value='Hot'>Hot</option>
                                <option value='Iced'>Iced</option>
                            </select>
                        </td>
                        <td class='price-cell' data-price='{$row['price']}'>₱{$priceFormatted}</td>
                        <td><input type='number' name='quantity' value='1' min='1' class='qty-input'></td>
                        <td>
                            <input type='hidden' name='item' value='{$row['name']}'>
                            <input type='hidden' name='price' value='{$row['price']}'>
                            <button type='submit' name='add_to_cart'>Add to Cart</button>
                        </td>
                    </form>
                  </tr>";
        }
        ?>
    </table>

    <div class="cart">
        <h2>Your Cart</h2>
        <table>
            <tr>
                <th>Item</th>
                <th>Size</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Remove</th>
            </tr>
            <?php
            $totalAmount = 0;
            foreach ($_SESSION['cart'] as $index => $item) {
                echo "<tr>
                        <td>{$item['item']}</td>
                        <td>{$item['size']}</td>
                        <td>{$item['type']}</td>
                        <td>{$item['quantity']}</td>
                        <td>₱" . number_format($item['price'], 2) . "</td>
                        <td>₱" . number_format($item['total'], 2) . "</td>
                        <td><a class='delete-btn' href='?delete_item=$index'>Delete</a></td>
                      </tr>";
                $totalAmount += $item['total'];
            }
            ?>
        </table>
        <div class="cart-summary">
            Total: ₱<?php echo number_format($totalAmount, 2); ?>
        </div>
        <?php if (!empty($_SESSION['cart'])): ?>
            <div style="text-align: center; margin-top: 20px;">
                <a href="payment.php"><button>Proceed to Payment</button></a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>


