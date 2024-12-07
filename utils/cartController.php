<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getCartItems(int $user): array
{
    if (!is_numeric($user) && $user < 0)
        return ['error' => 'What are you trying to do?'];
    global $db;
    try {
        $sql = "SELECT products_03.id,cart_03.quantity,products_03.title,products_03.price,products_03.img,cart_03.id as cart_id FROM cart_03 INNER JOIN products_03 ON products_03.id = cart_03.product_id WHERE cart_03.user_id=:user";
        $query = $db->prepare($sql);
        $query->bindParam(':user', $user, PDO::PARAM_INT);
        $query->execute();
        $itemCount = $query->rowCount();
        $_SESSION['itemCount'] = $itemCount;
        return $query->fetchAll(PDO::FETCH_OBJ);
    } catch (\Throwable $th) {
        return ['error' => $th->getMessage()];
    }
}
