<?php
error_reporting(0);
include_once './inc/config.inc.php';
include_once './inc/config_session.inc.php';



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
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'title' => 'Login Berhasil',
                'message' => 'Selamat datang kembali!'
            ];
            $_SESSION['last_regeneration_time'] = time();
            header('Location: index.php');
            die();
        } else {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'title' => 'Login Gagal',
                'message' => 'Password salah!'
            ];
            header('Location: login.php?status=error');
            die();
        }

    } else {
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'title' => 'Login Gagal',
            'message' => 'Email salah!'
        ];
        header('Location: login.php?status=error');

        die();
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrendZ | Online Store for Latest Trends</title>
    <link rel="stylesheet" href="./css/mdb.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <section style="background-color: #D6D6D6;" class="h-100 h-custom">
        <?php include('./inc/header.php'); ?>

        <div class="container my-4 ">
            <div class="row d-flex mx-auto justify-content-center">
                <div class="col-md-4">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <form action="login.php" method="POST">
                                <div class="my-md-5 pb-5">

                                    <h1 class="fw-bold mb-0">Welcome</h1>

                                    <i class="fas fa-user-astronaut fa-3x my-5"></i>

                                    <div class="form-outline mb-4" data-mdb-input-init>
                                        <input type="email" id="email" name="email" class="form-control form-control-lg"
                                            required />
                                        <label class="form-label" for="email">Email</label>
                                        <div class="invalid-feedback">Isi email terlebih dahulu</div>
                                    </div>

                                    <div class="form-outline mb-3" data-mdb-input-init>
                                        <input type="password" id="password" name="password"
                                            class="form-control form-control-lg" required />
                                        <label class="form-label" for="password">Password</label>
                                        <div class="invalid-feedback">Isi password terlebih dahulu</div>


                                    </div>


                                    <button class="btn btn-primary text-white btn-lg btn-rounded px-5" type="submit"
                                        name="submit">Login</button>

                                </div>
                            </form>

                            <div>
                                <p class="mb-0">Don't have an account? <a href="register.php"
                                        class="text-body fw-bold">Sign
                                        Up</a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('./inc/footer.php'); ?>
    </section>


    <script src="./js/jquery-3.3.1.js"></script>
    <!-- <script src="./js/script.js"></script> -->
    <script src="./js/popper.min.js"></script>
    <script src="./js/mdb.umd.min.js"></script>
    <script src="./js/mdb.min.js"></script>
    <script src="./js/alertController.js"></script>

    <?php
    if (isset($_SESSION['flash_message'])) {
        $flashMessage = $_SESSION['flash_message'];
        if (isset($_GET['status']) && $_GET['status'] == 'error') {
            $type = $flashMessage['type'];
            $title = $flashMessage['title'];
            $message = $flashMessage['message'];

            echo "<script>Swal.fire({icon: '$type', title: '$title',text: '$message'});</script>";
        }
        unset($_SESSION['flash_message']);
    }
    ?>



</body>

</html>