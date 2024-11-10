<?php
session_start();
include_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("location: ../login.php");
    exit();
}


$limit = 10;
$halaman = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $limit) - $limit : 0;
$previous = $halaman - 1;
$next = $halaman + 1;

$search = false;

if (isset($_GET['search'])) {
    $search = htmlspecialchars($_GET['s']);
    try {
        $query = "SELECT * FROM products_03 WHERE title LIKE '%$search%' ";
        $sql_search = $db->prepare($query);
        $sql_search->execute();
        $data = $sql_search->fetchAll(PDO::FETCH_OBJ);
        $sql_search->rowCount() == 0 ? $data = [] : $data;
        $search = true;

    } catch (Throwable $th) {
        echo "Connection Db Error : " . $th->getMessage();
        throw $th;
    }

}


try {
    $query_limit = "SELECT * FROM products_03 LIMIT $halaman_awal, $limit ";
    $sql = $db->prepare("SELECT * FROM products_03");
    $sql_limit = $db->prepare($query_limit);
    $sql_limit->execute();
    $sql->execute();
    $data_limit = $sql_limit->fetchAll(PDO::FETCH_OBJ);
} catch (Throwable $th) {
    echo "Connection Db Error : " . $th->getMessage();
    throw $th;
}

if (!$search) {
    $jumlah_data = $sql->rowCount();
    $total_halaman = ceil($jumlah_data / $limit);
} else {
    $jumlah_data = $sql_search->rowCount();
    $total_halaman = ceil($jumlah_data / $limit);

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Administrator - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="./vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <div id="wrapper">
        <?php include_once "./inc/sidebar_admin.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">
                <?php include_once "./inc/header_admin.php"; ?>

                <div class="container-fluid">
                    <div class="card">
                        <div class="h5 card-header text-white bg-dark">
                            Product Management
                        </div>
                        <div class="card-body">

                            <form action="product_management.php" method="GET">
                                <div class="row">
                                    <input class="form-control mx-3" type="text" style="width: 30%" name="s"
                                        placeholder="Search Product...">
                                    <button class="btn btn-primary" type="submit" name="search" value="search">
                                        Search
                                    </button>
                                </div>
                            </form>
                            <button class="btn btn-primary my-3" type="button" data-target="#modalAdd"
                                data-toggle="modal">
                                Add Product
                            </button>
                            <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modal"
                                oria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal">Form Edit Product</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="./utils/crud_product.php" method="POST"
                                            enctype="multipart/form-data"
                                            onsubmit="return validateFileExtension(this.fileField)">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="title" class="col-form-label">Title</label>
                                                    <input type="text" name="title" class="form-control" id="title"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="image" class="col-form-label">Image</label>
                                                    <input class="form-control" type="file"
                                                        onchange="return validateFileExtension(this)" name="imageUpload"
                                                        id="image" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="price" class="col-form-label">Price
                                                        (Rp)</label>
                                                    <input type="number" name="price" class="form-control" id="price"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="stock" class="col-form-label">Stock</label>
                                                    <input type="number" name="stock" class="form-control" id="stock"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="category" class="col-form-label">Category</label>
                                                    <select name="category" class="form-control" name="category"
                                                        id="category" required>
                                                        <option value="<?= CATEGORY_KIDS ?>">Kids</option>
                                                        <option value="<?= CATEGORY_WOMAN ?>">Woman</option>
                                                        <option value="<?= CATEGORY_MAN ?>">Man</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="add" class="btn btn-primary">Add</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <?php
                            if (isset($_GET['status'])) {
                                switch ($_GET['status']) {
                                    case 'addsuccess':
                                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert" id="autoDismissAlert">
                                            <strong>Success!</strong>
                                            <p>Successfully Add Product.</p> 
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>';
                                        break;
                                    case 'notfound':
                                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" id="autoDismissAlert">
                                            <strong>Error!</strong>
                                            <p>Product Not Found.</p> 
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>';
                                        break;
                                    case 'updatesuccess':
                                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert" id="autoDismissAlert">
                                            <strong>Success!</strong>
                                            <p>Successfully Updated Product.</p> 
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>';
                                        break;
                                    case 'formaterror':
                                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" id="autoDismissAlert">
                                            <strong>Error!</strong>
                                            <p>File Format Not Supported. Only Image File Supported.</p> 
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>';
                                        break;
                                    case 'fileexists':
                                        echo '<div class="alert alert-info alert-dismissible fade show" role="alert" id="autoDismissAlert">
                                            <strong>Info!</strong>
                                            <p>File already exists</p> 
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>';
                                        break;
                                    case 'largesize':
                                        echo '<div class="alert alert-info alert-dismissible fade show" role="alert" id="autoDismissAlert">
                                            <strong>Info!</strong>
                                            <p>Image must not be larger than 2MB</p> 
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>';
                                        break;
                                    default:
                                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" id="autoDismissAlert">
                                            <strong>Error!</strong>
                                            <p>File Failed Upload Image File.</p> 
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>';
                                        break;
                                }
                            }
                            ?>
                            <div class="table-responsive">
                                <table class="table  my-3">
                                    <thead class="thead-dark table-bordered">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Stock</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $search ? $data_limit = $data : $data_limit;
                                        if (count($data_limit) == 0) {
                                            echo "<tr><td colspan='7' class='text-dark' style='text-align: center;'>Data not found</td></tr>";
                                        } else {
                                            $halaman_awal = ($halaman - 1) * $limit;
                                        }
                                        foreach ($data_limit as $i => $product) {

                                            $image_link = "../img/products/" . $product->img;
                                            ?>
                                            <tr>
                                                <th scope="row"><?php echo $halaman_awal + $i + 1 ?></th>
                                                <td><?php echo $product->title ?></td>
                                                <td style="text-align: center;"><img class="img-thumbnail"
                                                        src="<?php echo $image_link ?>" alt="" width="150px" height="150px">
                                                </td>
                                                <td style="text-align: center;"><?= $product->price ?></td>
                                                <td style="text-align: center;">
                                                    <?= $product->stock == 0 ? "Out of stock" : $product->stock; ?>
                                                </td>
                                                <td style="text-align: center;"><?= $product->category ?></td>
                                                <td>
                                                    <button type="submit" class="btn btn-warning" name="edit"
                                                        data-toggle="modal"
                                                        data-target="#modalEdit<?= $halaman_awal + $i + 1 ?>">
                                                        Edit
                                                    </button>
                                                    <form action="./utils/crud_product.php?id=<?= $product->id ?>"
                                                        method="GET">
                                                        <button type="submit" class="btn btn-danger" name="delete"
                                                            data-toggle="modal">
                                                            Delete
                                                        </button>
                                                    </form>
                                                    <!-- Start Modal -->
                                                    <div class="modal fade" id="modalEdit<?= $halaman_awal + $i + 1 ?>"
                                                        tabindex="-1" aria-labelledby="modal" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modal">Form Edit Product
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form
                                                                    action="./utils/crud_product.php?id=<?= $product->id ?>"
                                                                    method="POST" enctype="multipart/form-data"
                                                                    onsubmit="return validateFileExtension(this.fileField)">
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label for="title"
                                                                                class="col-form-label">Title</label>
                                                                            <input type="text" name="title"
                                                                                class="form-control" id="title"
                                                                                value="<?= $product->title ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="image"
                                                                                class="col-form-label">Image</label>
                                                                            <div class="column " id="image">
                                                                                <img class="img-thumbnail mb-3"
                                                                                    src="../img/products/<?= $product->img ?>"
                                                                                    alt="" width="150px" height="150px">
                                                                                <input class="form-control" type="file"
                                                                                    name="imageUpload"
                                                                                    onchange="return validateFileExtension(this)"
                                                                                    id="image" accept="image/*">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="price" class="col-form-label">Price
                                                                                (Rp)</label>
                                                                            <input type="number" name="price"
                                                                                class="form-control" id="price"
                                                                                value="<?= $product->price ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="stock"
                                                                                class="col-form-label">Stock</label>
                                                                            <input type="number" name="stock"
                                                                                class="form-control" id="stock"
                                                                                value="<?= $product->stock ?>">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="category"
                                                                                class="col-form-label">Stock</label>
                                                                            <select name="category" class="form-control"
                                                                                name="category" id="category" required>
                                                                                <option value="<?= CATEGORY_KIDS ?>">Kids
                                                                                </option>
                                                                                <option value="<?= CATEGORY_WOMAN ?>">Woman
                                                                                </option>
                                                                                <option value="<?= CATEGORY_MAN ?>">Man
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" name="update"
                                                                            class="btn btn-primary">Update</button>
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End modal -->
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <nav>
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item">
                                            <a class="page-link" <?php if ($halaman > 1) {
                                                echo "href='?halaman=$previous'";
                                            } ?>>Previous</a>
                                        </li>
                                        <?php
                                        for ($x = 1; $x <= $total_halaman; $x++) {
                                            if ($halaman == $x) {
                                                echo "<li class='page-item active'><a class='page-link' href='?halaman=$x'>$x</a></li>";
                                            } else {
                                                echo "<li class='page-item'><a class='page-link' href='?halaman=$x'>$x</a></li>";
                                            }
                                        }
                                        ?>
                                        <li class="page-item">
                                            <a class="page-link" <?php if ($halaman < $total_halaman) {
                                                echo "href='?halaman=$next'";
                                            } ?>>Next</a>
            </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto dismis alert -->
    <script>
        setTimeout(function () {
            var alertElement = document.getElementById("autoDismissAlert");
            if (alertElement) {
                alertElement.classList.remove("show");
                alertElement.classList.add("fade");
                setTimeout(function () {
                    alertElement.remove();
                }, 500);
            }
        }, 3000);
    </script>

    <script type="text/javascript">
        function validateFileExtension(fld) {
            if (!/(\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value)) {
                alert("File Format Not Supported. Only Image File Supported.");
                fld.value = "";
                fld.focus();
                return false;
            }
            return true;
        }
    </script>
    <!-- Bootstrap core JavaScript-->
    <script src="./vendor/jquery/jquery.min.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="./vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="./vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="./js/demo/chart-area-demo.js"></script>
    <script src="./js/demo/chart-pie-demo.js"></script>
</body>