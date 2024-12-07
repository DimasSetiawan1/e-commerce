<?php

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
function applyDiscount($coupon_code)
{
    try {
        global $db;                                                                                                                                                                
        $query = 'INSERT INTO cart_voucher_03 (voucher_id,cart_id) VALUES (:voucher_id,:cart_id) WHERE user_id = :user_id AND cart_id = :cart_id AND voucher_id NOT IN (SELECT voucher_id FROM cart_voucher_03 WHERE cart_id = :cart_id)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':coupon_code', $coupon_code, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    } catch (\Throwable $th) {
        echo $th->getMessage();
        return false;
    }
}
