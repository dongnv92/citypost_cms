<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 06/06/2018
 * Time: 10:39
 */
require_once('includes/core.php');
session_destroy();
setcookie('user', '');
setcookie('pass', '');
$user_id 	= false;
$user_pass	= false;

header("location:"._URL_HOME);