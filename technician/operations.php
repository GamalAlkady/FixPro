<?php
require_once '../config.php';
$db = new DB('technicians');

if (isset($_POST['login'])) {
    $data = $_POST;
    unset($data['login']);
    $item = $db->getBy(['email' => $data['email'], 'password' => $data['password']]);

    if ($item === false) {
        $_SESSION['error'] = "الايميل او كلمة المرور غير صحيح";
        $_SESSION['old'] = $data;
        back();
//        header('location: login.php');
    } else {
        $_SESSION['name'] = $item['first_name'] . ' ' . $item['last_name'];
        $_SESSION['id'] = $item['id'];
        $_SESSION['image'] = $item['image'];

//        if ($item['email_verification'] == 0) {
//            $_SESSION['verify_role'] = 'technician';
//            $_SESSION['email'] = $item['email'];
//            redirect('email_verification.php');
//            return;
//        }
        $_SESSION['role'] = 'technician';
        redirect($_SESSION['role'].'/index.php');
    }
} elseif (isset($_POST['register'])) {
    $data = $_POST;
    unset($data['register']);
    $_SESSION['old'] = $data;
    $item = $db->getBy(['email' => $data['email']]);
    if (!empty($item)) {
        $_SESSION['error'] = 'يوجد حساب بهذا الايميل';
        back();
        return;
    }

    $data['image'] = uploadfile('image', TECHNICIAN_PATH);
    if ($data['image'] === null) {
        unset($data['image']);
    }

    unset($data['role']);
    $res = $db->insert($data);
    if ($res == true) {
        unset($_SESSION['old']);
        $item = $db->getBy(['email' => $data['email']]);
        $_SESSION['name'] = $item['first_name'] . ' ' . $item['last_name'];
        $_SESSION['id'] = $item['id'];
//        $_SESSION['verify_role'] = 'technician';
        $_SESSION['email'] = $item['email'];
//        redirect('technician/email_verification.php');
//        return;
        redirect('login.php');
    } else {
        $_SESSION['error'] = $res;
        back();
//        header('location: register.php');
    }
}

//var_dump($_POST);
die();