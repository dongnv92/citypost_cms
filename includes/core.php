<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 04/06/2018
 * Time: 09:53
 */

session_start();
error_reporting(0);
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once 'db.php';
$connection = sqlsrv_connect( _DB_SERVER, array( "Database"=> _DB_NAME, "UID"=>_DB_USER, "PWD"=>_DB_PASS, "CharacterSet" => "UTF-8"));


if( !$connection ) {
    echo "<center>Connection fail.</center>";
    exit();
}

require_once 'function.php';

define('_CONFIG_TIME',time());
define('_CONFIG_PAGINATION', 50); // Số bản ghi được hiển thị trên 1 trang
define('_URL_ADMIN',_URL_HOME);
define('_DB_TABLE_CUSTOMER', 'tblCustomer');
define('_DB_TABLE_USERS', 'tblUsers');
define('_DB_TABLE_DEVICE', 'tblDevice');
define('_DB_TABLE_DEVICE_CONFIG', 'tblDeviceConfig');
define('_DB_TABLE_TRANSACTIONS', 'tblTransactions');
define('_DB_TABLE_POSTMAN', 'tblPostMan');
$_SESSION["back_url"] = $_SERVER["HTTP_REFERER"] ? $_SERVER["HTTP_REFERER"] : _URL_ADMIN;
define('_DB_URL_BACK', $_SESSION["back_url"]);

/** Manager Session, Cookie User */
if ($_COOKIE['user'] && $_COOKIE['pass'])
{
    $user_id = ($_COOKIE['user']);
    $user_pass = $_COOKIE['pass'];
    $_SESSION['user'] = $user_id;
    $_SESSION['pass'] = $user_pass;
}
$user_id 	= $_SESSION['user'] ? $_SESSION['user'] : '';
$user_pass 	= $_SESSION['pass'] ? $_SESSION['pass'] : '';

/** Check user and setting user  */
if ($user_id && $user_pass)
{
    $check_login = checkGlobal(_DB_TABLE_USERS, array('id' => $user_id, 'password' => $user_pass));
    if($check_login > 0){
        $data_user 	= getGlobalAll(_DB_TABLE_USERS, array('id' => $user_id), array('onecolum' => 'limit'));
    }
    else{
        unset ($_SESSION['user']);
        unset ($_SESSION['pass']);
        setcookie('user', '');
        setcookie('pass', '');
        $user_id 	= false;
        $user_pass	= false;
    }
}

$page   = isset($_REQUEST['page'])      ? $_REQUEST['page']     : 1;
$act    = isset($_REQUEST['act'])       ? $_REQUEST['act']      : false;
$type   = isset($_REQUEST['type'])      ? $_REQUEST['type']     : false;
$id     = isset($_REQUEST['id'])        ? $_REQUEST['id']       : false;
$submit = isset($_REQUEST['submit'])    ? $_REQUEST['submit']   : false;