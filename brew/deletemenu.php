<?php
require_once 'placeorder.php'; // includes $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];

    $deleteQuery = "DELETE FROM menu WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: menu.php?msg=Item deleted successfully");
        exit();
    } else {
        header("Location: menu.php?msg=Failed to delete item");
        exit();
    }
}
?>

