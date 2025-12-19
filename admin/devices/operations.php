<?php
require_once '../../config.php';
$db = new DB('devices');

if (isset($_POST['add'])) {
    $data = $_POST;
    unset($data['add']);
    $data['notes']=trim($data['notes']);

    $res = $db->insert($data);
    if ($res === true) {
        $_SESSION['success'] = 'تمت إضافة الجهاز بنجاح';
        header('location: index.php');
    } else {
        $_SESSION['old'] = $data;
        $_SESSION['error'] = $res;
        header('location: add.php');
    }
}
elseif (isset($_POST['edit'])) {
    $data = $_POST;
    unset($data['edit']);
    $data['notes']=trim($data['notes']);

    $res = $db->update($data,$data['id']);
    if ($res == true) {
        $_SESSION['success'] = 'تمت تعديل الجهاز بنجاح';
        header('location: index.php');
    } else {
        $_SESSION['old'] = $data;
        $_SESSION['error'] = $res;
        header('location: edit.php');
    }
}

elseif (isset($_POST['delete'])) {
//    die($_POST['delete']);
    $res = $db->delete($_POST['delete']);
    if ($res == true) {
        $_SESSION['success'] = 'تم حذف الجهاز بنجاح';
    } else {
        $_SESSION['error'] = $res;
    }
    header('location: index.php');
}