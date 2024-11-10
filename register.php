<?php
session_start();
error_reporting(0);
include('config.php');

if (isset($_POST['submit'])) {
    $name = secure($_POST['name']);
    $mobile = secure($_POST['mobile']);
    $email = secure($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    $query = $db->prepare("SELECT COUNT(*) FROM users_03 WHERE email = :email");
    $query->bindParam(":email", $email);
    $query->execute();

    $count = $query->fetchColumn();

    if ($count > 0) {
        echo "<script>alert('Email Already Exists')</script>";
    } else {
        try {
            $sql = "INSERT INTO users_03(name, email, mobile, password) VALUES (:name,:email, :mobile, :password)";
            $query = $db->prepare($sql);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':password', $password, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $db->lastInsertId();
            if ($lastInsertId) {
                echo "<script>
                alert('Thanks For Register, Login For Continue Your Shopping')
                window.location.href = 'login.php';
                </script>";
            } else {
                echo "<script>alert('Please Fill All Valid Details')</script>";
            }
        } catch (Throwable $th) {
            echo "Error: " . $th->getMessage();
        }
    }


}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrendZ | Online Store for Latest Trends</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <section>
        <?php include('./inc/header.php'); ?>

        <div class="row justify-content-md-center">
            <div class="col-sm-4">
                <form class="text-center border border-light p-5" method="post">
                    <p class="h4 mb-4">Sign up</p>
                    <input name="name" type="text" class="form-control mb-4" placeholder="Name" required>
                    <input name="email" type="email" class="form-control mb-4" placeholder="Email" required>
                    <input name="mobile" type="number" class="form-control mb-4" placeholder="Mobile" required>
                    <input name="password" type="password" class="form-control mb-4" placeholder="Password" required>

                    <input class="btn btn-dark btn-block my-4" name="submit" type="submit" value="Register">
                    <p>Already Registerd?
                        <a href="login.php">Login</a>
                    </p>
                </form>
            </div>
        </div>
        <?php include('./inc/footer.php'); ?>
    </section>


    <script src="./js/jquery-3.3.1.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</body>

</html>