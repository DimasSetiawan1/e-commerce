<div class="col-sm-4 col-md-4 mt-5 ">
    <div class="card">

        <div class="card-body mx-auto text-center">
            <img src="./img/user.png" alt="test" class="rounded-circle" height="100px" width="100px" loading="lazy" />

            <h5 class="mt-3 fw-bold">
                <?= $_SESSION['username']; ?>
            </h5>
        </div>

        <div class="card-body">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button data-mdb-collapse-init class="accordion-button py-3" type="button"
                            data-mdb-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Akun Saya
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                        data-mdb-parent="#accordionExample">
                        <div class="accordion-body active-bar" mdb-data-button-init>
                            <a href="profile.php">
                                <button data-mdb-ripple-init type="button"
                                    class="btn mb-2 btn-link w-100 text-start <?= ($current_page == 'profile.php') ? 'text-primary fw-bold' : 'text-black'; ?>">
                                    Profile
                                </button>
                            </a>
                            <a href="address.php">
                                <button data-mdb-ripple-init type="button"
                                    class="btn mb-2 btn-link w-100 text-start <?= ($current_page == 'address.php') ? 'text-primary fw-bold' : 'text-black'; ?>">
                                    Alamat
                                </button>
                            </a>
                            <a href="change_password.php">
                                <button data-mdb-ripple-init type="button"
                                    class="btn mb-2 btn-link w-100 text-start <?= ($current_page == 'change_password.php') ? 'text-primary fw-bold' : 'text-black'; ?>">
                                    Ubah Password
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-2 border border-1 border-black rounded-2 shadow-none">
                <a href="orders.php">
                    <button type="button"
                        class="btn <?= ($current_page == 'orders.php') ? 'text-primary fw-bold' : 'text-black'; ?> w-100">
                        Pesanan Saya
                    </button>
                </a>
            </div>
        </div>

    </div>
</div>