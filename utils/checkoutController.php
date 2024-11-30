<?php



function getDataProducts(string $id): ?object
{
    global $db;
    try {
        $stmt = $db->prepare("SELECT * FROM products_03 WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ?: null;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return null;
    }
}

function getAddresses(string $customerId): array
{
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