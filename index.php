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
    $_SESSION['itemCount'] = $itemCount;
  } catch (\Throwable $th) {
    echo $th->getMessage();
    exit();
  }

}

if (isset($_POST['keyword'])) {
  $keyword = secure($_POST['keyword']);
  $query = "SELECT * FROM products_03 WHERE title LIKE :keyword";
  $keyword = "%$keyword%";
  $search = $db->prepare($query);
  $search->bindParam(":keyword", $keyword, PDO::PARAM_STR);
  $search->execute();
  $search_results = $search->fetchAll(PDO::FETCH_OBJ);


}


$msg = '';
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
    echo "<script type='text/javascript'> document.location = 'login.php'; </script>";
  }
}


if (isset($_GET['category'])) {
  $category = secure($_GET['category']);
  $query = "SELECT * FROM products_03 WHERE category=:category";
  $search = $db->prepare($query);
  $search->bindParam(":category", $category, PDO::PARAM_STR);
  $search->execute();
  $search_results = $search->fetchAll(PDO::FETCH_OBJ);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TrendZ | Online Store for Latest Trends</title>
  <link rel="stylesheet" href="./css/mdb.min.css">
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <script>
    if (typeof window.history.pushState == 'function') {
      window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF']; ?>');
    }
  </script>
</head>

<body>
  <?php include('./inc/header.php'); ?>
  <?php include('./inc/carousel.php'); ?>

  <section class="container-fluid px-4 pt-5 h-100 h-custom">

    <!-- <section class="container-fluid px-4 pt-5"> -->
    <div class="row d-flex ">
      <div class="col-sm-4 col-md-2 mb-4 ">
        <div class="card">
          <!-- card title -->
          <div class="card-header">
            <h3 class="card-title h4">Filter</h3>
          </div>
          <!-- card body -->
          <div class="card-body">
            <form action="index.php" id="categoryForm" method="GET">
              <div id="select-wrapper" class="select-wrapper">
                <label for="category">Category</label>
                <select class="select initialized" onchange="setCategory()" id="category" name="category">
                  <button type="submit">
                    <option value=""><?= $category ?? "All" ?></option>
                    <?php
                    $sql = "SELECT * FROM categories_03";
                    $query = $db->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    foreach ($results as $result) { ?>
                      <option value="<?php echo $result->title; ?>"><?php echo $result->title; ?></option>
                    <?php } ?>
                  </button>
                </select>
            </form>
          </div>

        </div>
      </div>
    </div>
    <div class="col-sm-8 col-md-10 mb-4">
      <?php
      if (isset($search) && $search->rowCount() > 0) { ?>
        <div class="container mt-2 my-section">
          <h3 class="text-bold ">Search Result</h3>
          <div class="row">
            <?php
            foreach ($search_results as $result) { ?>
              <div class="col-lg-3 col-md-6 mb-4">
                <div class="msg"><?= $msg; ?></div>

                <div class="card h-100 border-0 shadow-lg">
                  <a href="#">
                    <div class="bg-image hover-overlay hover-zoom ripple rounded">
                      <img class="card-img-top " src="./img/products/<?php echo $result->img; ?>"
                        alt="<?php echo $result->title; ?>" title="<?php echo $result->title; ?>">
                    </div>
                  </a>
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
            ?>
          </div>

        <?php } else if (isset($search) && $search->rowCount() == 0) { ?>
            <div class="container mt-2 my-section">
              <h3 class="text-bold ">No Result Found</h3>
            </div>
        <?php } else { ?>
            <div class="container mt-2 my-section">
              <h3 class="text-bold ">Popular Products</h3>
              <div class="msg"><?= $msg; ?></div>
              <div class="row">
                <?php

                // FECTH PRODUCTS
                $sql = "SELECT * from products_03 ORDER BY RAND() LIMIT 4";
                $query = $db->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                if ($query->rowCount() > 0) {
                  foreach ($results as $result) { ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                      <div class="card h-100 border-0 shadow-lg">
                        <a href="#">
                          <div class="bg-image hover-overlay hover-zoom ripple rounded">
                            <img class="card-img-top " src="./img/products/<?php echo $result->img; ?>"
                              alt="<?php echo $result->title; ?>" title="<?php echo $result->title; ?>">
                          </div>
                        </a>
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
            <div class="container mt-5 my-section">
              <h3 class="py-4">Mens</h3>
              <div class="row">

                <?php

                // FECTH PRODUCTS
                $sql = "SELECT * from products_03 WHERE category='Mens' ORDER BY RAND() LIMIT 4";
                $query = $db->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                if ($query->rowCount() > 0) {
                  foreach ($results as $result) { ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                      <div class="card h-100 border-0 shadow-lg">
                        <a href="#">
                          <div class="bg-image hover-overlay hover-zoom ripple rounded">
                            <img class="card-img-top " src="./img/products/<?php echo $result->img; ?>"
                              alt="<?php echo $result->title; ?>" title="<?php echo $result->title; ?>">
                          </div>
                        </a>
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

            <div class="container mt-5 my-section">
              <div class="row">
                <div class="col-6">
                  <h3 class="py-4 text-start">Womens</h3>
                </div>
                <div class="col-6">

                  <h5 class="py-4 text-end btn-link">More...</h5>
                </div>
              </div>

              <div class="row">

                <?php

                // FECTH PRODUCTS
                $sql = "SELECT * from products_03 WHERE category='Women' ORDER BY RAND() LIMIT 4";
                $query = $db->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                if ($query->rowCount() > 0) {
                  foreach ($results as $result) { ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                      <div class="card h-100 border-0 shadow-lg">
                        <a href="#">
                          <div class="bg-image hover-overlay hover-zoom ripple rounded">
                            <img class="card-img-top" src="./img/products/<?php echo $result->img; ?>"
                              alt="<?php echo $result->title; ?>" title="<?php echo $result->title; ?>">
                          </div>
                        </a>
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
        <?php }
      ?>

      </div>

    </div>
    <?php include('./inc/footer.php'); ?>
  </section>



  <script src="./js/jquery-3.3.1.js"></script>
  <script src="./js/popper.min.js"></script>
  <script src="./js/mdb.min.js"></script>

  <script src="./js/mdb.umd.min.js"></script>

  <script type="text/javascript">

    const setCategory = function () {
      document.getElementById('categoryForm').submit();
    }

    $(document).ready(function () {
      setTimeout(function () {
        $('#msg').slideUp("slow")
      }, 2000);
    });
  </script>
</body>

</html>