<!-- Navbar -->
<nav class="navbar navbar-expand-sm navbar-expand-lg navbar-dark bg-dark ">
    <!-- Container wrapper -->
    <div class="container-fluid px-3">
        <!-- Navbar brand -->
        <a class="navbar-brand fw-bold " href="index.php">
            TrendZ
        </a>

        <!-- Toggle button -->
        <button data-mdb-collapse-init class="navbar-toggler" type="button" data-mdb-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left links -->
            <ul class="navbar-nav me-auto mx-auto justify-content-center my-3  ">
                <form class="d-flex input-group form-search" action="index.php" method="POST">
                    <input type="search" name="keyword" class="form-control rounded bg-dark text-white "
                        placeholder="Search..." aria-label="Search" aria-describedby="search-addon" />
                    <button class="input-group-text border-0" id="search-addon">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </ul>

            <!-- Left links -->

            <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php">
                    <button data-mdb-ripple-init type="button" class="btn mx-5 btn-link px-3 my-3 me-2">
                        Login
                    </button>
                </a>
                <a class="text-white-50 " href="register.php">
                    <button data-mdb-ripple-init type="button" class="btn mx-5 my-3  btn-primary me-3">
                        Sign up
                    </button>
                </a>
            <?php } else { ?>
                <ul class="navbar-nav">
                    <div class="container mx-3 me-3">
                        <div class="row">
                            <div class="col-4 col-sm-4 text-start ">
                                <ul class="navbar-nav">
                                    <!-- Badge -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="mycart.php">
                                            <span
                                                class="badge badge-pill bg-danger"><?= $_SESSION['itemCount'] ?? 0 ?></span>
                                            <span><i class="fas fa-shopping-cart"></i></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6 col-sm-6 text-end ">
                                <!-- Avatar -->
                                <li class="nav-item dropdown">
                                    <a data-mdb-dropdown-init class="nav-link dropdown-toggle d-flex align-items-center"
                                        href="#" id="navbarDropdownMenuLink" role="button" aria-expanded="false">
                                        <img src="./img/user.svg" class="rounded-circle" style="margin-right: 10px;"
                                            height="25" alt="" loading="lazy" />
                                        <span style="font-style: normal;">Hi, <?= $_SESSION['username'] ?></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="navbarDropdownMenuLink">
                                        <li><a class="dropdown-item" href="profile.php">My profile</a></li>
                                        <li><a class="dropdown-item" href="#">Pesanan Saya</a></li>
                                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                    </ul>
                                </li>
                            </div>
                        </div>
                    </div>
                </ul>
            <?php } ?>
        </div>
        <!-- Collapsible wrapper -->
    </div>
    <!-- Container wrapper -->
</nav>
<!-- Navbar -->