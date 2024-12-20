<?php
include_once './inc/config.inc.php';
include_once './inc/config_session.inc.php';



$current_page = basename($_SERVER['PHP_SELF']);


// Get address user from database

$stmt = $db->prepare("SELECT * FROM addresses_03 WHERE user_id = :user ORDER BY is_default DESC");
$stmt->execute(['user' => $_SESSION['user_id']]);
$addresses = $stmt->fetchAll(PDO::FETCH_OBJ);



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
                                            <h5 class="card-title">Alamat Saya</h5>
                                        </div>
                                        <div class="col-sm-6 col-md-6 d-flex justify-content-end">
                                            <button class="btn btn-primary" id="add-address" data-mdb-ripple-init
                                                data-mdb-modal-init data-mdb-target="#addressModal">Tambah
                                                Alamat Baru</button>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <?php include "./inc/modal_address.php" ?>
                        </div>
                        <hr class="my-3">
                        <div class="card">
                            <div class="card-body">
                                <div id="address-list" class="container">
                                    <div class="row">
                                        <!-- Address list goes here -->
                                        <?php
                                        if (!empty($addresses)) {
                                            foreach ($addresses as $address) {
                                                ?>
                                                <div class="card h-50">
                                                    <div class="card-body">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-11 col-md-11 px-0">
                                                                    <span><?= $address->name ?> |
                                                                        <?= $address->phone_number ?></span>
                                                                </div>
                                                                <div
                                                                    class="col-1 col-md-1 text-right d-flex justify-content-center">
                                                                    <button class="btn btn-sm h-100 btn-link edit-address"
                                                                        data-mdb-ripple-init data-mdb-button-init
                                                                        onclick="editAddress(<?= $address->id ?>)"
                                                                        id="edit-address">Edit</button>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-8 col-md-8">
                                                                    <div class="row d-flex">
                                                                        <?= "$address->full_address, $address->province,$address->city, $address->postal_code " ?>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="col-4 col-md-4 text-center my-3 d-flex justify-content-center  ">
                                                                    <button onclick="setDefaultAddress(<?= $address->id ?>)"
                                                                        <?= $address->is_default == true ? 'disabled' : '' ?>
                                                                        class="btn btn-sm btn-link text-nowrap border
                                                                        border-info">Atur
                                                                        sebagai
                                                                        alamat utama</button>
                                                                </div>

                                                            </div>
                                                            <div class="row my-3 px-0 ">
                                                                <div
                                                                    class="col-4 col-md-4 d-flex <?= $address->is_default == true ? 'justify-content-between' : '' ?>  px-0">
                                                                    <?php
                                                                    if ($address->is_default) { ?>
                                                                        <div class="col-5 col-md-5 px-0">
                                                                            <div class="card h-75 border border-info">
                                                                                <div
                                                                                    class="card-title py-2 d-flex align-items-center justify-content-center">
                                                                                    <span class="text-center mb-1">Utama</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class=" col-5 col-md-5 px-0">
                                                                            <div
                                                                                class="card h-75 bg-secondary border border-secondary">
                                                                                <div
                                                                                    class="card-title text-white py-2 d-flex align-items-center justify-content-center">
                                                                                    <span
                                                                                        class="text-center mb-1"><?= $address->label ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } else { ?>

                                                                        <div class=" col-5 col-md-5 px-0">
                                                                            <div
                                                                                class="card h-75 bg-secondary border border-secondary">
                                                                                <div
                                                                                    class="card-title text-white py-2 d-flex align-items-center justify-content-center">
                                                                                    <span
                                                                                        class="text-center mb-1"><?= $address->label ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }
                                        } else { ?>

                                            <div class="col-12 text-center">
                                                <h3>Anda belum memiliki alamat</h3>

                                            </div>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/getRegion.js"></script>
    <script src="./js/alertController.js"></script>
    <script src="./js/addressController.js"></script>
    <script type="text/javascript">


        $(document).ready(function () {
            const modal = new mdb.Modal(document.getElementById('addressModal'), {
                focus: true
            });


            $('#add-address').click(function () {
                $('#addressModalLabel').text('Tambah Alamat');
            });

            $('#edit-address').click(function () {
                $('#addressModalLabel').text('Edit Alamat');
            });


        });

    </script>
</body>

</html>