<?php
session_start();
error_reporting(0);
include('config.php');

if (isset($_POST['submit'])) {
    $name = secure($_POST['name']);
    $mobile = secure($_POST['mobile']);
    $email = secure($_POST['email']);

    if (strlen($email) == 0 && strlen($mobile) == 0 && strlen($name) == 0) {

        echo "<script>alert('Please Fill All Valid Details')</script>";
        exit();
    }
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
                exit();

            } else {
                echo "<script>alert('Please Fill All Valid Details')</script>";
                exit();
            }
        } catch (Throwable $th) {
            echo "Error: " . $th->getMessage();
            exit();
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
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/mdb.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>

    <section>
        <?php include('./inc/header.php'); ?>

        <div class="container">
            <div class="row d-flex mx-auto justify-content-center">
                <div class="col-md-4">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <form action="register.php" class="needs-validation" method="POST">
                                <div class="my-md-5 pb-5">

                                    <h1 class="fw-bold mb-3">Sign up</h1>

                                    <div class="form-outline mb-4" data-mdb-input-init>
                                        <input type="text" id="Name" name="Name" class="form-control form-control-lg"
                                            required />

                                        <label class="form-label" for="Name">Name</label>
                                        <div class="invalid-feedback">Isi nama terlebih dahulu</div>
                                    </div>

                                    <div class="form-outline mb-4" data-mdb-input-init>
                                        <input type="email" id="email" name="email" required
                                            class="form-control form-control-lg" />
                                        <label class="form-label" for="email">Email</label>
                                        <div class="invalid-feedback">Isi email terlebih dahulu</div>

                                    </div>
                                    <div class="input-group form-outline mb-4" data-mdb-input-init>
                                        <span class="input-group-text" id="inputGroupPrepend">+62</span>
                                        <input type="number" class="form-control" id="number"
                                            aria-describedby="inputGroupPrepend" required />
                                        <label for="number" class="form-label">number</label>
                                        <div class="invalid-feedback">Isi Nomor Handphone terlebih dahulu.</div>
                                    </div>
                                    <div class="form-outline mb-4" data-mdb-input-init>
                                        <i class="far fa-eye trailing fa-fw pe-auto" id="showPassword"></i>
                                        <input type="password" id="password" name="password" required
                                            class="form-control form-control-lg form-icon-trailing" />
                                        <label class="form-label" for="password">Password</label>
                                        <div class="invalid-feedback">Isi password terlebih dahulu</div>

                                    </div>
                                    <div class="form-outline mb-4" data-mdb-input-init>
                                        <i class="far fa-eye trailing fa-fw pe-auto" id="showConfirmPassword"></i>
                                        <input type="password" id="confirmPassword" required
                                            class="form-control form-control-lg form-icon-trailing" />
                                        <label class="form-label" for="confirmPassword">Confirm Password</label>
                                        <div class="invalid-feedback">Isi Confirm Password terlebih dahulu</div>

                                    </div>


                                    <button class="btn btn-primary text-white btn-lg btn-rounded px-5" type="submit"
                                        name="submit">Sign Up</button>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('./inc/footer.php'); ?>
    </section>


    <script src="./js/jquery-3.3.1.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/mdb.umd.min.js"></script>
    <script src="./js/mdb.min.js"></script>
    <script src="./js/script.js"></script>

</body>

</html>