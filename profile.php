<?php
session_start();
include_once('config.php');


if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/mdb.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>

    <?php include('./inc/header.php') ?>

    <main>
        <section>
            <div class="container mx-auto">
                <div class="row justify-content-between">
                    <div class="col-sm-4 col-md-4 mt-5 ">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="fw-bold px-3 ">Profile</h4>
                            </div>
                            <div class="card-body align-items-center mx-auto">
                                <img src="./img/user.png" alt="test" class="rounded-circle " height="100px"
                                    width="100px" loading="lazy" />

                                <h5 class="mt-3 text-center align-items-center fw-bold ">
                                    <?= $_SESSION['username'] ?? "user" ?>
                                </h5>

                            </div>
                            <div class="card-body">
                                <?php include_once "./inc/sidebar_user.php" ?>
                            </div>
                            <div class="card-body">

                            </div>
                            <div class="card-footer justify-content-center">
                                <a href="#" class="btn btn-secondary btn-block">Logout</a>
                            </div>

                        </div>



                    </div>
                    <div class="col-sm-8 col-md-8 mt-5">
                        <div class="card text-start">
                            <img class="card-img-top" src="" alt="Title" />
                            <div class="card-body">
                                <h4 class="card-title">Title</h4>
                                <p class="card-text">Body</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>


    <?php include('./inc/footer.php') ?>


    <script src="./js/jquery-3.3.1.slim.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/mdb.umd.min.js"></script>
    <script src="./js/mdb.min.js"></script>

</body>

</html>