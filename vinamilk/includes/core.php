<?php
/**
 * Created by PhpStorm.
 * User: nguye
 * Date: 05/01/2018
 * Time: 23:04
 */
session_start();
error_reporting(0);
date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once 'db.php';
require_once 'function_system.php';


// Get Parameter
$submit	    = $_POST['submit'];
$id 	    = isset($_REQUEST['id']) 	    ? abs(intval($_REQUEST['id'])) 	: false;
$act 	    = isset($_REQUEST['act']) 	    ? trim($_REQUEST['act']) 		: '';
