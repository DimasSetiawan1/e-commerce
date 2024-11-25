<?php
session_start();
error_reporting(E_ALL);
include('config.php');
include('./utils/checkoutController.php');




if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

// buat agar $_SESSION tersusun rapih json 

foreach ($_SESSION as $key => $value) {
    echo "<strong>$key</strong>: ";
    if (is_array($value)) {
        echo "<pre>" . print_r($value, true) . "</pre>";
    } else {
        echo "$value<br>";
    }
}
// jika ada aksi (add, remove, update)




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrendZ | Online Store for Latest Trends</title>
    <link rel="stylesheet" href="./css/mdb.min.css  ">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

</head>

<body>
    <section>
        <?php include('./inc/header.php');
        if (strlen(isset($_SESSION['user_id']) == 0)) {
            header("Location: login.php");
        } else { ?>

            <div class="container">
                <div class="row mx-auto p-5">
                    <!-- Card Halaman -->
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title">Checkout</h2>
                                <p class="card-text">Selesaikan pembelian Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title">Alamat</h2>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Alamat Pengiriman</h5>
                                <p class="card-text">
                                    <?php
                                    $address = getAddresses($_SESSION['user_id']);
                                    echo $address['nama'] . '<br>';
                                    echo $address['nomor_telepon'] . '<br>';
                                    echo $address['alamat_lengkap'] . ', ' . $address['kota'] . '<br>';
                                    echo $address['provinsi'] . ' ' . $address['kode_pos'];
                                    ?>
                                </p>
                                <!-- <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                            </div>
                                        </div>
                                    </div>

                                </div> -->
                            </div>
                        </div>
                    </div>

                    <!-- Card Barang yang Dipesan -->
                    <div class="col-md-8 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Barang yang Dipesan</h5>
                                <ul class="list-group list-group-flush">
                                    <?php
                                    $cart_items = $_SESSION['cart'];
                                    foreach ($cart_items as $item) {
                                        $data = getDataProducts($item['productid']);
                                        ?>
                                        <li class='list-group-item d-flex align-items-center '>
                                            <img src='./img/products/<?= $data->img ?>' alt='<?= $data->title ?>'
                                                class='img-thumbnail mr-3'
                                                style='width: 100px; height: 100px; object-fit: cover;'>
                                            <div>
                                                <h6 class=' mb-0'><?= $data->title ?></h6>
                                                <p class='mb-0'>
                                                    <?= $formatter->formatCurrency($data->price, "IDR") . " x" . $item['quantity'] ?>
                                                </p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Card Metode Pembayaran -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Metode Pembayaran</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard"
                                        checked>
                                    <label class="form-check-label" for="creditCard">Kartu Kredit</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="bankTransfer">
                                    <label class="form-check-label" for="bankTransfer">Transfer Bank</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Perhitungan dan Tombol Bayar -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Ringkasan Pembayaran</h5>
                                <p>Total Harga: Rp <?php echo number_format($total_price, 0, ',', '.'); ?></p>
                                <p>Biaya Pengiriman: Rp <?php echo number_format($shipping_cost, 0, ',', '.'); ?></p>
                                <h4>Total Pembayaran: Rp
                                    <?php echo number_format($total_price + $shipping_cost, 0, ',', '.'); ?>
                                </h4>
                                <button class="btn btn-primary btn-lg mt-3">Bayar Sekarang</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php }
        include('./inc/footer.php'); ?>
    </section>

    <script src="./js/jquery-3.3.1.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/mdb.min.js"></script>
    <script src="./js/mdb.umd.min.js"></script>

</body>

</html>