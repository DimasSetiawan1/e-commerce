<?php

error_reporting(E_ALL);
include_once './inc/config.inc.php';
include_once './inc/config_session.inc.php';

include_once './utils/checkoutController.php';


if (!isset($_POST['id']) && ($_POST['id'] != $_SESSION['user_id'])) {
    header("Location: mycart.php");
    die();
} else {
    $id = secure(is_numeric($_POST['id']) ? $_POST['id'] : 0);
    $result = getDataItems($id);
}





?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrendZ | Online Store for Latest Trends</title>
    <link rel="stylesheet" href="./css/mdb.min.css  ">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

</head>

<body>
    <section>
        <?php include('./inc/header.php');
        if (strlen(isset($_SESSION['user_id']) == 0)) {
            header("Location: login.php");
        } else {
            if (isset($_POST['action']) && $_POST['action'] == 'applyCoupon') {
                $coupon_code = secure((string) $_POST['coupon']);
                $getRequest = applyDiscount($coupon_code);
                $voucherDecode = json_decode($getRequest);
                if ($voucherDecode->status === "success") {
                    echo "<script>Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{$voucherDecode->message}',
            });</script>";
                } else {
                    echo "<script>Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{$voucherDecode->message}',
            });</script>";
                }
            } ?>

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
                            <h2 class="card-title ps-4 pt-4"><i class="fa-solid fa-location-dot "></i>
                                Alamat
                            </h2>
                            <!-- <div class="card-body">
                            </div> -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 mb-3">
                                        <?php
                                        $address = getAddresses($_SESSION['user_id']);

                                        $full_address = [];
                                        foreach ($address as $key => $value) {
                                            $full_address[] = [
                                                'id' => $value->id,
                                                'address' => $value->full_address . ", " . $value->city . ", " . $value->province . " " . $value->postal_code,
                                                'name' => $value->name,
                                                'phone_number' => $value->phone_number,
                                                'is_default' => $value->is_default
                                            ];
                                        }

                                        if (!empty($full_address)) {
                                            $default_address = array_filter($full_address, function ($addr) {
                                                return $addr['is_default'] == 1;
                                            });

                                            if (!empty($default_address)) {
                                                $default_address = reset($default_address);
                                                echo "<h5 class='card-title' data-id='{$default_address['id']}' id='address-title'>{$default_address['name']} | {$default_address['phone_number']}</h5>";
                                                echo "<p class='card-text' id='full-address'>{$default_address['address']}</p>";
                                                ?>
                                            </div>

                                            <div class="col-md-6 mb-3 pt-0 pe-5 text-end ">
                                                <span class="badge bg-dark me-3 p-2" id="is-default">

                                                    <?php if ($default_address['is_default'] == 1) {
                                                        echo "Utama";
                                                    } else {
                                                        echo "";
                                                    }
                                            }
                                        }

                                        ?>
                                        </span>
                                        <button class="btn btn-link btn-sm">
                                            Edit
                                        </button>

                                        <template id="choose-address">
                                            <swal-html>
                                                <!-- address radio button -->
                                                <?php
                                                foreach ($full_address as $key => $value) {
                                                    ?>
                                                    <div class="container mb-3">
                                                        <div class="row align-items-center justify-content-around">
                                                            <div class="col-auto">
                                                                <input class="form-check-input" type="radio"
                                                                    name="addressSelect" value="<?= $value['id'] ?>"
                                                                    id="flexRadioDefault_<?= $key ?>"
                                                                    data-is-default="<?= $value['is_default'] ?>"
                                                                    <?= $value['is_default'] == true ? "checked" : "" ?> />
                                                            </div>
                                                            <div class="col">
                                                                <label class="form-check-label w-100"
                                                                    for="flexRadioDefault_<?= $key ?>">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <h5 class='card-title'
                                                                                id="title-<?= $value['id'] ?>">
                                                                                <?= $value['name'] ?> |
                                                                                <?= $value['phone_number'] ?>
                                                                            </h5>
                                                                            <p class='card-text'
                                                                                id="address-<?= $value['id'] ?>">
                                                                                <?= $value['address'] ?>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }
                                                ?>
                                            </swal-html>
                                        </template>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Barang yang Dipesan -->
                    <div class="col-md-12 col-sm-12 ">
                        <div class="card ">
                            <h2 class="card-title ms-3 mt-5 ">
                                Produk Dipesan
                            </h2>
                            <div class="card-body">
                                <table class="table table-responsive table-borderless">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-center">Harga Satuan</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-center">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $subtotal = 0;
                                        foreach ($result as $key => $item) {
                                            $subtotalPerProduct = $item->price * $item->quantity;
                                            $subtotal += $item->price * $item->quantity;
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src='./img/products/<?= $item->img ?>' alt='<?= $item->title ?>'
                                                            class='img-thumbnail me-3'
                                                            style='width: 80px; height: 80px; object-fit: cover;'>
                                                        <h6 class='mb-0'><?= $item->title ?></h6>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?= $formatter->formatCurrency($item->price, "IDR") ?>
                                                </td>
                                                <td class="text-center" id="qty-<?= $item->quantity ?>"><?= $item->quantity ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $formatter->formatCurrency($subtotalPerProduct, "IDR") ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 mt-4">
                            <div class="card ">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        Tambah Voucher
                                    </h5>
                                    <form action="checkout.php" method="post">
                                        <input type="hidden" name="id" value="<?= $_SESSION['user_id'] ?>">
                                        <input type="text" class="form-control w-50" name="coupon"
                                            placeholder="Masukan voucher" required>
                                        <br>
                                        <button type="submit" class="btn btn-primary" name="action"
                                            value="applyCoupon">Tambah</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 mt-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Daftar Voucher</h5>
                                    <ul class="list-group">
                                        <?php
                                        $vouchers = getAvailableVouchers();
                                        if (!empty($vouchers)) {
                                            foreach ($vouchers as $voucher) {
                                                $isSelected = $voucher->is_select === 1 ? 'disabled' : '';
                                                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                                                echo "<span>{$voucher->coupon_code} - Diskon {$voucher->value}%</span>";
                                                echo "<button class='btn btn-sm btn-primary {$isSelected}' data-id='{$voucher->id}' id='discount-{$voucher->value}' onclick='applyVoucher(\"{$voucher->value}\",\"{$voucher->id}\")'>Gunakan</button>";
                                                echo "</li>";
                                            }
                                        } else {
                                            echo "<li class='list-group-item'>Input Voucher Terlebih Dahulu</li>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Card Metode Pembayaran -->
                    <div class="col-md-4 col-sm-4  mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Metode Pembayaran</h5>
                                <select class="form-select" id="paymentMethod" name="paymentMethod">
                                    <option value="qris">QRIS</option>
                                    <option value="bankTransfer">Transfer Bank</option>
                                    <option value="creditCard">Kartu Kredit</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Card Metode Pengiriman -->

                    <div class="col-md-4 col-sm-4 mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Metode Pengiriman</h5>
                                <select class="form-select" id="shippingMethod" name="shippingMethod">
                                    <?php
                                    $couriers = getCouriers();
                                    if (!empty($couriers)) {
                                        foreach ($couriers as $courier) {
                                            echo "<option value='{$courier->id}' data-price='{$courier->price}'>{$courier->name} - " . $formatter->formatCurrency($courier->price, 'IDR') . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No couriers available</option>";
                                    }

                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 mt-4">
                        <div class="card">
                            <div class="card-body ">
                                <h5 class="card-title">Ringkasan Pesanan</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Harga Barang</span>
                                    <span id="totalPrice"><?= $formatter->formatCurrency($subtotal, "IDR") ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Biaya Pengiriman</span>
                                    <span id="shippingCost"></span>
                                </div>
                                <div id="" class="d-flex justify-content-between mb-2">
                                    <span id="discount-label">Diskon</span>
                                    <span id="discount"></span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Total Pembayaran</strong>
                                    <strong id="totalPayment"></strong>
                                </div>

                                <button id="checkoutBtn" class="btn btn-primary btn-block" onclick="processCheckout()">
                                    <i class="fas fa-shopping-cart me-2"></i>Checkout Sekarang
                                </button>
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
    <script src="./js/alertController.js"></script>
    <script src="./js/checkoutController.js"></script>

    <script>

        document.querySelector('.btn-link').addEventListener('click', function () {
            Swal.fire({
                title: 'Pilih Alamat',
                html: document.getElementById('choose-address').innerHTML,
                showCancelButton: true,
                confirmButtonText: 'Pilih',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const selectedAddress = document.querySelector('input[name="addressSelect"]:checked');

                    if (!selectedAddress) {
                        Swal.showValidationMessage('Pilih salah satu alamat');
                        return false;
                    }

                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const selectedAddress = document.querySelector('input[name="addressSelect"]:checked');
                    const selectedId = selectedAddress.value;
                    const isDefault = selectedAddress.dataset.isDefault;


                    const titleId = `title-${selectedId}`;
                    const addressId = `address-${selectedId}`;

                    const title = document.getElementById(titleId).textContent;
                    const nameContent = title.split('|')[0];
                    const phoneNumberContent = title.split('|')[1];

                    const address = document.getElementById(addressId).textContent;

                    updateSelectedAddress(isDefault, nameContent, phoneNumberContent, address);
                }
            })


        });

    </script>

</body>

</html>