<!-- Navbar -->
<nav class="navbar navbar-expand-sm navbar-expand-lg navbar-dark bg-dark ">
    <!-- Container wrapper -->
    <div class="container-fluid px-3">
        <!-- Navbar brand -->
        <a class="navbar-brand me-2 font-weight-bold  my-3 " href="index.php">
            <!-- <img src="https://mdbcdn.b-cdn.net/img/logo/mdb-transaprent-noshadows.webp" height="16" alt="MDB Logo"
                loading="lazy" style="margin-top: -1px;" /> -->
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
            <ul class="navbar-nav me-auto mx-auto justify-content-center w-sm-50 w-md-75 my-3  ">
                <form class="d-flex input-group w-sm-50 w-md-50 " action="index.php" method="POST">
                    <input type="search" name="keyword" class="form-control rounded bg-dark text-white  "
                        placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                    <button class="input-group-text border-0" id="search-addon">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </ul>


            <!-- Left links -->

            <?php if (strlen(isset($_SESSION['user_id']) == 0)) { ?>
                <a href="login.php">
                    <button data-mdb-ripple-init type="button" class="btn btn-link px-3 my-3 me-2">
                        Login
                    </button>
                </a>
                <a class="text-white-50" href="register.php">
                    <button data-mdb-ripple-init type="button" class="btn  my-3  btn-primary me-3">
                        Sign up
                    </button>
                </a>
            <?php } else { ?>

                <ul class="navbar-nav">
                    <ul class="navbar-nav">
                        <!-- Badge -->
                        <li class="nav-item">
                            <a class="nav-link" href="mycart.php">
                                <span class="badge badge-pill bg-danger">1</span>
                                <span><i class="fas fa-shopping-cart"></i></span>
                            </a>
                        </li>
                    </ul>
                    <!-- Avatar -->
                    <i class="nav-item dropdown">
                        <!-- <a data-mdb-dropdown-init class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                        id="navbarDropdownMenuLink" role="button" aria-expanded="false">
                        <img src="#" class="rounded-circle fa fa-users" height="22" alt="Portrait of a Woman"
                        loading="lazy" />
                    </a> -->
                        <a data-mdb-dropdown-init class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                            id="navbarDropdownMenuLink" role="button" aria-expanded="false">
                            <img src="./img/user.svg" class="rounded-circle" style="margin-right: 10px;" height="25" alt=""
                                loading="lazy" />
                            <span style="font-style: normal;">Hi, <?= $_SESSION['username'] ?></span>
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item" href="#">My profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Settings</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </li>
                        </ul>
                        </li>
                </ul>
            <?php } ?>
        </div>
        <!-- Collapsible wrapper -->
    </div>
    <!-- Container wrapper -->
</nav>
<!-- Navbar -->