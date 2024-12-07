<?php

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../config.php';
include_once '../utils/cartController.php';

if (isset($_SESSION['user_id'])) {

    if (!isset($_POST['action']))
        echo '';
    $user_id = $_SESSION['user_id'];


    switch ($_POST['action'] ?? '') {
        case 'incrementCart':
            try {
                $product_id = secure($_POST['id']);
                $query = "UPDATE cart_03 SET quantity = quantity + 1 WHERE product_id = :id AND user_id = :user_id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                $get_item = getCartItems($user_id);
                foreach ($get_item as $key => $item) {
                    if ($item->id == $product_id) {
                        echo json_encode([
                            'status' => 'success',
                            'data' => [
                                // "product_name" => $item->product_name,
                                "price" => $item->price,
                                "quantity" => $item->quantity
                            ]
                        ]);
                        break;
                    }
                }
                break;

            } catch (\Throwable $th) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => $th->getMessage()
                ]);
                break;
            }
        case 'decrementCart':
            $product_id = secure($_POST['id']);
            $query = "UPDATE cart_03 SET quantity = quantity - 1 WHERE product_id = :id AND user_id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $get_item = getCartItems($user_id);
            foreach ($get_item as $key => $item) {
                if ($item->id == $product_id) {
                    echo json_encode([
                        'status' => 'success',
                        'data' => [
                            "price" => $item->price,
                            "quantity" => $item->quantity
                        ]
                    ]);
                    break;
                }
            }
            break;
        case 'getDiscount':
            try {
                $get_discount = secure($_POST['voucher']);
                $query = "SELECT * FROM voucher_03 WHERE coupon_code = :discount";
                $sql = $db->prepare($query);
                $sql->bindParam(':discount', $get_discount, PDO::PARAM_STR);
                $sql->execute();
                $result = $sql->fetch(PDO::FETCH_OBJ);

                echo json_encode([
                    'status' => 'success',
                    'data' => [
                        'discount' => $result->value,
                        'coupon_code' => $result->coupon_code
                    ]
                ]);
                break;
            } catch (\Throwable $th) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => $th->getMessage()
                ]);
                break;
            }
        case 'removeCart':
            try {
                $product_id = secure($_POST['id']);
                $query = "DELETE FROM cart_03 WHERE product_id = (:cartid) AND user_id = (:userid) LIMIT 1";
                $sql = $db->prepare($query);
                $sql->bindParam(':cartid', $product_id, PDO::PARAM_INT);
                $sql->bindParam(':userid', $user_id, PDO::PARAM_INT);
                $sql->execute();
                unset($_SESSION['cart'][$product_id]);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Product removed from cart'
                ]);
                break;
            } catch (\Throwable $th) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => "Something went wrong"
                ]);
                break;
            }

        default:
            http_response_code(404);
            echo json_encode([
                'error' => 'Invalid action'
            ]);
            break;
    }


} else {
    http_response_code(404);

    echo json_encode([
        'error' => 'You are not logged in'
    ]);
}