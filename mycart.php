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
    <!-- <link rel="stylesheet" href="./css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" href="./css/bootstrap-grid.min.css"> -->
    <!-- <link rel="stylesheet" href="./css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/mdb.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
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

            <section class="h-100 h-custom">
                <div class="container py-5 h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-12">
                            <div class="card text-bg-dark card-registration card-registration-2"
                                style="border-radius: 15px;">
                                <div class="card-body p-0">
                                    <div class="row g-0">
                                        <div class="col-lg-8">
                                            <div class="p-5">
                                                <div class="d-flex justify-content-between align-items-center mb-5">
                                                    <h1 class="fw-bold mb-0">Shopping Cart</h1>
                                                    <h6 class="mb-0 text-white-50"><?= $itemCount ?? "" ?> items
                                                    </h6>
                                                </div>
                                                <hr class="my-4">
                                                <?php if (isset($results)) {
                                                    foreach ($results as $i => $result) {
                                                        $qty = $_SESSION['cart'][$_SESSION['user_id']][$result->id]['quantity'] ?? $result->quantity;
                                                        $total = $_SESSION['cart'][$_SESSION['user_id']][$result->id]['total'] ?? $result->price * $qty;
                                                        ?>
                                                        <div class="card mb-4 shadow-lg cart-item text-bg-dark"
                                                            style="border-radius: 20px;">

                                                            <div class="card-body">
                                                                <!-- Single item -->
                                                                <div class="row">
                                                                    <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                                                                        <!-- Image -->
                                                                        <div class="bg-image hover-overlay hover-zoom ripple rounded"
                                                                            data-mdb-ripple-color="light">
                                                                            <img src="./img/products/<?= $result->img ?>"
                                                                                class="w-100" alt="<?= $result->title ?>"
                                                                                style="border-radius: 20px; " />
                                                                            <a href="#!">
                                                                                <div class="mask"
                                                                                    style="background-color: rgba(251, 251, 251, 0.2)">
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <!-- Image -->
                                                                    </div>

                                                                    <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                                                                        <!-- Data -->
                                                                        <p><strong><?= $result->title ?></strong></p>
                                                                        <p>Color: blue</p>
                                                                        <p>Size: M</p>
                                                                        <button type="button"
                                                                            class="btn btn-primary btn-sm me-1 mb-2"
                                                                            data-mdb-button-init data-mdb-ripple-init
                                                                            onclick="removeCart(<?= $result->id ?>)">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                        <!-- Data -->
                                                                    </div>


                                                                    <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                                                                        <!-- Quantity -->
                                                                        <div class="d-flex mb-4" style="max-width: 300px">
                                                                            <button class="btn h-25 btn-primary px-3 me-2 minus"
                                                                                data-mdb-button-init data-mdb-ripple-init
                                                                                data-id="<?= $result->id ?>"
                                                                                onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                                                <i class="fas fa-minus"></i>
                                                                            </button>

                                                                            <div data-mdb-input-init class="form-outline w-25">
                                                                                <input min="1" id="quantity-<?= $result->id ?>"
                                                                                    value="<?= $qty ?>" type="number" disabled
                                                                                    class="form-control bg-dark quantity" />

                                                                            </div>

                                                                            <button class="btn h-25 btn-primary px-3 ms-2 add"
                                                                                data-mdb-button-init data-mdb-ripple-init
                                                                                data-id="<?= $result->id ?>"
                                                                                onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                                                <i class="fas fa-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                        <!-- Quantity -->

                                                                        <!-- Price -->
                                                                        <p class="text-start text-md-center ">
                                                                            <strong class="total-<?= $result->id ?>"
                                                                                data-price="<?= $result->price ?>"><?= $formatter->formatCurrency($total, "IDR") ?></strong>
                                                                        </p>
                                                                        <!-- Price -->
                                                                    </div>
                                                                </div>
                                                                <!-- Single item -->
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>

                                                <hr class=" my-4">

                                                <div class="pt-5 text-white">
                                                    <h6 class="mb-0"><a href="./index.php" class="text-body"><i
                                                                class="fas fa-long-arrow-alt-left text-white me-2"></i>Back
                                                            to shop</a>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 text-bg-light">
                                            <div class="p-5">
                                                <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                                                <hr class="my-4">

                                                <div class="d-flex justify-content-between mb-4">
                                                    <h5 class="text-uppercase">items
                                                        <?= $itemCount ?? "" ?>
                                                    </h5>
                                                    <h5>subtotal</h5>
                                                </div>

                                                <h5 class="text-uppercase mb-3">Shipping</h5>

                                                <div class="mb-4 pb-2">
                                                    <select data-mdb-select-init class="select ">
                                                        <option value="1">Standard-Delivery- €5.00</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
                                                        <option value="4">Four</option>
                                                    </select>
                                                </div>


                                                <h5 class="text-uppercase mb-3 mt-3">Give code</h5>

                                                <div class="mb-2 ">
                                                    <div data-mdb-input-init class="form-outline ">
                                                        <input type="text" id="addDiscount"
                                                            class="form-control form-control-lg" />
                                                        <label class="form-label" for="addDiscount">Enter your
                                                            code</label>

                                                    </div>
                                                </div>
                                                <div id="alert" class="alert d-none">
                                                    <strong class="text-notification"></strong>
                                                </div>

                                                <hr class="my-4">

                                                <div class="d-flex justify-content-between mb-5">
                                                    <h5 class="text-uppercase">Total price</h5>
                                                    <h5></h5>
                                                </div>

                                                <button type="button"
                                                    class="btn btn-dark btn-block btn-lg">Register</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php include('./inc/footer.php'); ?>



        <?php } ?>
    </section>

    <!-- <script src="./js/jquery-3.3.1.slim.min.js"></script> -->
    <script src="./js/popper.min.js"></script>
    <!-- <script src="./js/bootstrap.min.js"></script> -->
    <script src="./js/cart.js"></script>
    <script src="./js/mdb.umd.min.js"></script>
    <script src="./js/mdb.min.js"></script>



</body>

</html>