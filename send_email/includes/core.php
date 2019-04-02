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
define('_HOME', 'http://112.78.11.14/send_email');
define('_LOGIN', _HOME.'/login.php');
define('_LOGOUT', _HOME.'/logout.php');
define('_URL_API', _HOME.'/api.php');
require_once 'function.php';

/** Manager Session, Cookie User */
if ($_COOKIE['user'] && $_COOKIE['pass']) {
    $user_id            = ($_COOKIE['user']);
    $user_pass          = $_COOKIE['pass'];
    $_SESSION['user']   = $user_id;
    $_SESSION['pass']   = $user_pass;
}
$user_id 	= $_SESSION['user'] ? $_SESSION['user'] : '';
$user_pass 	= $_SESSION['pass'] ? $_SESSION['pass'] : '';

/** Check user and setting user  */
if ($user_id && $user_pass) {
    $user           = getApi('authencation', array('user' => $user_id, 'pass' => $user_pass));
    if($user['response'] != 200){
        unset ($_SESSION['user']);
        unset ($_SESSION['pass']);
        setcookie('user', '');
        setcookie('pass', '');
        $user_id 	= false;
        $user_pass	= false;
        $user	    = false;
    }
}

$page   = isset($_REQUEST['page'])      ? $_REQUEST['page']     : 1;
$act    = isset($_REQUEST['act'])       ? $_REQUEST['act']      : false;
$type   = isset($_REQUEST['type'])      ? $_REQUEST['type']     : false;
$id     = isset($_REQUEST['id'])        ? $_REQUEST['id']       : false;
$submit = isset($_REQUEST['submit'])    ? $_REQUEST['submit']   : false;