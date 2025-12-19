<?php
require_once '../../config.php';
$db = new DB('technicians');

if (isset($_POST['edit'])) {
    $data = $_POST;
    unset($data['edit']);
    $data['address']=trim($data['address']);

    $data['image']=uploadfile('image',TECHNICIAN_PATH);
    if ($data['image']===null){
        unset($data['image']);
    }else{
//        $_SESSION['image']=$data['image'];
        unlink(ROOT_PATH.'/assets/'.$data['old_image']);
    }

    $data['image_home']=uploadfile('image_home',TECHNICIAN_PATH);
    if ($data['image_home']===null){
        unset($data['image_home']);
    }else{
        unlink(ROOT_PATH.'/assets/'.$data['old_image_home']);
    }

    unset($data['old_image']);
    unset($data['old_image_home']);
    $_SESSION['old'] = $data;
    $res = $db->update($data,$data['id']);
    if ($res == true) {
        unset($_SESSION['old']);
        $_SESSION['success'] = 'تم التعديل بنجاح';
        header('location: index.php');
    } else {
        $_SESSION['error'] = $res;
        header('location: edit.php');
    }
}

elseif (isset($_POST['change'])) {
//    die($_POST['delete']);
        $data=$_POST;
    $item = $db->getBy(['id'=>$data['id'],'password'=>$data['old_password']]);

    $_SESSION['old']=$data;
    if ($item!=null) {
        $res = $db->update(['password'=>$data['new_password']],$data['id']);
        if ($res == true) {
            unset($_SESSION['old']);
            $_SESSION['success'] = 'تم تغيير كلمة المرور بنجاح';
            header('location: index.php');
        } else {
            $_SESSION['error'] = $res;
            header('location: change_password.php?id='.$data['id']);
        }
    }else{
        unset($_SESSION['old']['old_password']);
        $_SESSION['error'] = 'كلمة المرور الحالية غير صحيحة';
        header('location: change_password.php?id='.$data['id']);
    }
}