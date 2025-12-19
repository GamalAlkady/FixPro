<?php
require_once '../../config.php';
$db = new DB('posts');

if (isset($_POST['add'])) {
    $data = $_POST;
    unset($data['add']);
    $data['title']=trim($data['title']);
    $data['description']=trim($data['description']);
    $data['image']=uploadfile('image',POST_PATH);
    if ($data['image']==false){
        $data['image']='';
    }
    $res = $db->insert($data);

    if ($res === true) {
        $_SESSION['success'] = 'تمت إضافة المنشور بنجاح';
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
    $data['title']=trim($data['title']);
    $data['description']=trim($data['description']);

    $data['image']=uploadfile('image',POST_PATH);
    if ($data['image']===null){
        unset($data['image']);
    }else{
        unlink(ROOT_PATH.'/'.$data['old_image']);
    }

    unset($data['old_image']);
    $res = $db->update($data,$data['id']);
    if ($res == true) {
        $_SESSION['success'] = 'تم تعديل المنشور بنجاح';
        header('location: index.php');
    } else {
        $_SESSION['old'] = $data;
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
            unlink(ROOT_PATH.'/'.$item['image']);
            $_SESSION['success'] = 'تم حذف المنشور بنجاح';
        } else {
            $_SESSION['error'] = $res;
        }
    }
    header('location: index.php');
}