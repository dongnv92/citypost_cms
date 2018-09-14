<?php
/**
 * Created by PhpStorm.
 * User: Dong
 * Date: 06/08/2018
 * Time: 10:39
 */
use PHPMailer\PHPMailer\PHPMailer;
require_once 'email/Exception.php';
require_once 'email/PHPMailer.php';
require_once 'email/SMTP.php';

function dongSendEmail($option){
    $mail               = new PHPMailer;
    $mail->CharSet      = "utf-8";
    $mail->IsHTML(true);
    $mail->isSMTP();
    $mail->SMTPDebug    = 0;
    $mail->Host         = 'smtp.gmail.com';
    $mail->Port         = 465;
    $mail->SMTPSecure   = 'ssl';
    $mail->SMTPAuth     = true;
    $mail->Username     = $option['user'];
    $mail->Password     = $option['pass'];
    $mail->setFrom($option['user'], $option['send_name']);
    $mail->addReplyTo($option['user'], $option['send_name']);
    foreach ($option['receive'] AS $receive){
        foreach ($receive AS $receive_address => $receive_name){
            $mail->addAddress($receive_address, $receive_name);
        }
    }
    if($option['attach']){
        foreach ($option['attach'] AS $files){
            $mail->addAttachment($files);
        }
    }
    $mail->Subject      = $option['title'];
    $mail->msgHTML($option['content']);
    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        return true;
    }
}
