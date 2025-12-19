<?php
require_once '../../config.php';
$db = new DB('orders');

if (isset($_POST['add'])) {
    $data = $_POST;
    unset($data['add']);
    $data['description'] = trim($data['description']);
    $data['image'] = uploadfile('image', ORDER_PATH);
    if ($data['image'] == false) {
        $data['image'] = '';
    }

//    var_dump($data);die();
    $data['id'] = $db->getNextId();
    $res = $db->insert($data);

    if ($res === true) {
        $_SESSION['success'] = 'تمت إضافة الطلب بنجاح';
        header('location: index.php');
    } else {
        unlink($data['image']);
        $_SESSION['old'] = $data;
        $_SESSION['error'] = $res;
        header('location: add.php');
    }
}
elseif (isset($_POST['edit'])) {
    $data = $_POST;
    unset($data['edit']);
    $data['description'] = trim($data['description']);

    $data['image'] = uploadfile('image', ORDER_PATH);
    if ($data['image'] === null) {
        unset($data['image']);
    } elseif (!empty($data['old_image'])) {
        unlink('assets/' . ORDER_PATH . $data['old_image']);
    }


    if (isset($data['old_technician_id']) and $data['old_technician_id'] == $data['technician_id']) $data['status'] = 'modify';
    else {
        $data['status']='new';
        $data['post_id']=null;
        $data['reason']=null;
    }
    $data['is_read']=0;
    unset($data['old_image']);
    unset($data['old_technician_id']);
    $res = $db->update($data, $data['id']);
    if ($res == true) {
        $_SESSION['success'] = 'تم تعديل الطلب بنجاح';
        header('location: index.php');
    } else {
        $_SESSION['old'] = $data;
        $_SESSION['error'] = $res;
        back();
    }
} elseif (isset($_POST['cancel'])) {
    $res = $db->update(['status' => 'user_canceled'], $_POST['cancel']);
    if ($res == true) {
        $_SESSION['success'] = 'تم الغاء الطلب بنجاح';
    } else {
        $_SESSION['error'] = $res;
    }

   back();
} elseif (isset($_POST['delete'])) {
    $item = $db->getBy('id', $_POST['delete']);
    if ($item != null) {
        $res = $db->delete($_POST['delete']);
        if ($res == true) {
            unlink(ROOT_PATH . '/assets/' . $item['image']);
            $_SESSION['success'] = 'تم حذف الطلب بنجاح';
        } else {
            $_SESSION['error'] = $res;
        }
    }
    header('location: index.php');
} elseif (isset($_POST['rate'])) {
    $data = $_POST;

    if ($data['id'] == 0) {
        unset($data['id']);
        $res = (new DB('ratings'))->insert($data);
    } else    $res = (new DB('ratings'))->update($data, $data['id']);

    if ($res == false) {
        $_SESSION['old'] = $data;
        $_SESSION['error'] = $res;
        back();
    } else {
        unset($_SESSION['old']);
        back();
    }
}