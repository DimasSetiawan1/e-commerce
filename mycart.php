<?php
session_start();
error_reporting(E_ALL);
include('config.php');

$discount = 0;

if (isset($_SESSION['user_id'])) {
    $user = $_SESSION['user_id'];


    if (isset($_GET['rem'])) {
        $productid = secure($_GET['rem']);
        $sql = "DELETE FROM cart_03 WHERE id = (:productid)";
        $query = $db->prepare($sql);
        $query->bindParam(':productid', $productid, PDO::PARAM_STR);
        $query->execute();
    }

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
            <div class="container mt-5 p-5">
                <h3 class="p-5 m-5 text-center">Please <a href="./login.php">Login</a> To Check Cart</h3>
            </div>

        <?php } else { ?>
            <div class="container " style="margin-left: 16%;">
                <h2 class="my-3 ">Shopping Bag</h2>
                <p class="text-muted  "><?= isset($itemCount) ? "$itemCount" : "" ?> items in your cart
                </p>
            </div>

            <div class="row  justify-content-center ">
                <!-- Product Card Section -->
                <div class="col-md-5  ">
                    <?php if (isset($results)) {
                        foreach ($results as $i => $result) {
                            $qty = $_SESSION['cart'][$_SESSION['user_id']][$result->id]['quantity'];
                            $total = $_SESSION['cart'][$_SESSION['user_id']][$result->id]['total'];

                            ?>
                            <div class="card cart-item mb-3 shadow-lg " data-qty="<?= $qty ?? $result->quantity ?>"
                                style="border-radius: 20px;">
                                <div class=" card-body">
                                    <div class="row">
                                        <div class="col-md-3" style="width: 100px; height: auto;">
                                            <img src="./img/products/<?= $result->img ?>" alt="" class="img-fluid"
                                                style="border-radius: 10px;">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h6 class="text-truncate" style="width: 150px;"><?= $result->title ?></h6>
                                            <p class="text-muted my-3 price">
                                                <?= $formatter->formatCurrency($result->price, "IDR") ?>
                                            </p>
                                        </div>
                                        <div class="col-md-3  " style="padding-top: 0px;">
                                            <h6 class=" mb-3 text-center">Quantity</h6>
                                            <div class="row justify-content-center ">
                                                <button type="button" class="btn mr-2 btn-outline-dark  minus"
                                                    data-id="<?= $result->id ?>"
                                                    style="width: 30px;height: 30px; text-align: center; padding-top: 0px;">-</button>
                                                <span class="quantity">
                                                    <?= $qty ?? $result->quantity ?>
                                                </span>
                                                <button type="button"
                                                    style="width: 30px;height: 30px; text-align: center; padding: 0px;"
                                                    class="btn btn-outline-dark ml-2  add" data-id="<?= $result->id ?>">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h6>Total</h6>
                                            <p class="text-muted total">
                                                <?= $formatter->formatCurrency($total == 0 ? $result->price : $total, "IDR") ?>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <!-- Side Card Section -->
                <div class="col-md-3">
                    <div class="card p-4 mb-3 shadow-sm cart-discount" style="border-radius: 20px;">
                        <h6 class="mb-3">Voucher Discount</h6>
                        <div id="msg" class="alert alert-success d-none">
                            <strong>Voucher Applied! </strong>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control inputVoucher" placeholder="Enter voucher code">
                        </div>
                        <button type="button" class="btn btn-primary w-100 addDiscount">Apply</button>

                    </div>

                    <div class="card p-4 shadow-sm" style="border-radius: 20px;">
                        <h6 class="mb-3">Cart Total</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Cart Subtotal</span>
                            <span id="subtotal">0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping & Handling</span>
                            <span>$8.50</span>
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
                        <button type="button" class="btn btn-primary w-100 mt-3">Checkout</button>
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