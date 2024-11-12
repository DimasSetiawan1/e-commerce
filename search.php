<?php
session_start();
error_reporting(E_ALL);
include('config.php');


if (isset($_POST['keyword'])) {
    $keyword = secure($_POST['keyword']);
    $query = "SELECT * FROM products_03 WHERE title LIKE :keyword";
    $sql = $db->prepare($query);
    $sql->bindParam(":keyword", $keyword, PDO::PARAM_STR);
    $sql->execute();
    $results = $sql->fetchAll(PDO::FETCH_OBJ);

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search <?= "title" ?></title>
    <link rel="stylesheet" href="./css/mdb.min.css  ">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>

    <?php include("header.php"); ?>

    <div class="container">
        <div class="row">
            <div class="col">
                <h3 class="my-3">Search Result</h3>
                <div class="row">
                    <?php
                    if ($sql->rowCount() > 0) {
                        foreach ($results as $result) { ?>
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-lg">
                                    <a href="#"><img class="card-img-top" src="./img/products/<?= $result->img; ?>"
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
                    } else {
                        echo '<div class="alert alert-danger">No Result Found</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="./js/jquery-3.3.1.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/mdb.min.js"></script>
    <script src="./js/mdb.umd.min.js"></script>

</body>

</html>