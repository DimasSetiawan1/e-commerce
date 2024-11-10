<?php
session_start();
error_reporting(E_ALL);
include('config.php');

$discount = 0;

if (isset($_SESSION['user_id'])) {
    $user = $_SESSION['user_id'];


    // FECTH PRODUCTS
    $sql = "SELECT cart_03.id,cart_03.quantity,products_03.title,products_03.price,products_03.img FROM cart_03 INNER JOIN products_03 ON products_03.id = cart_03.productid WHERE cart_03.user=:user";
    $query = $db->prepare($sql);
    $query->bindParam(':user', $user, PDO::PARAM_STR);
    $query->execute();
    $itemCount = $query->rowCount();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    // TOTAL
    $sql = "SELECT SUM(products_03.price) as total FROM cart_03 INNER JOIN products_03 ON products_03.id = cart_03.productid WHERE cart_03.user=:user";
    $query = $db->prepare($sql);
    $query->bindParam(':user', $user, PDO::PARAM_STR);
    $query->execute();
    $total = $query->fetch(PDO::FETCH_OBJ);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrendZ | Online Store for Latest Trends</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="./css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <script>
        if (typeof window.history.pushState == 'function') {
            window.history.pushState({}, "Hide", '<?= $_SERVER['PHP_SELF']; ?>');
        }
    </script>
</head>

<body>
    <section>
        <?php include('./inc/header.php'); ?>

        <?php if (strlen(isset($_SESSION['user_id']) == 0)) { ?>
            <h3 class="p-5 m-5 text-center">Please <a href="./login.php">Login</a> To Check Cart</h3>
            <!-- <div class="container mt-5 p-5">
            </div> -->

        <?php } else { ?>

            <!-- Modal -->
            <div class="modal fade statusSuccessModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
                data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-body text-center p-lg-4">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                <circle class="path circle" fill="none" stroke="#198754" stroke-width="6"
                                    stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />
                                <polyline class="path check" fill="none" stroke="#198754" stroke-width="6"
                                    stroke-linecap="round" stroke-miterlimit="10"
                                    points="100.2,40.2 51.5,88.8 29.8,67.5 " />
                            </svg>
                            <h4 class="text-success mt-3">Success</h4>
                            <p class="mt-3 msg"></p>
                            <!-- <button type="button" class="btn btn-sm mt-3 btn-success" data-bs-dismiss="modal">Ok</button> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="container ">
                <div class="d-md-flex flex-column ">
                    <h2 class="my-3 ">Shopping Bag</h2>
                    <p class="text-muted  "><?= isset($itemCount) ? "$itemCount" : "" ?> items in your cart
                    </p>
                </div>
                <div class="row row-cols-md-2  row-cols-sm-1 justify-content-center ">

                    <!-- Product Card Section -->
                    <div class="col-sm-5 col-md-5">
                        <?php if (isset($results)) {
                            foreach ($results as $i => $result) {
                                $qty = $_SESSION['cart'][$_SESSION['user_id']][$result->id]['quantity'] ?? $result->quantity;
                                $total = $_SESSION['cart'][$_SESSION['user_id']][$result->id]['total'] ?? $result->price * $qty;
                                ?>
                                <div class="card cart-item mb-3 shadow-lg bg-dark " data-qty="<?= $qty ?? $result->quantity ?>"
                                    style="border-radius: 20px;">
                                    <img src="./img/products/<?= $result->img ?>" alt="" style="height: 15rem; height: 15rem;"
                                        class="card-img-top" />
                                    <div class="card-body d-flex align-items-center">
                                        <!-- <div class="row "> -->
                                        <!-- Gambar produk -->
                                        <!-- <div class="row "> -->
                                        <!--  <div class="col-md-3 col-sm-3 bg-danger align-content-center"> -->
                                        <!-- </div> -->


                                        <!-- Detail produk -->
                                        <div class="col-md-4 col-sm-4 bg-secondary col-md-3 mb-3  align-content-center">
                                            <h6 class="text-truncate w-75"><?= $result->title ?></h6>
                                            <p class="text-muted my-3 price">
                                                <?= $formatter->formatCurrency($result->price, "IDR") ?>
                                            </p>
                                            <button type="button" class=" btn mr-2 btn-outline-dark  minus"
                                                data-id="<?= $result->id ?>"
                                                style="width: 30%;height: 30%; text-align: center; padding: 0px;">-</button>
                                            <span class="quantity mx-2">
                                                <?= $qty ?? $result->quantity ?>
                                            </span>
                                            <button type="button" style="width: 30%;height: 30%; text-align: center; padding: 0px;"
                                                class=" btn btn-outline-dark ml-2  add" data-id="<?= $result->id ?>">+</button>
                                        </div>
                                        <!-- Tombol hapus -->
                                        <div
                                            class="col-md-4 col-sm-4 bg-light col-md-1 order-3 order-md-5 align-content-center text-center">
                                            <button class="btn btn-outline-danger fa fa-trash bg-text-danger delete"
                                                onClick="removeCart(<?= $result->id ?>)"></button>
                                        </div>

                                        <!-- </div> -->
                                        <!-- Kuantitas produk -->
                                        <!-- <div class="col-sm-3  bg-info col-md-3 mb-3  order-4 order-md-3">
                                        <h6 class="text-center ">Quantity</h6>
                                        <div class="d-flex justify-content-center align-items-center ">
                                            <button type="button" class=" btn mr-2 btn-outline-dark  minus"
                                                data-id="<?= $result->id ?>"
                                                style="width: 30%;height: 30%; text-align: center; padding: 0px;">-</button>
                                            <span class="quantity mx-2">
                                                <?= $qty ?? $result->quantity ?>
                                            </span>
                                            <button type="button" style="width: 30%;height: 30%; text-align: center; padding: 0px;"
                                                class=" btn btn-outline-dark ml-2  add" data-id="<?= $result->id ?>">+</button>
                                        </div>
                                    </div> -->

                                        <!-- Total harga -->
                                        <!-- <div
                                        class="col-2 bg-success col-md-2 text-center order-5 order-md-4 mb-3  align-content-center  ">
                                        <h6>Total</h6>
                                        <p class="text-muted total">
                                            <?= $formatter->formatCurrency($total == 0 ? $result->price : $total, "IDR") ?>
                                        </p>
                                    </div> -->


                                        <!-- </div> -->
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <!-- Side Card Section -->
                    <div class=" col-sm-3  col-md-3">
                        <div class="card p-4 mb-3 shadow-lg cart-discount" style="border-radius: 20px;">
                            <h6 class="mb-3">Voucher Discount</h6>
                            <div id="alert" class="alert d-none">
                                <strong class="text-notification"></strong>
                            </div>
                            <div class="mb-2">
                                <input type="text" class="form-control inputVoucher" placeholder="Enter voucher code">
                            </div>
                            <button type="button" class="btn btn-primary w-100 addDiscount">Apply</button>
                        </div>
                        <!-- <div class="col-sm-3">
                        </div> -->

                        <div class="card p-4 shadow-lg" style="border-radius: 20px;">
                            <h6 class="mb-3">Cart Total</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Cart Subtotal</span>
                                <span id="subtotal">0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 setDiscount">
                                <span>Discount</span>
                                <span class="discount">0</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span id="total"></span>
                            </div>
                            <button type="submit" name="" class="btn btn-primary w-100 mt-3"
                                onclick="toCheckout()">Checkout</button>
                        </div>
                        <!-- <div class="col-sm-3">
                        </div> -->
                    </div>
                </div>
            </div>

        <?php } ?>


        <?php include('./inc/footer.php'); ?>
    </section>

    <script src="./js/jquery-3.3.1.slim.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/cart.js"></script>


</body>

</html>