<?php 
session_start();
error_reporting(E_ALL);

include_once('../../config.php');

try {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $login = $db -> prepare("SELECT * FROM admin_03 WHERE email = :email");
    $login -> bindParam(":email",$email);
    $login -> execute();
    $admin = $login->fetch(mode: PDO::FETCH_OBJ);
    if ($login->rowCount() > 0 ) {
        $hashed_password = $admin->password;
        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $admin->id;
            $_SESSION['admin'] = $admin->name;

            header(header: "location: ../index.php");
            exit();
        }else{
            header("location: ../login.php?error=password_incorrect");
            exit();
        }
    }else{
        header("location: ../login.php?error=email_not_found");
        exit();
    }
} catch (Throwable $th) {
    header("location: ../login.php?error=1");
    exit();

}

