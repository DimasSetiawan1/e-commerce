<?php

function updateEmail(int $userId, string $email): bool
{
    global $db;

    try {
        $email = secure($email);
        $query = "UPDATE users_03 SET email = :email WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return true;
    } catch (\Throwable $th) {
        echo $th->getMessage();
        return false;
    }

}
function updatePhoneNumber(int $userId, int $phoneNumber): bool
{
    global $db;

    try {
        $phoneNumber = secure($phoneNumber);
        $query = "UPDATE users_03 SET phone_number = :phone_number WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':phone_number', $phoneNumber);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return true;
    } catch (\Throwable $th) {
        echo $th->getMessage();
        return false;

    }

}

function getAddresses(int $id): array
{
    global $db;
    $id = secure($id);
    $query = "SELECT * FROM addresses_03 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindparam(':id', $id);
    $stmt->execute();
    $addresses = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $addresses;
}

function deleteAddress(int $addressId): bool
{
    global $db;
    $query = "DELETE FROM addresses_03 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $addressId, PDO::PARAM_INT);
    return $stmt->execute();
}

function getAddressById(int $addressId): ?array
{
    global $db;
    $query = "SELECT * FROM addresses_03 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $addressId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result : null;
}

function setDefaultAddress(int $userId, int $addressId): bool
{
    global $db;
    $db->beginTransaction();
    try {
        // Unset current default address
        $query1 = "UPDATE addresses_03 SET is_default = FALSE WHERE user_id = :user_id";
        $stmt1 = $db->prepare($query1);
        $stmt1->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt1->execute();

        // Set new default address
        $query2 = "UPDATE addresses_03 SET is_default = TRUE WHERE id = :id AND user_id = :user_id";
        $stmt2 = $db->prepare($query2);
        $stmt2->bindParam(':id', $addressId, PDO::PARAM_INT);
        $stmt2->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt2->execute();

        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        return false;
    }
}

function getDefaultAddress(int $userId): ?array
{
    global $db;
    $query = "SELECT * FROM addresses_03 WHERE user_id = :user_id AND is_default = TRUE LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result : null;
}


function addAddress(int $userId, array $addressData): bool
{
    global $db;
    $query = "INSERT INTO addresses_03 (user_id, label, full_address, city, province, postal_code) 
              VALUES (:user_id, :label, :full_address, :city, :province, :postal_code)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':label', $addressData['label'], PDO::PARAM_STR);
    $stmt->bindParam(':full_address', $addressData['full_address'], PDO::PARAM_STR);
    $stmt->bindParam(':city', $addressData['city'], PDO::PARAM_STR);
    $stmt->bindParam(':province', $addressData['province'], PDO::PARAM_STR);
    $stmt->bindParam(':postal_code', $addressData['postal_code'], PDO::PARAM_STR);
    return $stmt->execute();
}

function updateAddress(int $addressId, array $addressData): bool
{
    global $db;
    $query = "UPDATE addresses_03 
              SET label = :label, full_address = :full_address, city = :city, 
                  province = :province, postal_code = :postal_code 
              WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $addressId, PDO::PARAM_INT);
    $stmt->bindParam(':label', $addressData['label'], PDO::PARAM_STR);
    $stmt->bindParam(':full_address', $addressData['full_address'], PDO::PARAM_STR);
    $stmt->bindParam(':city', $addressData['city'], PDO::PARAM_STR);
    $stmt->bindParam(':province', $addressData['province'], PDO::PARAM_STR);
    $stmt->bindParam(':postal_code', $addressData['postal_code'], PDO::PARAM_STR);
    return $stmt->execute();
}

