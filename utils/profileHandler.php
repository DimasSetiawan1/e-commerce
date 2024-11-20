<?php
session_start();
include_once '../config.php';
include_once 'profileController.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = secure($_POST['action']) ?? '';

    switch ($action) {
        case 'add':
            $result = addAddress($userId, [
                'name' => secure($_POST['name']),
                'no_phone' => secure("0" . $_POST['nomor_telepon']),
                'label' => secure($_POST['label']),
                'full_address' => secure($_POST['alamat_lengkap']) + secure($_POST['detail_alamat']),
                'city' => secure($_POST['kota']),
                'province' => secure($_POST['provinsi']),
                'postal_code' => secure($_POST['kode_pos'])
            ]);
            echo json_encode(['success' => $result]);
            break;

        case 'update':
            $addressId = secure($_POST['address_id']) ?? 0;
            $result = updateAddress($addressId, [
                'label' => secure($_POST['label']),
                'full_address' => secure($_POST['full_address']),
                'city' => secure($_POST['city']),
                'province' => secure($_POST['province']),
                'postal_code' => secure($_POST['postal_code'])
            ]);
            echo json_encode(['success' => $result]);
            break;

        case 'delete':
            $addressId = secure($_POST['address_id']) ?? 0;
            $result = deleteAddress($addressId);
            echo json_encode(['success' => $result]);
            break;

        case 'set_default':
            $addressId = secure($_POST['address_id']) ?? 0;
            $result = setDefaultAddress($userId, $addressId);
            echo json_encode(['success' => $result]);
            break;
        case 'update_email':
            $result = updateEmail($userId, $_POST['email']);
            echo json_encode(['success' => $result]);

            break;
        case 'update_phone_number':
            $result = updatePhoneNumber($userId, $_POST['phone']);
            echo json_encode(['success' => $result]);
            break;
        case 'name_change':
            $result = updateName($userId, $_POST['name']);
            echo json_encode(['success' => $result]);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $addresses = getAddresses($userId);
    echo json_encode(['success' => true, 'addresses' => $addresses]);
}