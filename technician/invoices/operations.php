<?php
require_once '../../config.php';
$db = new DB('invoices');

if (isset($_POST['add'])) {
    $data = $_POST;
    unset($data['add']);
    $data['notes']=trim($data['notes']);

    $data['id']=$db->getNextId('INV-');
    $res = $db->insert($data);
    $_SESSION['old'] = $data;

    if ($res === true) {
        $_SESSION['success'] = 'تمت الفوتره بنجاح';
        unset($_SESSION['old']);
        header('location: index.php');
    } else {
        $_SESSION['error'] = $res;
        header('location: add.php');
    }
}
elseif (isset($_POST['edit'])) {
    $data = $_POST;
    unset($data['edit']);
    $data['notes']=trim($data['notes']);

    $res = $db->update($data,$data['id']);
    $_SESSION['old'] = $data;
    if ($res == true) {
        $_SESSION['success'] = 'تم تعديل الفوتره بنجاح';
        unset($_SESSION['old']);
        header('location: index.php');
    } else {
        $_SESSION['error'] = $res;
        header('location: edit.php');
    }
}

elseif (isset($_POST['delete'])) {
//    die($_POST['delete']);
    $item = $db->getBy('id',$_POST['delete']);
    if ($item!=null) {
        $res = $db->delete($_POST['delete']);
        if ($res == true) {
            $_SESSION['success'] = 'تم حذف الفوتره بنجاح';
        } else {
            $_SESSION['error'] = $res;
        }
    }
    header('location: index.php');
}