<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 2018-11-06
 * Time: 09:08
 */
//error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
require_once 'includes/core.php';
require_once 'vendor/autoload.php';
$email  = isset($_REQUEST['email']) && !empty($_REQUEST['email']) ? trim($_REQUEST['email']) : '';
if(!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo 'Chưa có Email hoặc Email không đúng định dạng';
    exit();
}
$api = getApi('get_detail_by_email', array('email' => $email));
if($api['response'] != 200){
    echo 'Email không có trong danh sách';
    exit();
}

$templates      = 4;
$check_temp     = getApi('check_send_templates', array('email_to' => $email, 'email_templates' => $templates));
if($check_temp['number'] > 0){
    echo 'Nội dung này ('. $templates .') đã được gửi đến Email này '.$email;
    exit();
}

$email_address  = $api['list_email'];
$email_name     = $api['list_name'];
$content        = file_get_contents('templates/'. $templates .'.php');
$content        = str_replace(array('{name_company}', '{email_address}'), array($email_name, $email_address), $content);
$title          = 'QC: CÔNG TY CỔ PHẦN BƯU CHÍNH THÀNH PHỐ KỶ NIỆM 10 NĂM THÀNH LẬP VÀ PHÁT TRIỂN';

$acc = array();
$acc[] = array('login' => 'giaohangsieutoccitypost@gmail.com', 'pass' => 'Citypost2018');
$acc[] = array('login' => 'giaohangnhanhcitypost@gmail.com', 'pass' => 'Citypost2018');
$acc[] = array('login' => 'citypostvietnam@gmail.com', 'pass' => 'Citypost2018');
$acc[] = array('login' => 'cityposttoanquoc@gmail.com', 'pass' => 'Citypost2018');
$acc[] = array('login' => 'cityposthanoi2@gmail.com', 'pass' => 'Citypost2018');
$acc[] = array('login' => 'cityposttoanquoc2@gmail.com', 'pass' => 'Citypost2018');
$acc[] = array('login' => 'waptruyenhay@gmail.com', 'pass' => 'anhdonganhdong');
$acc[] = array('login' => 'dongsocial@gmail.com', 'pass' => 'anhdonganhdong');
$acc[] = array('login' => 'wap4g.vn@gmail.com', 'pass' => 'donghuong2442');

$acc_ran = rand(0, (count($acc) - 1));
$user['login']  = $acc[$acc_ran]['login'];
$user['pass']   = $acc[$acc_ran]['pass'];
$user['name']   = 'CITYPOST';

$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->CharSet = 'UTF-8';
$mail->Username = $user['login'];
$mail->Password = $user['pass'];
$mail->setFrom($user['login'], $user['name']);
$mail->addReplyTo($user['login'], $user['name']);
$mail->addAddress($email, 'Quý Khách Hàng');
$mail->Subject = $title;
$mail->msgHTML($content, __DIR__);
$mail->AltBody = 'CITYPOST xin thông báo cung cấp dịch vụ phát quà Tết và lịch 2019';
if (!$mail->send()) {
    echo 'Có lỗi trong quá trình gửi mail đến: '.$email.' ('. $user['login'] .')';
    file_get_contents('http://112.78.11.14/send_email/api.php?act=add_activity&email_from='. $user['login'] .'&email_to='. $email .'&email_status=0&email_templates='.$templates.'&token='.getToken());
} else {
    echo "<i class='text-success'>Gửi Email đến $email thành công (". $user['login'] .")</i>";
    file_get_contents('http://112.78.11.14/send_email/api.php?act=update_count&email='.$email.'&token='.getToken());
    file_get_contents('http://112.78.11.14/send_email/api.php?act=add_activity&email_from='. $user['login'] .'&email_to='. $email .'&email_status=1&email_templates='.$templates.'&token='.getToken());
}
