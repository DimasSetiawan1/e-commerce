<?php
session_start();
error_reporting(E_ALL);
include('config.php');



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


echo json_encode($_SESSION);

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
    <link href="./admin/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="./admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
                <h3 class="p-5 m-5 text-center">Please Login To Check Cart</h3>
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
                            $qty = $_SESSION['cart'][$_SESSION['user_id']][$result->id]['quantity'] ?? null;
                            ?>
                            <div class="card cart-item p-4 mb-3 shadow-lg rounded-3 " data-qty="1" style=" height: auto; ">
                                <div class=" content rounded-3">
                                    <div class="row">
                                        <div class="col-md-3" style="width: 100px; height: auto;">
                                            <img src="./img/products/<?= $result->img ?>" alt="" class="img-fluid">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h6 class="text-truncate" style="width: 150px;"><?= $result->title ?></h6>
                                            <p class="text-muted my-3 price">
                                                <?= $formatter->formatCurrency($result->price, "IDR") ?>
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="ml-4 mb-3">Quantity</h6>
                                            <button type="button" class="btn btn-outline-dark mr-3 minus"
                                                data-id="<?= $result->id ?>">-</button>
                                            <span class="quantity">
                                                <?= $qty ?? $result->quantity ?>
                                            </span>
                                            <button type="button" class="btn btn-outline-dark ml-3 add"
                                                data-id="<?= $result->id ?>">+</button>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h6>Total</h6>
                                            <p class="text-muted total">
                                                <?php
                                                if (isset($qty) > 1) {
                                                    echo $formatter->formatCurrency($result->price * isset($qty), "IDR");
                                                } else if ($result->quantity > 1) {
                                                    echo $formatter->formatCurrency($result->price * $result->quantity, "IDR");
                                                } else {
                                                    echo $formatter->formatCurrency($result->price, "IDR");

                                                }
                                                ?>
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
                    <div class="card p-4 mb-3 shadow-sm">
                        <h6 class="mb-3">Calculated Shipping</h6>
                        <form>
                            <div class="mb-2">
                                <select class="form-select" aria-label="Country">
                                    <option selected>Country</option>
                                    <option value="1">USA</option>
                                    <option value="2">Canada</option>
                                    <option value="3">UK</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <input type="text" class="form-control" placeholder="ZIP Code">
                            </div>
                            <button type="button" class="btn btn-primary w-100">Update</button>
                        </form>
                    </div>

                    <div class="card p-4 shadow-sm">
                        <h6 class="mb-3">Cart Total</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Cart Subtotal</span>
                            <span>$71.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping & Handling</span>
                            <span>$8.50</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount</span>
                            <span>$0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span>$87.50</span>
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