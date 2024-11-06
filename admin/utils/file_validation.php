<?php
function image_validation($image)
{
    $image_name = $image['imageUpload']['name'];
    $image_size = $image['imageUpload']['size'];
    $image_temp = $image['imageUpload']['tmp_name'];
    $image_type = $image['imageUpload']['type'];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $file_type = $finfo->file($image_temp);
    $allowed_image_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];


    if (!in_array($file_type, $allowed_image_types)) {
        // var_dump($file_type);
        // return "gagal";
        header("Location: ../../admin/product_management.php?status=formaterror");
        exit();
    }

    $upload_max_size = 2 * 1024 * 1024; // 2MB
    if ($image_size > $upload_max_size) {
        header("Location: ../../admin/product_management.php?status=largesize");
        exit();
    }

    $str = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz";
    $length = 10;
    $shuffled_str = str_shuffle($str);
    $new_name = substr($shuffled_str, 0, $length);

    $extension = pathinfo($image_name, PATHINFO_EXTENSION);
    $allowed_image_extension = ["jpg", "png", "gif", "jpeg", "webp"];

    if (!in_array($extension, $allowed_image_extension)) {
        header("Location: ../../admin/product_management.php?status=formaterror");
        exit();
    }
    $image_name = $new_name . "." . $extension;


    $images = glob("../../img/products/*.*");
    if (in_array("../../img/products/" . $image_name, $images)) {
        header("Location: ../../admin/product_management.php?status=fileexists");
        exit();
    }

    $move_file = move_uploaded_file($image_temp, "../../img/products/" . $image_name);
    if ($move_file == false) {
        header("Location: ../../admin/product_management.php?status=");
        exit();
    }
    return $image_name;
}
