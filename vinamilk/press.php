<?php
/**
 * Created by PhpStorm.
 * User: Dong
 * Date: 10/08/2018
 * Time: 16:11
 */
require_once 'includes/core.php';

if($submit){
    $device_code    = (isset($_POST['device_code'])     && !empty($_POST['device_code']))       ? $_POST['device_code']     : '';
    $device_type    = (isset($_POST['device_type'])     && !empty($_POST['device_type']))       ? $_POST['device_type']     : '';
    $error          = array();
    if(checkGlobal('dong_device', array('device_code' => $device_code)) == 0){
        $error['info'] = 'Thiết bị không tồn tại trên hệ thống<br />';
    }
    if(!$device_type){
        $error['info'] .= 'Chưa nhập kiểu bấm<br />';
    }

    $device = getGlobal('dong_device', array('device_code' => $device_code));

    if(!$error){
        switch ($device_type){
            case 1:
                $member     = getGlobal('dong_member', array('member_device' => $device_code, 'member_status' => 1));
                $items      = '';
                $total_price= 0;
                foreach (getGlobalAll('dong_cart', array('cart_member' => $member['id'], 'cart_device' => $device_code)) AS $item){
                    $total_price_one_item = $item['cart_product_price'] * $item['cart_product_amount'];
                    $total_price += $total_price_one_item;
                    $items .= '<tr>
                        <td align="left" valign="top" style="font-family: arial, sans-serif; margin: 0px; padding: 3px 9px;">
                            <span class="m_1611805067555861768m_-1588988751008573123name">'. $item['cart_product_name'] .'</span><br>
                        </td>
                        <td align="left" valign="top" style="font-family: arial, sans-serif; margin: 0px; padding: 3px 9px;">'. adddotstring($item['cart_product_price']) .'&nbsp;₫</td>
                        <td align="left" valign="top" style="font-family: arial, sans-serif; margin: 0px; padding: 3px 9px;">'. $item['cart_product_amount'] .'</td>
                        <td align="left" valign="top" style="font-family: arial, sans-serif; margin: 0px; padding: 3px 9px;">0&nbsp;₫</td>
                        <td align="right" valign="top" style="font-family: arial, sans-serif; margin: 0px; padding: 3px 9px;">'. adddotstring($total_price_one_item) .'&nbsp;₫</td>
                    </tr>';
                }
                $content    = '
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#dcf0f8" style="margin: 0px; padding: 0px; background-color: rgb(242, 242, 242); font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: rgb(68, 68, 68); line-height: 18px;" data-mce-selected="1">
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
                                                    <h1 style="font-size: 17px; padding: 0px 0px 5px; margin: 0px;">Cảm ơn quý khách '. $member['member_name'] .' đã gọi yêu cầu mua hàng online</h1>
                                                    <p style="margin: 4px 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px;">Citypost.com.vn rất vui thông báo đơn hàng của quý khách đã được tiếp nhận và đang trong quá trình xử lý.</p>
                                                    <h3 style="font-size: 13px; color: rgb(2, 172, 234); text-transform: uppercase; margin: 20px 0px 0px; border-bottom: 1px solid rgb(221, 221, 221);">THÔNG TIN ĐƠN HÀNG<span style="font-size: 12px; color: rgb(119, 119, 119); text-transform: none; font-weight: normal;"> (Ngày '. date('m', time()) .' Tháng '. date('d', time()) .' Năm '. date('Y', time()) .')</span></h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-family: Arial, Helvetica, sans-serif; margin: 0px; font-size: 12px; line-height: 18px;">
                                                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <thead>
                                                        <tr>
                                                            <th align="left" width="50%" style="padding: 6px 9px 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">Thông tin thanh toán</th>
                                                            <th align="left" width="50%" style="padding: 6px 9px 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">Địa chỉ giao hàng</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td valign="top" style="font-family: Arial, Helvetica, sans-serif; margin: 0px; padding: 3px 9px 9px; border-top: 0px; font-size: 12px; line-height: 18px;">
                                                                <span style="text-transform: capitalize;">'. $member['member_name'] .'</span><br>
                                                                <a href="mailto:'. $member['member_email'] .'" target="_blank" style="color: rgb(17, 85, 204);">'. $member['member_email'] .'</a><br>'. $member['member_phone'] .'
                                                            </td>
                                                            <td valign="top" style="font-family: Arial, Helvetica, sans-serif; margin: 0px; padding: 3px 9px 9px; border-top: 0px; border-left: 0px; font-size: 12px; line-height: 18px;">
                                                                <span style="text-transform: capitalize;">'. $member['member_name'] .'</span><br>
                                                                <a href="mailto:'. $member['member_email'] .'" target="_blank" style="color: rgb(17, 85, 204);">'. $member['member_email'] .'</a><br>
                                                                '. $member['member_address'] .'<br>ĐT: '. $member['member_phone'] .'
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" colspan="2" style="font-family: Arial, Helvetica, sans-serif; margin: 0px; padding: 7px 9px 0px; border-top: 0px; font-size: 12px;">
                                                                <p style="line-height: 18px;"><strong>Phương thức thanh toán:&nbsp;</strong>Trả tiền khi nhận hàng&nbsp;<br>
                                                                    <strong>Phí vận chuyển:&nbsp;</strong>0&nbsp;₫&nbsp;<br>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-family: arial, sans-serif; margin: 0px;">
                                                    <p style="margin: 4px 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px;"><i>Lưu ý: Với những đơn hàng thanh toán trả trước, xin vui lòng đảm bảo người nhận hàng đúng thông tin đã đăng ký trong đơn hàng, và chuẩn bị giấy tờ tùy thân để đơn vị giao nhận có thể xác thực thông tin khi giao hàng.</i></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-family: arial, sans-serif; margin: 0px;">
                                                    <h2 style="margin: 10px 0px; border-bottom: 1px solid rgb(221, 221, 221); padding-bottom: 5px; font-size: 13px; color: rgb(2, 172, 234);">CHI TIẾT ĐƠN HÀNG</h2>
                                                    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="background: rgb(245, 245, 245);">
                                                        <thead>
                                                        <tr>
                                                            <th align="left" bgcolor="#02acea" style="padding: 6px 9px; color: rgb(255, 255, 255); font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 14px;">Sản phẩm</th>
                                                            <th align="left" bgcolor="#02acea" style="padding: 6px 9px; color: rgb(255, 255, 255); font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 14px;">Đơn giá</th>
                                                            <th align="left" bgcolor="#02acea" style="padding: 6px 9px; color: rgb(255, 255, 255); font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 14px;">Số lượng</th>
                                                            <th align="left" bgcolor="#02acea" style="padding: 6px 9px; color: rgb(255, 255, 255); font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 14px;">Giảm giá</th>
                                                            <th align="right" bgcolor="#02acea" style="padding: 6px 9px; color: rgb(255, 255, 255); font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 14px;">Tổng tạm</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody bgcolor="#eee" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px;">
                                                        '. $items .'
                                                        </tbody>
                                                        <tfoot style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px;">
                                                        <tr>
                                                            <td colspan="4" align="right" style="font-family: arial, sans-serif; margin: 0px; padding: 5px 9px;">Tổng giá trị sản phẩm chưa giảm</td>
                                                            <td align="right" style="font-family: arial, sans-serif; margin: 0px; padding: 5px 9px;">'. adddotstring($total_price) .' &nbsp;₫</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" align="right" style="font-family: arial, sans-serif; margin: 0px; padding: 5px 9px;">Giảm giá</td>
                                                            <td align="right" style="font-family: arial, sans-serif; margin: 0px; padding: 5px 9px;">0&nbsp;₫</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" align="right" style="font-family: arial, sans-serif; margin: 0px; padding: 5px 9px;">Chi phí vận chuyển</td>
                                                            <td align="right" style="font-family: arial, sans-serif; margin: 0px; padding: 5px 9px;">0&nbsp;₫</td>
                                                        </tr>
                                                        <tr bgcolor="#eee">
                                                            <td colspan="4" align="right" style="font-family: arial, sans-serif; margin: 0px; padding: 7px 9px;"><strong><big>Tổng giá trị đơn hàng</big></strong></td>
                                                            <td align="right" style="font-family: arial, sans-serif; margin: 0px; padding: 7px 9px;"><strong><big>'. adddotstring($total_price) .' &nbsp;₫</big></strong></td>
                                                        </tr>
                                                        </tfoot>
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
                break;
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicon.ico">
    <title>IOT BUTTON</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
    <form class="form-signin" method="post" action="">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <h4>IOT BUTTON PRESS</h4>
                                    <?php
                                    if($error['info']){
                                        echo '<div class="text-center text-danger">'. $error['info'] .'</div>';
                                    }
                                    if($submit && !$error['info']){
                                        echo '<div class="text-center text-primary">PRESS SUCCESS <a href="edit.php?device_code='. $device_code .'&device_token='. $device['device_token'] .'" class="btn btn-round btn-primary">UPDATE INFO</a> </div>';
                                    }

                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%" class="text-right">Mã Seri</td>
                                <td width="70%" class="text-left"><input class="form-control" type="text" name="device_code" value="<?php echo $device_code;?>"></td>
                            </tr>
                            <tr>
                                <td width="30%" class="text-right">Kiểu bấm</td>
                                <td width="70%" class="text-left">
                                    <label><input type="radio" name="device_type" value="1" <?php echo $device_type == 1 ? 'checked="checked"' : ''?>> SINGLE </label> |
                                    <label><input type="radio" name="device_type" value="2" <?php echo $device_type == 2 ? 'checked="checked"' : ''?>> DOUBLE </label> |
                                    <label><input type="radio" name="device_type" value="3" <?php echo $device_type == 3 ? 'checked="checked"' : ''?>> LONG </label> |
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <input type="submit" class="btn btn-round btn-facebook btn-primary" name="submit" value="PRESS">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
    <?php
        if($content){
            echo '<hr />'.$content;
        }
    ?>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</html>
