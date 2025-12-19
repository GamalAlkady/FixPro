<?php
require_once 'config.php';
$db = new DB('users');

// die('jj');
if (isset($_POST['login'])) {
    $data = $_POST;
    unset($data['login']);
    $item = $db->getBy(['email' => $data['email'], 'password' => $data['password']]);
    // die('$_item[']');

    if (!is_array($item)) {
        $_SESSION['error'] = "الايميل او كلمة المرور غير صحيح";
        $_SESSION['old'] = $data;
        back();
    } else {
        $_SESSION['id'] = $item['id'];
        $_SESSION['name'] = $item['first_name'] . ' ' . $item['last_name'];

        if ($item['email_verification'] == 0) {
            $_SESSION['verify_role'] = $item['role'];
            $_SESSION['email'] = $item['email'];
            redirect('email_verification.php');
            return;
        }
        $_SESSION['role'] = $item['role'];

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
    $data['image']=uploadfile('image',USER_PATH);
    if ($data['image']===null){
        unset($data['image']);
    }

    $res = $db->insert($data);
    if ($res == true) {
        unset($_SESSION['old']);
        $item = $db->getBy(['email' => $data['email']]);
        $_SESSION['name'] = $item['first_name'] . ' ' . $item['last_name'];
        $_SESSION['id'] = $item['id'];
        $_SESSION['email'] = $item['email'];
        $_SESSION['role'] = $item['role'];
        redirect($_SESSION['role'].'/index.php');
    } else {
        $_SESSION['error'] = $res;
        back();
    }
}
elseif (isset($_POST['web_rate'])) {
    $data = $_POST;
    $data['rate'] = $data['web_rate'];
    unset($data['web_rate']);
    if ($data['id'] == 0) {
        unset($data['id']);
        $res = (new DB('web_ratings'))->insert($data);
    } else    $res = (new DB('web_ratings'))->update($data, $data['id']);

    back();
} elseif (isset($_POST['later'])) {
    $data = $_POST;

    if ($data['id'] == 0) {
        unset($data['id']);
        $res = (new DB('web_ratings'))->insert(['user_id' => $data['user_id']]);
    }else (new DB('web_ratings'))->update(['updated_at'=>(new DateTime())->format('Y-m-d H:i:s')], $data['id']);

    back();
} elseif (isset($_POST['logout'])) {
    session_destroy();
    redirect('login.php');  
}