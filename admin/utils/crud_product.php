<?php

session_start();
include_once '../../config.php';
include_once 'file_validation.php';

if (!isset($_SESSION['admin_id'])) {
    header("location: ../login.php");
    exit();
}
if (isset($_GET['delete'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM products_03 WHERE id = (:id)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: ../../admin/product_management.php");
    exit();
}


if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $image_upload = image_validation($_FILES);
    try {
        $query = "INSERT INTO products_03  (title, stock, price ,  category, created_at, img) VALUES (:title, :stock, :price ,  :category, NOW(), :image)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image_upload, PDO::PARAM_STR);
        $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: ../../admin/product_management.php?status=addsuccess");
        exit();
    } catch (\Throwable $th) {
        var_dump("Connection Db Error : " . $th->getMessage());
        throw $th;
    }
}

if (isset($_POST['update'])) {
    $id = $_GET['id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $img = NULL;

    empty($_FILES['imageUpload']['name']) ? $img = NULL : $img = image_validation($_FILES);

    try {
        $query = "UPDATE products_03 SET title = :title, stock = :stock, price = :price ,  category = :category, created_at = NOW() ";
        if ($img)
            $query .= ", img = :image ";

        $query .= "WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($img)
            $stmt->bindParam(':image', $img, PDO::PARAM_STR);

        $stmt->execute();

        header("Location: ../../admin/product_management.php?status=updatesuccess");
    } catch (\Throwable $th) {

        var_dump("Connection Db Error : " . $th->getMessage());
        throw $th;
    }
}
