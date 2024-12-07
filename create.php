<?php
session_start();
// include '../config.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/mdb.min.css  ">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>

<body>
    <div class="container ">
        <div class="row mx-auto">
            <div class=" col-md-4 col-sm-4 ">
                <div class="card-dark text-center d-flex  flex-column shadow-sm">
                    <div class="check-icon mb-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5 class="fw-bold">Payment Success!</h5>
                    <p>Your payment has been successfully done.</p>
                    <h3 class="fw-bold">Total Payment</h3>
                    <h3 class="fw-bold">IDR 1,000,000</h3>
                    <div class="text-start mt-4">
                        <p class="mb-2"><strong>Ref Number</strong> <span class="float-end">000085752257</span></p>
                        <p class="mb-2"><strong>Payment Time</strong> <span class="float-end">25 Feb 2023,
                                13:22</span></p>
                        <p class="mb-2"><strong>Payment Method</strong> <span class="float-end">Bank Transfer</span>
                        </p>
                        <p class="mb-2"><strong>Sender Name</strong> <span class="float-end">Antonio Roberto</span>
                        </p>
                    </div>
                    <div class="mt-4">
                        <a href="#" class="btn-link cetak-invoice"><i class="fas fa-file-pdf"></i> Get PDF Receipt</a>
                    </div>
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