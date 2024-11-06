<?php

unset($_SESSION['admin_id']);
unset($_SESSION['admin']);
session_destroy();
header("location:../login.php"); 
