<?php
session_start();

require_once 'config.php';

// echo isset($_SESSION['cart']);
if (isset($_SESSION['cart'])) {
    $user_id = $_SESSION['user_id'];
    $cart_items = $_SESSION['cart'][$user_id];
    foreach ($cart_items as $item) {
        $product_id = $item['productid'];
        $item_quantity = $item['quantity'];
        echo $product_id;
        echo $item_quantity;
        $query = "UPDATE cart_03 SET quantity = :qty WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindparam(':qty', $item_quantity);
        $stmt->bindparam(':id', $product_id);
        $stmt->execute();
    }
}

unset($_SESSION['username']);
unset($_SESSION['user_id']);
session_destroy();
header("location:index.php");

