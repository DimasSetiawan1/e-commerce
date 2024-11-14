<?php

session_start();
include_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location:../login.php");
    exit();
}


var_dump($_POST);
if (isset($_POST['email']) && !empty($_POST['email'])) {
    $email = secure($_POST['email']);
    $query = "UPDATE users_03 SET email = :email WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();

    echo json_encode(['success' => true]);

}