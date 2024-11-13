<?php
session_start();
error_reporting(E_ALL);
include('config.php');




if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}


if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];

    // TOTAL
    $sql = "SELECT SUM(products_03.price) as total FROM cart_03 INNER JOIN products_03 ON products_03.id = cart_03.productid WHERE cart_03.user=:user";
    $query = $db->prepare($sql);
    $query->bindParam(':user', $user, PDO::PARAM_STR);
    $query->execute();
    $total = $query->fetch(PDO::FETCH_OBJ);

    // FECTH PRODUCTS
    $sql = "SELECT cart_03.id,products_03.title,products_03.price,products_03.img FROM cart_03 INNER JOIN products_03 ON products_03.id = cart_03.productid WHERE cart_03.user=:user";
    $query = $db->prepare($sql);
    $query->bindParam(':user', $user, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);


    //INSERT ORDER
    if (isset($_POST['orderplace'])) {
        $address = $_POST['address'];
        $sql = "INSERT INTO orders_03(user, address) VALUES(:user,:address)";
        $query = $db->prepare($sql);
        $query->bindParam(':user', $user, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $db->lastInsertId();
        if ($lastInsertId) {
            foreach ($results as $item) {
                $sqlitem = "INSERT INTO orderitems_03 (oid,ptitle,price) VALUES (:orderid,:title,:price)";
                $stmtitem = $db->prepare($sqlitem);
                $stmtitem->bindParam("orderid", $lastInsertId, PDO::PARAM_STR);
                $stmtitem->bindParam("title", $item->title, PDO::PARAM_STR);
                $stmtitem->bindParam("price", $item->price, PDO::PARAM_INT);
                $stmtitem->execute();
            }

            //CLEAR CART
            $sql = "DELETE FROM cart_03 WHERE user = (:user)";
            $query = $db->prepare($sql);
            $query->bindParam(':user', $user, PDO::PARAM_STR);
            $query->execute();

            echo "<script>alert('Order Placed')</script>";
            echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
        } else {
            echo "<script>alert('Please Fill All Valid Details')</script>";
        }
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <script>
        if (typeof window.history.pushState == 'function') {
            window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF']; ?>');
        }
    </script>
</head>

<body>
    <section>
        <?php include('./inc/header.php'); ?>

        <?php if (strlen(isset($_SESSION['user_id']) == 0)) {
            header("Location: login.php");
            ?>
        <?php } else { ?>
            <div class="row mx-auto  p-5">
                <div class="col-md-6">
                    <span class="h3 fw-bold "> Checkout </span>
                    <div class="card cart-item mb-3 mt-3 shadow-lg p-3" style="border-radius: 20px;">
                        <div class="card-body">
                            <div class="row mb-3 justify-content-around">
                                <div class="col-md-6">
                                    <label for="nama_depan" class="form-label">Nama Depan</label>
                                    <input type="text" class="form-control" name="nama_depan" id=""
                                        placeholder="Nama Depan">
                                </div>
                                <div class="col-md-6">
                                    <label for="nama_belakang" class="form-label">Nama Belakang</label>
                                    <input type="text" class="form-control" name="nama_belakang" id=""
                                        placeholder="Nama Belakang">
                                </div>
                            </div>
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control mb-3" name="alamat" id="" placeholder="Alamat"></textarea>
                            <div class="mb-3">
                                <label for="nomor" class="form-label">Nomor Telpon</label>
                                <input type="text" class="form-control" name="nomor" id="" placeholder="Nomor">
                            </div>
                            <div class="mb-3">
                                <label for="Negara" class="form-label">Negara</label>
                                <input type="text" class="form-control" name="negara" value="indonesia" readonly>
                            </div>
                            <div class="row ">
                                <div class="col-md-6 mb-3">
                                    <label for="provinsi" class="form-label">Provinsi</label>
                                    <select class="form-control province" name="provinsi" placeholder="Provinsi">
                                        <option value="">Pilih</option>
                                    </select>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label for="kota" class="form-label">Kota</label>
                                    <select class="form-control kota" name="kota" placeholder="Kota">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                    <select class="form-control kecamatan" name="kecamatan" placeholder="kecamatan">
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label for="kelurahan" class="form-label">Kelurahan</label>
                                    <select class="form-control kelurahan" name="kelurahan" placeholder="kelurahan">
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-5 mt-5">
                    <div class="card shadow-lg p-3" style="border-radius: 20px;">
                        <div class="card-body">
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
                </div>
            <?php } ?>

            <?php include('./inc/footer.php'); ?>
    </section>

    <script src="./js/jquery-3.3.1.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/mdb.min.js"></script>
    <script src="./js/mdb.umd.min.js"></script>
    <script src="./js/getRegion.js"></script>
</body>

</html>