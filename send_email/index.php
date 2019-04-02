<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 2018-11-06
 * Time: 09:08
 */
use PHPMailer\PHPMailer\PHPMailer;
require_once 'includes/core.php';
require_once 'vendor/autoload.php';
if(!$user){
    header('location:'._LOGIN);
}
header('location:'._HOME.'/email.php?act=send');