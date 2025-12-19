<?php
require_once '../../config.php';
$db = new DB('users');

if (isset($_POST['edit'])) {
    $data = $_POST;
    unset($data['edit']);

    $data['image']=uploadfile('image',USER_PATH);
    if ($data['image']===null){
        unset($data['image']);
    }else{
//        $_SESSION['image']=$data['image'];
        unlink(ROOT_PATH.'/assets/'.$data['old_image']);
    }
    unset($data['old_image']);
    $_SESSION['old'] = $data;
    $res = $db->update($data,$data['id']);
    if ($res == true) {
        unset($_SESSION['old']);
        $_SESSION['success'] = 'تم التعديل بنجاح';
//        header('location: index.php');
    } else {
        $_SESSION['error'] = $res;
//        header('location: edit.php');
    }
    back();
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
}elseif (isset($_POST['rate'])) {
    $data = $_POST;
    (new DB('web_ratings'))->update($data, $data['id']);
//    redirect('index.php');
    back();
}