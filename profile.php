<?php
session_start();
include_once('config.php');


if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

try {
    $stmt = $db->prepare("SELECT * FROM users_03 WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);
} catch (\Throwable $th) {
    echo $th->getMessage();
}


$current_page = basename($_SERVER['PHP_SELF']);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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
                                    <?= $user->name; ?>
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
                            <h4 class="card-title px-4 ">Profile</h4>
                            <div class="card-body">
                                <form action="profile_edit.php" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-outline mb-3" data-mdb-input-init>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="<?= $user->name; ?>">
                                                <label for="name" class="form-label">Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline mb-3" data-mdb-input-init>
                                                <i class="btn btn-link fa-1x far fa-pen-to-square trailing fa-fw pe-auto"
                                                    id="editEmail" onclick="setEmail();"></i>
                                                <input type="text" id="email" name="email"
                                                    class="form-control  form-icon-trailing" disabled
                                                    value="<?= $user->email; ?>" />
                                                <label class="form-label" for="email">Email</label>
                                                <!-- <div class="invalid-feedback">Isi Confirm Password terlebih dahulu</div> -->
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="modal fade" id="confirmEmailChangeModal" tabindex="-1"
                                    aria-labelledby="confirmEmailChangeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmEmailChangeModalLabel">Confirm Email
                                                    Change</h5>
                                                <button type="button" class="btn-close" data-mdb-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to change your email address?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-mdb-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-primary"
                                                    id="confirmEmailChange">Confirm</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-success mb-0 alert-dismissible alert-absolute fade d-none"
                                    id="emailChangeAlert" role="alert" data-mdb-color="success">
                                    <i class="fas fa-check me-2"></i>
                                    Email successfully changed
                                    <button type="button" class="btn-close ms-2" data-mdb-dismiss="alert"
                                        aria-label="Close"></button>
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
    <script src="./js/updateEmail.js"></script>

</body>

</html>