<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../config.php';


if (isset($_SESSION['user_id'])) {

    if (!isset($_POST['action']))
        echo '';
    $userId = $_SESSION['user_id'];
    switch ($_POST['action'] ?? '') {
        case 'updateCart':
            try {
                $product_id = secure($_POST['id']);
                $quantity = secure($_POST['qty']);
                $price = secure($_POST['price']);


                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
                    $_SESSION['cart'][$product_id]['price'] = $price;
                } else {
                    $_SESSION['cart'][$product_id] = [
                        'productid' => $product_id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ];
                }

                echo json_encode([
                    'status' => 'success',
                    'data' => [
                        "quantity" => $quantity,
                        "price" => $price,
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
        case 'getDiscount':
            try {
                $get_discount = secure($_POST['voucher']);
                $query = "SELECT * FROM discount_03 WHERE kode_kupon = :discount";
                $sql = $db->prepare($query);
                $sql->bindParam(':discount', $get_discount, PDO::PARAM_STR);
                $sql->execute();
                $result = $sql->fetch(PDO::FETCH_OBJ);
                if ($sql->rowCount() > 0) {
                    $_SESSION['discount'][$result->id] = [
                        'discount_total' => $result->total_discount,
                        'discount_code' => $result->kode_kupon
                    ];

                    echo json_encode([
                        'status' => 'success',
                        'data' => [
                            'discount_total' => $result->total_discount,
                            'discount_code' => $result->kode_kupon
                        ]
                    ]);
                } else {
                    http_response_code(404);
                    echo json_encode([
                        'status' => 'error',
                        'message' => "Not Found"
                    ]);
                    break;
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
        case 'removeCart':
            try {
                $product_id = secure($_POST['id']);
                $query = "DELETE FROM cart_03 WHERE productid = (:cartid) AND user = (:userid) LIMIT 1";
                $sql = $db->prepare($query);
                $sql->bindParam(':cartid', $product_id, PDO::PARAM_INT);
                $sql->bindParam(':userid', $userId, PDO::PARAM_INT);
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
                'status' => 'error',
                'message' => 'What are you doing ?'
            ]);
            break;
    }

} else {
    header('Location: login.php');
    exit();
}



