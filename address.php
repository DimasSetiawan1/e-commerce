<?php
session_start();
include_once 'config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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


</head>

<body>

    <?php include "./inc/header.php"; ?>
    <main>
        <section>
            <div class="container mx-auto">
                <div class="row justify-content-between">
                    <div class="col-sm-4 col-md-4 mt-5 ">
                        <div class="card">

                            <div class="card-body align-items-center mx-auto">
                                <img src="./img/user.png" alt="test" class="rounded-circle " height="100px"
                                    width="100px" loading="lazy" />

                                <h5 class="mt-3 text-center align-items-center fw-bold ">
                                    <?= $_SESSION['username']; ?>
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
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6 pt-2 ">
                                            <h5 class="card-title">Alamat Saya</h5>
                                        </div>
                                        <div class="col-sm-6 col-md-6 d-flex justify-content-end">
                                            <button class="btn btn-primary" id="add-address">Tambah Alamat Baru</button>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="modal fade" id="addressModal" tabindex="-1" role="dialog"
                                aria-labelledby="addressModalLabel" aria-hidden="true">
                                cdiv class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addressModalLabel">Tambah/Edit Alamat</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="addressForm">
                                            <input type="hidden" id="address-id">
                                            <div class="form-group">
                                                <label for="label">Label Alamat</label>
                                                <input type="text" class="form-control" id="label" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="full-address">Alamat Lengkap</label>
                                                <textarea class="form-control" id="full-address" rows="3"
                                                    required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="city">Kota</label>
                                                <input type="text" class="form-control" id="city" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="province">Provinsi</label>
                                                <input type="text" class="form-control" id="province" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="postal-code">Kode Pos</label>
                                                <input type="text" class="form-control" id="postal-code" required>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Tutup</button>
                                        <button type="button" class="btn btn-primary" id="save-address">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="card">
                            <div class="card-body">
                                <div id="address-list" class="container">
                                    <div class="row">
                                        <!-- Address list goes here -->
                                        <div class="card h-50">
                                            <div class="card-body">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-11 col-md-11 px-0">
                                                            <span>Nama | nomor</span>
                                                        </div>
                                                        <div
                                                            class="col-1 col-md-1 text-right d-flex justify-content-center">
                                                            <button class="btn btn-sm h-100 btn-link edit-address"
                                                                data-id="1">Edit</button>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-9 col-md-9">
                                                            <div class="row d-flex">
                                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                                                Sit nesciunt ad mollitia facere! Dignissimos, deserunt
                                                                molestias commodi quos, a, reprehenderit necessitatibus
                                                                magni ea doloribus recusandae ipsa dolores ipsum placeat
                                                                omnis.
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="col-3 col-md-3 text-center my-3 d-flex justify-content-center ">
                                                            <button
                                                                class="btn btn-sm h-50 btn-link text-nowrap border border-info">Atur
                                                                sebagai
                                                                alamat utama</button>
                                                        </div>

                                                    </div>
                                                    <div class="row my-3 px-0 ">
                                                        <div class="col-4 col-md-4 d-flex justify-content-around px-0">
                                                            <div class="col-5 col-md-5 px-0">
                                                                <div class="card h-75 border border-info">
                                                                    <div
                                                                        class="card-title py-2 d-flex align-items-center justify-content-center">
                                                                        <span class="text-center mb-1">Utama</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-5 col-md-5 px-0">
                                                                <div
                                                                    class="card h-75 bg-secondary border border-secondary">
                                                                    <div
                                                                        class="card-title text-white py-2 d-flex align-items-center justify-content-center">
                                                                        <span class="text-center mb-1">LABEL</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <script>
                                $(document).ready(function () {
                                    $('#add-address').click(function () {
                                        $('#addressForm')[0].reset();
                                        $('#address-id').val('');
                                        $('#addressModal').modal('show');
                                    });

                                    $('.edit-address').click(function () {
                                        var addressId = $(this).data('id');
                                        // Fetch address details and populate the form
                                        // This is a placeholder. You need to implement the actual data fetching.
                                        $('#address-id').val(addressId);
                                        $('#addressModal').modal('show');
                                    });

                                    $('.delete-address').click(function () {
                                        var addressId = $(this).data('id');
                                        if (confirm('Apakah Anda yakin ingin menghapus alamat ini?')) {
                                            // Delete address
                                            // This is a placeholder. You need to implement the actual deletion.
                                        }
                                    });

                                    $('#save-address').click(function () {
                                        // Save or update address
                                        // This is a placeholder. You need to implement the actual saving/updating.
                                        $('#addressModal').modal('hide');
                                    });
                                });
                            </script> -->
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include "./inc/footer.php"; ?>

    <script src="./js/jquery-3.3.1.slim.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/mdb.umd.min.js"></script>
    <script src="./js/mdb.min.js"></script>

</body>

</html>