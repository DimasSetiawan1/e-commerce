<?php
session_start();
error_reporting(E_ALL);

include_once 'config.php';

if (isset($_SESSION['user_id'])) {

    $product_id = secure($_POST['id']);
    $quantity = secure($_POST['qty']);
    $user_id = $_SESSION['user_id'];
    echo $product_id;
    echo $quantity;


    if (!isset($_SESSION['cart'][$user_id]))
        $_SESSION['cart'][$user_id] = [];

    // if (isset($_POST['action']) && $_POST['action'] == 'add') {
    //     if (isset($_SESSION['cart'][$user_id][$product_id])) {
    //         $_SESSION['cart'][$user_id][$product_id]['quantity'] += $quantity;
    //     } else {
    //         $_SESSION['cart'][$user_id][$product_id] = [
    //             'productid' => $product_id,
    //             'quantity' => $quantity
    //         ];
    //     }
    // } elseif (isset($_POST['action']) && $_POST['action'] == 'minus') {
    //     if (isset($_SESSION['cart'][$user_id][$product_id])) {
    //         $_SESSION['cart'][$user_id][$product_id]['quantity'] = $quantity;
    //     } else {
    //         $_SESSION['cart'][$user_id][$product_id] = [
    //             'productid' => $product_id,
    //             'quantity' => $quantity
    //         ];
    //     }
    // }
    echo json_encode(['status' => 'success', 'message' => `{$quantity} added to cart`]);
} else {
    header('Location: login.php');
    exit();
}



