<?php

declare(strict_types=1);

/**
 * Retrieves the items in a user's cart.
 *
 * @param int $user_id The ID of the user whose cart items are to be retrieved.
 * 
 * @return array An array of cart item objects, each containing user ID, quantity, product ID, title, stock, price, and image.
 *               If an error occurs, an array with an 'error' key and the error message is returned.
 */
function getDataItems(int $user_id)
{
    try {
        global $db;
        if (!$db) {
            throw new PDOException('Could not connect to the database.');
        }
        if ($user_id <= 0) {
            throw new InvalidArgumentException('Invalid user ID.');
        }
        $user_id = secure(is_numeric($user_id) ? $user_id : 0);
        $query = 'SELECT c.user_id, c.quantity, p.id, p.title, p.stock, p.price, p.img  FROM cart_03 as c INNER JOIN products_03 as p ON p.id = c.product_id  WHERE c.user_id = :user_id ';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (\Throwable $th) {
        return ['error' => $th->getMessage()];
    }
}


/**
 * Retrieves a list of addresses associated with a given customer ID.
 *
 * @param int $customerId The ID of the customer whose addresses are to be retrieved.
 * 
 * @return array An array of address objects associated with the customer. 
 *               If the customer ID is invalid or an error occurs, an error message or an empty array is returned.
 */
function getAddresses(int $customerId): array
{
    if (!is_numeric($customerId) && $customerId < 0)
        return ['error' => 'What are you trying to do?'];
    global $db;
    try {
        $stmt = $db->prepare("SELECT * FROM addresses_03 WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $customerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}


/**
 * Retrieves a list of couriers from the database.
 *
 * @return array An array of courier objects. If an error occurs, an empty array is returned.
 */
function getCouriers(): array
{
    global $db;
    try {
        $stmt = $db->prepare("SELECT * FROM courier_03");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);

    } catch (\Throwable $th) {
        echo $th->getMessage();
        return [];
    }

}


/**
 * Applies a discount to the cart using a given coupon code.
 *
 * @param string $coupon_code The coupon code to be applied for the discount.
 * 
 * @return bool Returns true if the discount was successfully applied, false otherwise.
 */
function applyDiscount(string $coupon_code): ?string
{
    try {
        global $db;
        $userId = $_SESSION['user_id'];
        $coupon_code = secure($coupon_code);
        // validasi voucher
        $query = 'SELECT * FROM voucher_03 WHERE coupon_code = :coupon_code AND end_date >= NOW() ';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':coupon_code', $coupon_code, PDO::PARAM_STR);
        $stmt->execute();
        $voucher = $stmt->fetch(PDO::FETCH_OBJ);

        // bentrok disinih 
        if ($stmt->rowCount() > 0) {
            $voucherId = $voucher->id;
            $query = 'INSERT INTO voucher_usage_03 (user_id, voucher_id) SELECT :user_id, :voucher_id  WHERE NOT EXISTS ( SELECT 1 FROM cart_voucher_03 WHERE user_id = :user_id AND voucher_id = :voucher_id)';
            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':voucher_id', $voucherId, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return json_encode(['status' => 'success', 'message' => 'Coupon berhasil diterapkan']);
            } else {
                return json_encode(['status' => 'error', 'message' => 'Coupon Sudah diterapkan']);
            }
        } else {
            return json_encode(['status' => 'error', 'message' => "Coupon sudah expired atau salah"]);
        }

    } catch (\Throwable $th) {
        return json_encode(['status' => 'error', 'message' => "{$th->getMessage()}"]);
    }
}


function getAvailableVouchers()
{
    try {
        global $db;
        $userId = $_SESSION['user_id'];
        $query = 'SELECT v.*, cv.is_select FROM voucher_03 v INNER JOIN voucher_usage_03 cv ON v.id = cv.voucher_id WHERE cv.user_id = :user_id ';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (\Throwable $th) {
        echo json_encode(['status' => 'error', 'message' => "{$th->getMessage()}"]);
    }
}
function setTransaction(array $data)
{

}

