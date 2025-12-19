<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!function_exists('include_file')) {
    function require_file($file)
    {
        require(ROOT_PATH .'/'. $file);
    }
}

if (!function_exists('assets')) {
    function assets($path, string $default = ''): string
    {
        if (!is_file_exists($path) and !empty($default))
            return BASE_PATH . '/assets/'. $default;;
        return BASE_PATH . '/assets/' . $path;
    }
}

if (!function_exists('get_path')) {
    function get_path($for,$forRole=true): string
    {
        $path=BASE_PATH;
        if ($forRole) $path.=($_SESSION['role'] ?? 'user').'/';
        return  $path. $for;
    }
}

if (!function_exists('BASE_PATH')) {
    function BASE_PATH($for): string
    {
        return BASE_PATH . $for;
    }
}


if (!function_exists('is_file_exists')) {
    function is_file_exists($path): bool
    {

        return (!empty($path) and file_exists(ROOT_PATH .'/assets/' . $path));
    }
}


if (!function_exists('uploadfile')) {
    function uploadfile($input_name, $dest)
    {
        if (!isset($_FILES[$input_name]) or empty($_FILES[$input_name]['name'])) return null;
        $origin = strtolower(basename($_FILES[$input_name]['name']));
        $fulldest = $dest . $origin;

        for ($i = 1; file_exists($fulldest); $i++) {
            $fileext = (strpos($origin, '.') === false ? '' : '.' . substr(strrchr($origin, "."), 1));
            $filename = substr($origin, 0, strlen($origin) - strlen($fileext)) . '[' . $i . ']' . $fileext;
            $fulldest = $dest . $filename;
        }

        if (move_uploaded_file($_FILES[$input_name]["tmp_name"], ROOT_PATH . '/assets/'. $fulldest))
            return $fulldest;
        return false;
    }

}

if (!function_exists('status')){
    function status($value): string
    {
        $bg='bg-primary';
        if ($value=='accepted') {
            $bg = 'bg-success';
            $value='مقبول';
        }
        elseif ($value=='completed') {
            $bg = 'bg-secondary';
            $value='مكتمل';
        }
        elseif ($value=='rejected') {
            $bg = 'bg-danger';
            $value='مرفوض';
        }
        elseif ($value=='canceled' or $value=='user_canceled') {
            $bg = 'bg-danger';
            $value='ملغي';
        }
        elseif ($value=='unpaid') {
            $bg = 'bg-warning text-dark';
            $value='غير مدفوع';
        }
        elseif ($value=='paid') {
            $bg = 'bg-success';
            $value='مدفوع';
        }
        else $value='جديد';

        return "<span class='badge $bg'>$value</span>";
    }
}


if (!function_exists('isRole')) {
    function isRole($role): bool
    {
        return (isset($_SESSION['role']) and $_SESSION['role']===$role);
    }
}

if (!function_exists('old')) {
    function old($name,$default=''): string|int|null
    {
        return ($_SESSION['old'][$name] ?? $default);
    }
}



if (!function_exists('redirect')) {
    function redirect($url)
    {
        header("location: " .BASE_PATH . $url);
//        echo("<script>location.href = '".$page."';</script>");
    }
}

if (!function_exists('back')) {
    function back()
    {
        header("location: " .$_SERVER['HTTP_REFERER']);
//        echo("<script>location.href = '".$page."';</script>");
    }
}

if (!function_exists('sendEmail')){
    function sendEmail($to,$name):bool|string{


        require ROOT_PATH.'/PHPMailer/src/Exception.php';
        require ROOT_PATH.'/PHPMailer/src/PHPMailer.php';
        require ROOT_PATH.'/PHPMailer/src/SMTP.php';
        $mail=new PHPMailer(false);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = MAIL_HOST;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = MAIL_USERNAME;                     //SMTP username
            $mail->Password   = MAIL_PASSWORD;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
            $mail->Port       = MAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(MAIL_FROM_ADDRESS, 'FixPro');
            $mail->addAddress($to, $name);     //Add a recipient


            //Attachments
//    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML

            $template = file_get_contents(ROOT_PATH."/email_template.html");
            $bytes = random_bytes(16);
            $token = bin2hex($bytes); // Generates a 32-character random hexadecimal string
            $_SESSION['token']=$token;
            $_SESSION['time_verify'] = time();
            $html = str_replace(['{{الصورة}}', '{{الرابط}}'], [assets('images/logo/logo.png'),
                BASE_PATH($_SESSION['verify_role'].'/email_verification.php?token='.$token)], $template);
//            $mail->Subject = 'Here is the subject';
            $mail->Body    = $html;
//            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            return $mail->send();
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}










