<?php
session_start();
error_reporting(E_ALL);

include_once 'config.php';

if (isset($_SESSION['user_id'])) {

    $product_id = secure($_POST['id']);
    $quantity = secure($_POST['qty']);
    $price = secure($_POST['price']);

    $total = $quantity * $price;
    $user_id = $_SESSION['user_id'];

    if (!isset($_SESSION['cart'][$user_id]))
        $_SESSION['cart'][$user_id] = [];


    if (isset($_SESSION['cart'][$user_id][$product_id])) {
        $_SESSION['cart'][$user_id][$product_id]['quantity'] = $quantity;
        $_SESSION['cart'][$user_id][$product_id]['total'] = $quantity * $price;
    } else {
        $_SESSION['cart'][$user_id][$product_id] = [
            'productid' => $product_id,
            'quantity' => $quantity,
            'total' => $total,
        ];
    }

    echo json_encode([
        'status' => 'success',
        'data' => [
            "quantity" => $quantity,
            "total" => $total,
        ]

    ]);
} else {
    header('Location: login.php');
    exit();
}



