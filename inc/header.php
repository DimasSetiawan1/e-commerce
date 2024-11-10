<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-4">
    <div class="container-fluid bg-dark">
        <a class="navbar-brand mr-5 font-weight-bold" href="index.php">TrendZ</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav ">
                <li class="nav-item">
                    <a class="nav-link font-weight-bold <?= $path == 'index.php' ? 'active' : '' ?>"
                        href="index.php">Home</a>
                </li>
                <li class="nav-item ml-2">
                    <a class="nav-link font-weight-bold  <?= $path == 'mens.php' ? 'active' : '' ?>"
                        href="mens.php">Mens</a>
                </li>
                <li class="nav-item ml-2 ">
                    <a class="nav-link font-weight-bold <?= $path == 'women.php' ? 'active' : '' ?>"
                        href="women.php">Womens</a>
                </li>
                <li class="nav-item ml-2 ">
                    <a class="nav-link font-weight-bold <?= $path == 'kids.php' ? 'active' : '' ?>"
                        href="kids.php">Kids</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <?php if (strlen(isset($_SESSION['user_id']) != 0)) { ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="mycart.php"> <i class="fa fa-shopping-cart"></i> My Cart
                            <?= isset($itemCount) ? "( $itemCount )" : "" ?> </a>
                    </li>
                <?php } ?>

                <?php if (strlen(isset($_SESSION['user_id']) == 0)) { ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="login.php">Login / Register</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="#"><i class="fa fa-user"></i> Hi,
                            <?= $_SESSION['username'] ?></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="logout.php"><b>Logout</b></a>
                    </li>
                <?php } ?>
            </ul>

        </div>
    </div>
</nav>