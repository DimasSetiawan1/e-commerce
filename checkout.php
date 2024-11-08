<?php
session_start();
error_reporting(E_ALL);
include('config.php');
if (isset($_SESSION['user_id'])) {
    try {
        $user = $_SESSION['user_id'];
        $sql = "SELECT cart_03.id,products_03.title,products_03.price,products_03.img FROM cart_03 INNER JOIN products_03 ON products_03.id = cart_03.productid WHERE cart_03.user=:user";
        $query = $db->prepare($sql);
        $query->bindParam(':user', $user, PDO::PARAM_STR);
        $query->execute();
        $itemCount = $query->rowCount();
    } catch (\Throwable $th) {
        echo $th->getMessage();
        exit();
    }

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
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/style.css">
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
            <div class="row">
                <div class="col-md-5">
                
                </div>
                <div class="col-md-4">

                </div>
            </div>
        <?php } ?>

        <?php include('./inc/footer.php'); ?>
    </section>

    <script src="./js/jquery-3.3.1.slim.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</body>

</html>