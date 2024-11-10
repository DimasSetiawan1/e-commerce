<?php
session_start();
error_reporting(0);
include('config.php');

if (isset($_SESSION['user_id'])) {
    echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
}

if (isset($_POST['submit'])) {
    $email = secure($_POST['email']);
    $password = secure($_POST['password']);
    $sql = "SELECT * FROM users_03 WHERE email=:email ";
    $query = $db->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetch(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        $hashed_password = $results->password;
        if (password_verify(trim($password), $hashed_password)) {
            $_SESSION['user_id'] = $results->id;
            $_SESSION['username'] = $results->name;
            echo "<script>alert('Login Success, Continue Your Shopping')</script>";
            echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
            exit();
        } else {
            echo "<script>alert('Password Incorrect');</script>";
            echo "<script type='text/javascript'> document.location = 'login.php'; </script>";
            exit();
        }

    } else {
        echo "<script>alert('Email Incorrect');</script>";
        exit();
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

        <div class="row justify-content-md-center ">
            <div class="col-sm-4">
                <form class="text-center border border-light p-5" method="post">
                    <p class="h4 mb-4">Sign in</p>
                    <input type="email" name="email" id="email" class="form-control mb-4" placeholder="E-mail" required>
                    <input type="password" name="password" id="password" class="form-control mb-4"
                        placeholder="Password" required>

                    <div class="d-flex justify-content-around">
                        <div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="chkbx">
                                <label class="custom-control-label" for="chkbx">Remember me</label>
                            </div>
                        </div>
                        <div>
                            <a href="">Forgot password?</a>
                        </div>
                    </div>
                    <input class="btn btn-dark btn-block my-4" name="submit" type="submit" value="Sign In">
                    <p>Not a member?
                        <a href="register.php">Register</a>
                    </p>
                </form>
            </div>
        </div>
        <?php include('./inc/footer.php'); ?>
    </section>


    <script src="./js/jquery-3.3.1.js"></script>
    <script src="./js/script.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</body>

</html>