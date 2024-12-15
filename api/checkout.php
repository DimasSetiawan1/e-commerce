<?php

require_once '../inc/config.inc.php';
require_once '../inc/config_session.inc.php';
require_once '../utils/checkoutController.php';



if ($_SERVER['REQUEST_METHOD'] === "GET") {
    echo json_encode(['message' => 'Nothing Here']);
    exit();
}

$userId = secure(is_numeric($_SESSION['user_id'] ?? 0) ? $_SESSION['user_id'] : 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'updateVoucher':
            $voucherId = secure(is_numeric($_POST['id'] ?? 0) ? $_POST['id'] : 0);

            if ($voucherId === 0) {
                echo json_encode(['message' => 'Invalid Voucher ID']);
                exit();
            }
            $setVoucherFalse = $db->prepare("UPDATE cart_voucher_03 SET is_select = FALSE WHERE user_id = :user_id");
            $setVoucherFalse->bindParam(':user_id', $userId);
            $setVoucherFalse->execute();

            $query = "UPDATE cart_voucher_03 SET is_select = 1 WHERE id = :id AND user_id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $voucherId);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Voucher updated successfully']);
            } else {
                echo json_encode(['message' => 'Failed to update voucher']);
            }
            break;
        case 'checkout':
            $userId = secure(is_numeric($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
            $addressId = secure(is_numeric($_POST['address']) ? $_POST['address'] : 0);
            $courierId = secure(is_numeric($_POST['courier']) ? $_POST['courier'] : 0);
            $voucherId = secure(is_numeric($_POST['voucher']) ? $_POST['voucher'] : 0);
            $totalPayment = secure($_POST['total_payment'] ?? 0);

            $orderId = 0;
            try {
                $db->beginTransaction();
                $query = 'INSERT INTO orders_03 (user_id, shipping_address_id , courier_id, total_amount) values (:user_id, :shipping_address_id, :courier_id,:total_amount) ';
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':shipping_address_id', $addressId, PDO::PARAM_INT);
                $stmt->bindParam(':total_amount', $totalPayment, PDO::PARAM_INT);
                $stmt->bindParam(':courier_id', $courierId, PDO::PARAM_INT);
                $stmt->execute();
                $orderId = $db->lastInsertId();
                $db->commit();
            } catch (PDOException $th) {
                $db->rollBack();
                throw $th;
            }
            if ($orderId === 0) {
                echo json_encode(['message' => 'Failed to checkout']);
                break;
            }

            try {
                $cartItems = getDataItems($userId);
                $db->beginTransaction();
                foreach ($cartItems as $item) {
                    $quantity = $item->quantity;
                    $productId = $item->id;
                    $query = 'INSERT INTO order_items_03 (order_id, product_id,quantity, price) values (:order_id,:product_id,:quantity,:price) ';
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
                    $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
                    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                    $stmt->bindParam(':price', $totalPayment, PDO::PARAM_INT);
                    $stmt->execute();
                }
                $db->commit();
            } catch (\PDOException $th) {
                $db->rollBack();
                throw $th;
            }
            try {
                $db->beginTransaction();
                $query = 'INSERT INTO voucher_usage_03 (order_id, voucher_id) values (:order_id, :voucher_id) ';
                $stmt = $db->prepare($query);
                $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
                $stmt->bindParam(':voucher_id', $voucherId, PDO::PARAM_INT);
                $stmt->execute();


                $query = 'DELETE FROM cart_03 WHERE user_id = :user_id ';
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
                $db->commit();
                echo json_encode(['status' => 'success', 'message' => 'Checkout successful']);
            } catch (\PDOException $th) {
                $db->rollBack();
                throw $th;
            }
            break;

        default:
            echo json_encode(['message' => 'Invalid Action']);
            break;
    }
}