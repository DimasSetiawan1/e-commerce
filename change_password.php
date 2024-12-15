<?php
include_once './inc/config.inc.php';
include_once './inc/config_session.inc.php';




$current_page = basename($_SERVER['PHP_SELF']);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alamat Saya</title>
    <link rel="stylesheet" href="./css/mdb.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


</head>

<body>

    <?php include "./inc/header.php"; ?>
    <main>
        <section>
            <div class="container mx-auto">
                <div class="row justify-content-between">
                    <?php include_once "./inc/sidebar_user.php" ?>
                    <div class="col-sm-8 col-md-8 mt-5">

                        <div class="card text-start">
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6 pt-2 ">
                                            <h5 class="card-title">Ubah Password</h5>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <?php include "./inc/modal_address.php" ?>
                        </div>
                        <hr class="my-3">
                        <div class="card">
                            <div class="card-body">
                                <!-- <form id="changePasswordForm" action="./utils/profileHandler.php" method="POST"> -->
                                <div class="form-outline w-50 mb-4">
                                    <i class="far fa-eye trailing fa-fw pe-auto" id="showCurrentPassword"></i>
                                    <input type="password" id="currentPassword" name="password" class="form-control"
                                        required />
                                    <label class="form-label" for="currentPassword">Current Password</label>
                                </div>
                                <!-- <input type="hidden" name="action" value="password_change"> -->

                                <div class="form-outline w-50 mb-4">
                                    <i class="far fa-eye trailing fa-fw pe-auto" id="showNewPassword"></i>

                                    <input type="password" id="newPassword" name="new_password" class="form-control"
                                        required />
                                    <label class="form-label" for="newPassword">New Password</label>
                                </div>

                                <div class="form-outline w-50 mb-4">
                                    <i class="far fa-eye trailing fa-fw pe-auto" id="showConfirmPassword"></i>
                                    <input type="password" id="confirmPassword" class="form-control" required />
                                    <label class="form-label" for="confirmPassword">Confirm New Password</label>
                                </div>

                                <button type="buttton" onclick="changePassword()" id="changePasswordBtn"
                                    class="btn btn-primary btn-rounded  mt-2">
                                    <i class="fas fa-save me-2 saved-text"></i>Save Changes
                                    <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                        aria-hidden="true"> </span>
                                </button>
                                <!-- </form> -->


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include "./inc/footer.php"; ?>
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/mdb.umd.min.js"></script>
    <script src="./js/mdb.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/alertController.js"></script>
    <script src="./js/updateProfile.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var newPassword = document.getElementById('newPassword').value;
            var confirmPassword = document.getElementById('confirmPassword').value;
            var currentPassword = document.getElementById('currentPassword').value;

            const toggleNewPassword = document.getElementById("showNewPassword");
            const toggleCurrentPassword = document.getElementById("showCurrentPassword");
            const toggleConfirmPassword = document.getElementById("showConfirmPassword");
            const togglePassword = (inputId, toggleId) => {
                const input = document.getElementById(inputId);
                const toggle = document.getElementById(toggleId);

                toggle.addEventListener("pointerdown", (e) => {
                    e.preventDefault();
                    const type = input.type === "password" ? "text" : "password";
                    input.type = type;
                    toggle.classList.toggle("fa-eye");
                    toggle.classList.toggle("fa-eye-slash");
                });
            };

            togglePassword("currentPassword", "showCurrentPassword");
            togglePassword("newPassword", "showNewPassword");
            togglePassword("confirmPassword", "showConfirmPassword");


        })

    </script>
</body>

</html>