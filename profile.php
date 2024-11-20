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
                    <?php include_once "./inc/sidebar_user.php" ?>
                    <div class="col-sm-8 col-md-8 mt-5">
                        <div class="card text-start ">
                            <h3 class="card-title px-4 mx-3 my-4 fw-bold ">Profile</h3>
                            <div class="card-body d-flex col-md-7">
                                <table class="table table-responsive  table-borderless align-content-start ">
                                    <tr>
                                        <td><label for="name">Name</label></td>
                                        <td>
                                            <div class="form-outline" data-mdb-input-init>
                                                <input type="text" class="form-control" id="name"
                                                    value="<?= $user->name; ?>">
                                                <label for="name" class="form-label">Name</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="email">Email</label></td>
                                        <td>
                                            <div class="form-outline" data-mdb-input-init>
                                                <i class="btn btn-link fa-1x far fa-pen-to-square trailing fa-fw pe-auto"
                                                    id="editEmail" onclick="setEmail();"></i>
                                                <input type="text" id="email" class="form-control form-icon-trailing"
                                                    disabled value="<?= $user->email; ?>" />
                                                <label class="form-label" for="email">Email</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="phone_number">Nomor Telepon</label></td>
                                        <td>
                                            <div class="form-outline" data-mdb-input-init>
                                                <i class="btn btn-link fa-1x far fa-pen-to-square trailing fa-fw pe-auto"
                                                    id="editPhoneNumber" onclick="setPhoneNumber();"></i>
                                                <input type="text" id="phone_number" name="phone_number"
                                                    class="form-control form-icon-trailing" disabled
                                                    value="<?= $user->phone_number; ?>" />
                                                <label class="form-label" for="phone_number">Phone Number</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <button type="buttton" onclick="handleNameChange()"
                                                class="btn btn-primary btn-rounded  mt-2">
                                                <i class="fas fa-save me-2 saved-text"></i>Save Changes
                                                <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                                    aria-hidden="true"> </span>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
    
                                <div class="modal fade" id="confirmChangeModal" tabindex="-1"
                                    aria-labelledby="confirmChangeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmChangeModalLabel">
                                                </h5>
                                                <button type="button" class="btn-close" data-mdb-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" id="confirmChangeModalBody">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-mdb-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-primary"
                                                    id="confirmChange">Confirm</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="alert alert-success d-none  alert-dismissible fade hide top-1 position-fixed start-0 m-3"
                                id="alertUpdateProfile" role="alert" data-mdb-color="success">
                                <i class="fas fa-check me-2"></i>
                                <span id="alertMessages"></span>
                                <button type="button" class="btn-close ms-2" data-mdb-dismiss="alert"
                                    aria-label="Close"></button>
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
    <script src="./js/updateProfile.js"></script>

</body>

</html>