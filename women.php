<?php
session_start();
error_reporting(E_ALL);
include('config.php');



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
      header("Location: women.php?status=success");

    } catch (\Throwable $th) {
      header("Location: women.php?status=error");

      throw $th;
    }
  } else {
    header("Location: women.php?status=error");

  }
} else {
}
// FECTH PRODUCTS
$sql = "SELECT * from products_03 WHERE category = 'Women'";
$query = $db->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
if (isset($_GET['status'])) {
  $status = $_GET['status'];
  switch ($status) {
    case 'success':
      $_SESSION['msg'] = '<div id="msg" class="alert alert-success"><strong>Product Added To Cart</strong></div>';
      break;
    case 'error':
      $_SESSION['msg'] = '<div id="msg" class="alert alert-danger"><strong>Unable To Add</strong></div>';
      break;
    default:
      break;
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

</head>

<body>

  <section>
    <?php include('./inc/header.php'); ?>

    <div class="container mt-5 my-section">
      <h3 class="py-4">Women</h3>
      <div class="msg"><?php if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
      } ?>
      </div>
      <div class="row">

        <?php

        if ($query->rowCount() > 0) {
          foreach ($results as $result) { ?>
            <div class="col-lg-3 col-md-6 mb-4">
              <div class="card h-100 border-0 shadow-lg">
                <a href="#"><img class="card-img-top" src="./img/products/<?= $result->img; ?>" alt="<?= $result->title; ?>"
                    alt="<?= $result->title; ?>" title="<?= $result->title; ?>"></a>
                <div class="card-body">
                  <h4 class="card-title">
                    <a href="#"><?= $result->title; ?></a>
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
  <script src="./js/mdb.min.js"></script>
  <script src="./js/mdb.umd.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      setTimeout(function () {
        $('#msg').slideUp("slow");
      }, 2000);
    });
  </script>
</body>

</html>