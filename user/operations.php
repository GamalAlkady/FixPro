<?php
require_once '../config.php';
$db = new DB('users');
if (isset($_POST['web_rate'])) {
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