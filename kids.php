<?php
session_start();
error_reporting(E_ALL);
include('config.php');

$msg = '';
$path = basename(__FILE__);
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
if (isset($_GET['add'])) {
  if (isset($_SESSION['user_id'])) {
    $productid = secure($_GET['add']);
    $user = $_SESSION['user_id'];
    try {
      $sql = "INSERT INTO cart_03 (productid, user, quantity)
        VALUES (:productid, :user, 1)
        ON DUPLICATE KEY UPDATE quantity = quantity + 1";
      $query = $db->prepare($sql);
      $query->bindParam(':productid', $productid, PDO::PARAM_STR);
      $query->bindParam(':user', $user, PDO::PARAM_STR);
      $query->execute();
      $msg = '<div id="msg" class="alert alert-success"><strong>Product Added To Cart</strong></div>';
    } catch (\Throwable $th) {
      $msg = '<div id="msg" class="alert alert-danger"><strong>Unable To Add</strong></div>';
      throw $th;
    }
  } else {
    $msg = '<div id="msg" class="alert alert-danger"><strong>Please Login</strong></div>';
  }
} else {
}
// FECTH PRODUCTS
$sql = "SELECT * from products_03 WHERE category = 'Kids'";
$query = $db->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

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

    <div class="container mt-5 my-section">
      <h3 class="py-4">Kids</h3>
      <div class="msg"><?php echo $msg; ?></div>
      <div class="row">

        <?php

        if ($query->rowCount() > 0) {
          foreach ($results as $result) { ?>
            <div class="col-lg-3 col-md-6 mb-4">
              <div class="card h-100 border-0 shadow-lg">
                <a href="#"><img class="card-img-top" src="./img/products/<?php echo $result->img; ?>"
                    alt="<?php echo $result->title; ?>" title="<?php echo $result->title; ?>"></a>
                <div class="card-body">
                  <h4 class="card-title">
                    <a href="#"><?php echo $result->title; ?></a>
                  </h4>
                  <h5>
                    <?= $result->stock > 0 ? $formatter->formatCurrency($result->price, "IDR") : 'Out Of Stock'; ?>
                  </h5>
                  <?php
                  if ($result->stock > 0) {
                    echo '<a href="index.php?add=' . $result->id . '" class="btn btn-dark mt-2">Add To Cart</a>';
                  } else {
                    echo '<a href="index.php?add=' . $result->id . '" class="btn btn-dark mt-2 disabled"><del>Add To Cart</del></a>';
                  }
                  ?>
                </div>
              </div>
            </div>
          <?php }
        } ?>
      </div>
    </div>

    <?php include('./inc/footer.php'); ?>
  </section>

  <script src="./js/jquery-3.3.1.js"></script>
  <script src="./js/popper.min.js"></script>
  <script src="./js/bootstrap.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      setTimeout(function () {
        $('#msg').slideUp("slow");
      }, 2000);
    });
  </script>
</body>

</html>