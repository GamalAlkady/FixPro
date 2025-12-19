<?php
session_start();
if(!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}


const PROJECT_NAME = 'FixPro';
const BASE_PATH = 'http://localhost/FixPro/';
//const BASE_PATH = 'https://fix-pro.freesite.online/' ;

//const ROOT_PATH = BASE_PATH . '/' . BASE_PATH;
define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT'].'/'.PROJECT_NAME);

// Database Credentials
defined('DATABASE_HOST_NAME')       ? null : define ('DATABASE_HOST_NAME', 'localhost');
//defined('DATABASE_HOST_NAME')       ? null : define ('DATABASE_HOST_NAME', 'sql207.infinityfree.com');
defined('DATABASE_USER_NAME')       ? null : define ('DATABASE_USER_NAME', 'root');
//defined('DATABASE_USER_NAME')       ? null : define ('DATABASE_USER_NAME', 'if0_37770378');
defined('DATABASE_PASSWORD')        ? null : define ('DATABASE_PASSWORD', '');
//defined('DATABASE_PASSWORD')        ? null : define ('DATABASE_PASSWORD', 'LZvsHBmhiP3SR');
defined('DATABASE_DB_NAME')         ? null : define ('DATABASE_DB_NAME', 'fix_pro');
//defined('DATABASE_DB_NAME')         ? null : define ('DATABASE_DB_NAME', 'if0_37770378_fix_pro');


// define the path to our uploaded files
//define ('UPLOAD_PATH', sprintf("uploads%s", DS));
const POST_PATH = 'uploads/posts/';
const ACCOUNT_PATH = 'uploads/account/';
const ORDER_PATH = 'uploads/orders/';
const TECHNICIAN_PATH = 'uploads/technicians/';
const USER_PATH = 'uploads/users/';
const ADMIN_PATH = 'uploads/';


const MAIL_MAILER='smtp';
const MAIL_HOST='smtp.gmail.com';
const MAIL_PORT=587;
const MAIL_USERNAME='gamal333re@gmail.com';
const MAIL_PASSWORD="deyp fbak tuhu uhpl";
const MAIL_ENCRYPTION='tls';
const MAIL_FROM_ADDRESS='gamal333re@gmail.com';
const MAIL_FROM_NAME="FixPro";


include 'helper.php';
//include ROOT_PATH.'/helpers/fields_helper.php';
include ROOT_PATH.'/DB.php';


