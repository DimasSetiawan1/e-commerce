<?php
session_start();
include_once './inc/config.inc.php';

unset($_SESSION['username']);
unset($_SESSION['user_id']);
unset($_SESSION['cart']);
session_destroy();
header("location:index.php");

