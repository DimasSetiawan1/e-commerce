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

            <section class="h-100 h-custom">
                <div class="container py-5 h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-12">
                            <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                                <div class="card-body p-0">
                                    <div class="row g-0">
                                        <div class="col-lg-8">
                                            <div class="p-5">
                                                <div class="d-flex justify-content-between align-items-center mb-5">
                                                    <h1 class="fw-bold mb-0">Shopping Cart</h1>
                                                    <h6 class="mb-0 text-muted"><?= isset($itemCount) ? "$itemCount" : "" ?>
                                                        items</h6>
                                                </div>
                                                <hr class="my-4">
                                                <?php if (isset($results)) {
                                                    foreach ($results as $i => $result) {
                                                        $qty = $_SESSION['cart'][$_SESSION['user_id']][$result->id]['quantity'] ?? $result->quantity;
                                                        $total = $_SESSION['cart'][$_SESSION['user_id']][$result->id]['total'] ?? $result->price * $qty;
                                                        ?>
                                                        <div
                                                            class="row mb-4 d-flex justify-content-between align-items-center cart-item ">
                                                            <div class="col-md-2 col-lg-2 col-xl-2">
                                                                <img src="./img/products/<?= $result->img ?>"
                                                                    class="img-fluid rounded-3" alt="<?= $result->title ?>">
                                                            </div>
                                                            <div class="col-md-3 col-lg-3 col-xl-3">
                                                                <h6 class="text-muted">Shirt</h6>
                                                                <h6 class="mb-0"><?= $result->title ?></h6>
                                                            </div>
                                                            <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                                                <button data-mdb-button-init data-mdb-ripple-init
                                                                    class="btn btn-link px-2 minus" data-id="<?= $result->id ?>"
                                                                    onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>

                                                                <input id="quantity-<?= $result->id ?>" min="0" value="<?= $qty ?>"
                                                                    type="number" class="form-control form-control-sm quantity" />

                                                                <button data-mdb-button-init data-mdb-ripple-init
                                                                    class="btn btn-link px-2 add" data-id="<?= $result->id ?>"
                                                                    onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                            </div>
                                                            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                                <h6 class="mb-0 total-<?= $result->id ?>"
                                                                    data-price="<?= $result->price ?>">
                                                                    <?= $formatter->formatCurrency($total, "IDR") ?>
                                                                </h6>
                                                            </div>
                                                            <div class="col-md-1 col-lg-1 col-xl-1 text-end ">
                                                                <a href="#!" class="text-muted"><i class="fas fa-times "
                                                                        onclick="removeCart(<?= $result->id ?>)"></i></a>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>

                                                <hr class="my-4">

                                                <div class="pt-5">
                                                    <h6 class="mb-0"><a href="#!" class="text-body"><i
                                                                class="fas fa-long-arrow-alt-left me-2"></i>Back to shop</a>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 bg-body-tertiary">
                                            <div class="p-5">
                                                <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                                                <hr class="my-4">

                                                <div class="d-flex justify-content-between mb-4">
                                                    <h5 class="text-uppercase">items
                                                        <?= isset($itemCount) ? "$itemCount" : "" ?>
                                                    </h5>
                                                    <h5>subtotal</h5>
                                                </div>

                                                <h5 class="text-uppercase mb-3">Shipping</h5>

                                                <div class="mb-4 pb-2">
                                                    <select data-mdb-select-init>
                                                        <option value="1">Standard-Delivery- €5.00</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
                                                        <option value="4">Four</option>
                                                    </select>
                                                </div>

                                                <h5 class="text-uppercase mb-3">Give code</h5>

                                                <div class="mb-2">
                                                    <div data-mdb-input-init class="form-outline">
                                                        <input type="text" id="addDiscount"
                                                            class="form-control form-control-lg" />
                                                        <label class="form-label" for="form3Examplea2">Enter
                                                            your code</label>
                                                    </div>
                                                </div>
                                                <div id="alert" class="alert d-none">
                                                    <strong class="text-notification"></strong>
                                                </div>

                                                <hr class="my-4">

                                                <div class="d-flex justify-content-between mb-5">
                                                    <h5 class="text-uppercase">Total price</h5>
                                                    <h5>€ 137.00</h5>
                                                </div>

                                                <button type="button" data-mdb-button-init data-mdb-ripple-init
                                                    class="btn btn-dark btn-block btn-lg"
                                                    data-mdb-ripple-color="dark">Register</button>

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

    <script src="./js/jquery-3.3.1.slim.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/cart.js"></script>
    <script type="module" src="./js/module.js"></script>



</body>

</html>