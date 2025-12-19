<?php
require_once '../../config.php';
$db = new DB('invoices');

if (isset($_POST['delete'])) {
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