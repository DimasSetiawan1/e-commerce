<?php

function updateEmail(int $userId, string $email): bool
{
    global $db;
    if (!is_numeric($userId) && $userId < 0)
        return false;
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
    if (!is_numeric($userId) && $userId < 0)
        return false;
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

function updatePassword(int $userId, string $password, string $newPassword): string
{
    if (!is_numeric($userId) && $userId < 0)
        return 'What are you trying to do?';
    global $db;
    try {
        $password = secure($password);
        $newPassword = secure($newPassword);

        // pengecekan dulu
        $checkPasswordQuery = "SELECT password FROM users_03 WHERE id = :id";
        $checkPasswordStmt = $db->prepare($checkPasswordQuery);
        $checkPasswordStmt->bindParam(':id', $userId);
        $checkPasswordStmt->execute();
        $checkPassword = $checkPasswordStmt->fetch(PDO::FETCH_ASSOC);
        if (!password_verify($password, $checkPassword['password'])) {
            return 'Password Salah';
        }

        $query = "UPDATE users_03 SET password = :password WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':password', $newPassword);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return 'Password berhasil diubah';
    } catch (\Throwable $th) {
        return 'Error: ' . $th->getMessage();

    }
}

function getAddresses(int $id, int $userId): array
{
    if ((!is_numeric($id) && $id < 0) && (!is_numeric($userId) && $userId < 0))
        return ['error' => 'What are you trying to do?'];
    global $db;
    $id = secure($id);
    $query = "SELECT * FROM addresses_03 WHERE id = :id AND user_id = :userId";
    $stmt = $db->prepare($query);
    $stmt->bindparam(':id', $id);
    $stmt->bindparam(':userId', $userId);
    $stmt->execute();
    $addresses = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $addresses;
}

function deleteAddress(int $addressId): bool
{
    if (!is_numeric($addressId) && $addressId < 0)
        return false;
    global $db;
    $query = "DELETE FROM addresses_03 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $addressId, PDO::PARAM_INT);
    return $stmt->execute();
}

function getAddressById(int $addressId): ?array
{
    if (!is_numeric($addressId) && $addressId < 0)
        return ['error' => 'what are you trying to do?'];
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
    if ((!is_numeric($addressId) && $addressId < 0) && (!is_numeric($userId) && $userId < 0))
        return false;
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
    if (!is_numeric($userId) && $userId < 0)
        return ['error' => 'what are you trying to do?'];
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
    if (!is_numeric($userId) && $userId < 0)
        return false;
    global $db;
    $query = "INSERT INTO addresses_03 (user_id, name, phone_number, label, full_address, city, province, postal_code) 
              VALUES (:user_id, :name, :phone_number, :label, :full_address, :city, :province, :postal_code)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':label', $addressData['label'], PDO::PARAM_STR);
    $stmt->bindParam(':full_address', $addressData['full_address'], PDO::PARAM_STR);
    $stmt->bindParam(':city', $addressData['city'], PDO::PARAM_STR);
    $stmt->bindParam(':province', $addressData['province'], PDO::PARAM_STR);
    $stmt->bindParam(':postal_code', $addressData['postal_code'], PDO::PARAM_STR);
    $stmt->bindParam(':phone_number', $addressData['phone_number'], PDO::PARAM_INT);
    $stmt->bindParam(':name', $addressData['name'], PDO::PARAM_STR);


    return $stmt->execute();
}

function updateAddress(int $addressId, array $addressData): bool
{
    if (!is_numeric($addressId) && $addressId < 0)
        return false;
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

function updateName(int $userId, string $newName): bool
{
    if (!is_numeric($userId) && $userId < 0)
        return false;
    global $db;

    try {
        $newName = secure($newName);
        $query = "UPDATE users_03 SET name = :name WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $newName);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $_SESSION['username'] = $newName;
        return true;
    } catch (\Throwable $th) {
        echo $th->getMessage();
        return false;
    }
}
