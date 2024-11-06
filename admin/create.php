<?php
if (isset($_POST['submit'])) {
    // $pass = $_POST['pass'];
    // $salt = '$6$rounds=1000000$' . bin2hex(random_bytes(16)) . '$';
    // // $hashed_password = crypt($pass, $salt);
    // // echo $hashed_password;
    // $hashed_password = crypt($pass, $salt);

    // echo "hashed password: " . $hashed_password;

    // // if (hash_equals($hashed_password, crypt($pass, $salt))) {
    // //     echo "Password verified!";
    // //  }
    // if (hash_equals($hashed_password, crypt($pass, $salt))) {
    //     echo "<br>Password verified!";
    //     exit();
    // }
    // echo "salah";

    include_once '../config.php';
    // $pass = $_POST['password'];
    // $email = $_POST['email'];
    // // $salt = '$6$rounds=1000000$' . bin2hex(random_bytes(16)) . '$';
    // // $hashed_password = password_hash($pass, PASSWORD_BCRYPT);
    // $stmt = $db->prepare("SELECT * FROM admin_03 WHERE email = :email");
    // $stmt->bindParam(':email', $email, type: PDO::PARAM_STR);
    // $stmt->execute();
    // $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // if ($stmt->rowCount() > 0) {
    //     $password = $result["password"];
    //     if (password_verify($pass, $password)) {
    //         $_SESSION['login'] = $results->name;
    //         $_SESSION['user'] = $results->user_level;
    //         echo "<script>alert('Login Success, Continue Your Shopping')</script>";
    //         echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
    //         echo "Password verified!";
    //         exit();
    //     }else{
    //         echo "Salah";
    //         exit();
    //     }
    // } 
    // echo "<script>alert('Email Incorrect')</script>";
    // exit();
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    $sql = "INSERT INTO  admin_03  (email,name,password) VALUES (:email, :name, :password)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $name,PDO::PARAM_STR );
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "Update success";
        exit();
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="create.php" method="post">
        <input type="text" name="name" placeholder="name">
        <input type="email" name="email" placeholder="email">
        <input type="text" name="password" placeholder="password">
        <button type="submit" name="submit">Submit</button>
    </form>
</body>
</html>