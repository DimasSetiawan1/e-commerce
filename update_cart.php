<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'config.php';
// include_once 'mycart.php';


if (isset($_SESSION['user_id'])) {

    if (!isset($_POST['action']))
        echo '';
    switch (isset($_POST['action']) ? $_POST['action'] : '') {
        case 'updateCart':
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
            break;
        case 'getDiscount':
            $get_discount = secure($_POST['voucher']);
            $sql = "SELECT * FROM discount_03 WHERE kode_kupon = :discount";
            $query = $db->prepare($sql);
            $query->bindParam(':discount', $get_discount, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'discount' => $result->total_discount,
                    'discount_code' => $result->kode_kupon
                ]
            ]);
            break;

        default:
            echo json_encode([
                'status' => 'error',
                'message' => 'What are you doing ?'
            ]);
            break;
    }

} else {
    header('Location: login.php');
    exit();
}



