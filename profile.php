<?php

include_once './inc/config.inc.php';
include_once './inc/config_session.inc.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

try {
    $stmt = $db->prepare("SELECT * FROM users_03 WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
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
                                        <td><label for="name">Nama</label></td>
                                        <td>
                                            <div class="form-outline" data-mdb-input-init>
                                                <input type="text" class="form-control" id="name"
                                                    value="<?= $user->name; ?>">
                                                <label for="name" class="form-label">Nama</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="email">Email</label></td>
                                        <td>
                                            <div class="form-outline" data-mdb-input-init>
                                                <i class="btn btn-link fa-1x far fa-pen-to-square trailing fa-fw pe-auto"
                                                    id="editEmail"></i>
                                                <input type="text" id="email" data-original-email="<?= $user->email; ?>"
                                                    class="form-control form-icon-trailing" disabled
                                                    value="<?= $user->email; ?>" />
                                                <label class="form-label" for="email">Email</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="phone_number">Nomor Telepon</label></td>
                                        <td>
                                            <div class="form-outline" data-mdb-input-init>
                                                <i class="btn btn-link fa-1x far fa-pen-to-square trailing fa-fw pe-auto"
                                                    id="editPhoneNumber"></i>
                                                <input type="text" id="phone_number" name="phone_number"
                                                    data-original-phone-number="<?= $user->phone_number; ?>"
                                                    class="form-control form-icon-trailing" disabled
                                                    value="<?= $user->phone_number; ?>" />
                                                <label class="form-label" for="phone_number">Nomor Telepon</label>
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
    <script src="./js/alertController.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editPhoneNumberBtn = document.getElementById('editPhoneNumber');
            const email = document.getElementById('email').value
            const editEmailBtn = document.getElementById('editEmail')
            const [nick, provider] = email.split('@')
            const maskedNick = nick.slice(0, 2) + '*'.repeat(Math.max(0, nick.length - 2))
            const maskedEmail = `${maskedNick}@${provider}`

            const phoneNumber = document.getElementById('phone_number')
            const maskedPhoneNumber =
                '*'.repeat(phoneNumber.value.length - 2) + phoneNumber.value.slice(-2)
            phoneNumber.value = maskedPhoneNumber

            document.getElementById('email').value = maskedEmail
            if (editEmailBtn) {
                editEmailBtn.addEventListener('click', setEmail);
            }

            if (editPhoneNumberBtn) {
                editPhoneNumberBtn.addEventListener('click', setPhoneNumber);
            }
        });

    </script>

</body>

</html>