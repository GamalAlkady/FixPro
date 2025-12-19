<?php
require_once '../config.php';
$db = new DB('admin');

if (isset($_POST['login'])) {
    $data = $_POST;
    unset($data['login']);
    $item = $db->getBy(['email' => $data['email'], 'password' => $data['password']]);

    if (!is_array($item)) {
        $_SESSION['error'] = "الايميل او كلمة المرور غير صحيح";
        $_SESSION['old'] = $data;
        header('location: login.php');
    } else {
        $_SESSION['role'] = 'admin';
        $_SESSION['id'] = $item['id'];
        $_SESSION['name'] = $item['first_name'] . ' ' . $item['last_name'];

        header('location: index.php');
//        redirect('dashboard');
    }

}

//var_dump($_POST);
die();