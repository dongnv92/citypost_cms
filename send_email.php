<?php
/**
 * Created by PhpStorm.
 * User: Dong
 * Date: 02/08/2018
 * Time: 17:09
 */
require_once 'includes/core.php';
require_once 'includes/func_send_email.php';

$seri = $_GET['seri'];
$type = $_GET['type'];

// check device
$device = getGlobalAll(_DB_TABLE_DEVICE, array('deviceID' => $seri), array('onecolum' => 'limit'));
if(!$device){
    echo json_encode(array('error' => 'Seri Empty'));
    exit();
}

if(!$seri || !$type){
    echo json_encode(array('error' => 'Empty seri or type'));
    exit();
}

$device_config = getGlobalAll(_DB_TABLE_DEVICE_CONFIG, array('deviceID' => $seri, 'status' => 1), array('onecolum' => 'limit'));
if(!$device_config){
    echo json_encode(array('error' => 'Device Config Empty'));
    exit();
}

$customer = getGlobalAll(_DB_TABLE_CUSTOMER, array('cusID' => $device_config['cusID']), array('onecolum' => 'limit'));

$content = '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#dcf0f8" style="margin: 0px; padding: 0px; background-color: rgb(242, 242, 242); font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: rgb(68, 68, 68); line-height: 18px;" data-mce-selected="1">
    <tbody>
    <tr>
        <td align="center" valign="top" style="font-family: Arial, Helvetica, sans-serif; margin: 0px; font-size: 12px; line-height: 18px;">
            <table border="0" cellpadding="0" cellspacing="0" width="600" style="margin-top: 15px;">
                <tbody>
                <tr>
                    <td align="center" valign="bottom" style="font-family: arial, sans-serif; margin: 0px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom: 3px solid rgb(0, 183, 241); padding-bottom: 10px; background-color: rgb(255, 255, 255);">
                            <tbody>
                            <tr>
                                <td valign="top" bgcolor="#FFFFFF" width="100%" style="font-family: arial, sans-serif; margin: 0px; padding: 0px;">
                                    <a href="#" target="_blank" style="color: rgb(0, 126, 211); border: medium none; text-decoration-line: none; margin: 0px 120px 0px 20px;">
                                        <img src="http://citypost.com.vn/images/logo/logocitypost.png" class="CToWUd">
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr style="background: rgb(255, 255, 255);">
                    <td align="left" width="600" height="auto" style="font-family: arial, sans-serif; margin: 0px; padding: 15px;">
                        <table>
                            <tbody>
                            <tr>
                                <td style="font-family: arial, sans-serif; margin: 0px;">
                                    <h1 style="font-size: 17px; padding: 0px 0px 5px; margin: 0px;">Cảm ơn quý khách '. $customer['fullname'] .' đã gọi thư tại CITYPOST.COM.VN</h1>
                                    <p style="margin: 4px 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px;">CITYPOST rất vui thông báo đơn hàng của quý khách đã được tiếp nhận và đang trong quá trình xử lý.</p>
                                    <h3 style="font-size: 13px; color: rgb(2, 172, 234); text-transform: uppercase; margin: 20px 0px 0px; border-bottom: 1px solid rgb(221, 221, 221);">
                                        THÔNG TIN ĐƠN HÀNG<span style="font-size: 12px; color: rgb(119, 119, 119); text-transform: none; font-weight: normal;"> (Ngày '. date(d, _CONFIG_TIME) .' tháng '. date(m, _CONFIG_TIME) .' năm '. date(Y, _CONFIG_TIME) .' '. date('H:i:s', _CONFIG_TIME) .')</span>
                                    </h3>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family: Arial, Helvetica, sans-serif; margin: 0px; font-size: 12px; line-height: 18px;">
                                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th align="left" width="50%" style="padding: 6px 9px 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">Thông tin khách hàng</th>
                                            <th align="left" width="50%" style="padding: 6px 9px 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">Địa chỉ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td valign="top" style="font-family: Arial, Helvetica, sans-serif; margin: 0px; padding: 3px 9px 9px; border-top: 0px; font-size: 12px; line-height: 18px;">
                                                <span style="text-transform: capitalize;">'. $customer['fullname'] .'</span><br>
                                                <a href="mailto:'. $customer['mail'] .'" target="_blank" style="color: rgb(17, 85, 204);">'. $customer['mail'] .'</a><br>'. $customer['phone_company'] .'
                                            </td>
                                            <td valign="top" style="font-family: Arial, Helvetica, sans-serif; margin: 0px; padding: 3px 9px 9px; border-top: 0px; border-left: 0px; font-size: 12px; line-height: 18px;">
                                                '. $customer['addr_receive'] .'
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family: arial, sans-serif; margin: 0px;">
                                    <h2 style="margin: 10px 0px; border-bottom: 1px solid rgb(221, 221, 221); padding-bottom: 5px; font-size: 13px; color: rgb(2, 172, 234);">CHI TIẾT ĐƠN HÀNG</h2>
                                    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="background: rgb(245, 245, 245);">
                                        <thead>
                                        <tr>
                                            <th align="left" bgcolor="#02acea" style="padding: 6px 9px; color: rgb(255, 255, 255); font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 14px;">Kiểu bấm</th>
                                            <th align="left" bgcolor="#02acea" style="padding: 6px 9px; color: rgb(255, 255, 255); font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 14px;">Thời gian</th>
                                        </tr>
                                        </thead>
                                        <tbody bgcolor="#eee" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px;">
                                        <tr>
                                            <td align="left" valign="top" style="font-family: arial, sans-serif; margin: 0px; padding: 3px 9px;">'. ($type == 1 ? 'Gọi lấy thư' : ( $type == 2 ? 'Gọi lấy hàng' : 'Bấm hủy giao dịch' )) .'</td>
                                            <td align="left" valign="top" style="font-family: arial, sans-serif; margin: 0px; padding: 3px 9px;">'. date('H:i:s d/m/Y', _CONFIG_TIME) .'</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family: arial, sans-serif; margin: 0px;"><br>
                                    <p style="margin: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px;">Trường hợp quý khách có những băn khoăn về đơn hàng, có thể gọi trực tiếp đến tổng đài: <strong>1900 2630</strong></p>
                                    <p style="margin: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px; color: #0A72E8">Quý khách muốn hủy, hãy bấm và giữ trong 3 giây. Sẽ có 1 Email xác nhận đơn bị hủy</p>
                                    <p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px; border: 1px dashed rgb(20, 173, 229); padding: 5px; list-style-type: none;">Từ ngày 1/08/2018, CITYPOST sẽ gởi Email khi đơn hàng của bạn được xác nhận thành công. Chúng tôi sẽ liên hệ lại để xác nhận đơn hàng của bạn.</p>
                                    <p style="margin: 10px 0px 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px;">Bạn cần được hỗ trợ ngay? Chỉ cần email&nbsp;<a href="mailto:info@citypost.com.vn" target="_blank" style="color: rgb(9, 146, 2); text-decoration-line: none;"><strong>info@citypost.com.vn</strong></a>, hoặc gọi số điện thoại&nbsp;<strong style="color: rgb(9, 146, 2);">1900 2630</strong>&nbsp;(8-21h cả T7,CN). Đội ngũ CITYPOST Care luôn sẵn sàng hỗ trợ bạn bất kì lúc nào.</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family: arial, sans-serif; margin: 0px;"><br>
                                    <p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; margin: 0px; padding: 0px; line-height: 18px; font-weight: bold;">Một lần nữa CITYPOST cảm ơn quý khách.<br></p>
                                    <p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px; text-align: right;"><strong><a href="http://citypost.com.vn/" target="_blank" style="color: rgb(0, 163, 221); text-decoration-line: none; font-size: 14px;">CITYPOST.COM.VN</a></strong><br></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" style="font-family: arial, sans-serif; margin: 0px;"><table width="600">
                <tbody>
                <tr>
                    <td style="font-family: arial, sans-serif; margin: 0px;">
                        <p align="left" style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; line-height: 18px; color: rgb(75, 141, 165); padding: 10px 0px; margin: 0px;">
                            Quý khách nhận được email này vì đã gọi thư từ thiết bị của CITYPOST.<br>
                            Địa chỉ: Hà Nội : Tầng 6, Tháp B, TN Sông Đà, Đường Phạm Hùng, P. Mỹ Đình 1, Nam Từ Liêm, Hà Nội.<br />
                            Liên hệ: số điện thoại 1900 2630 hoặc email:  info@citypost.com.vn
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>';

if($type == 1){
    $title = '[CITYPOST.COM.VN] Yêu cầu gọi lấy thư được xác nhận - Do Not Reply';
}else if($type == 2){
    $title = '[CITYPOST.COM.VN] Yêu cầu gọi lấy hàng được xác nhận - Do Not Reply';
}else if($type == 3){
    $title = '[CITYPOST.COM.VN] Yêu cầu hủy từ thiết bị được xác nhận - Do Not Reply';
}

dongSendEmail(array(
    'user'      => 'dongnguyen@citypost.com.vn',
    'pass'      => '123456789',
    'send_name' => 'CITYPOST.COM.VN',
    'attach'    => '',
    'title'     => $title,
    'content'   => $content,
    'receive'   => array(
        array('nguyenvandong242@gmail.com'      => 'Nguyễn Văn Đông')
    )));
?>