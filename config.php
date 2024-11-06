<?php

// DB credentials.
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_kl03');



$formatter = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
$formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
// Categories
define('CATEGORY_WOMAN', 'WOMAN');
define('CATEGORY_MAN', 'MAN');
define('CATEGORY_KIDS', 'KIDS');

// Security function
function secure($data)
{
    $data = trim($data);
    $data = preg_replace('#\'#', '&apos;', $data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES);
    return $data;
}

// Connect to database
try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}

