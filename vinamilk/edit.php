<?php
    require_once 'includes/core.php';

    $device_code    = (isset($_GET['device_code'])     && !empty($_GET['device_code']))       ? $_GET['device_code']     : '';
    $device_token   = (isset($_GET['device_token'])    && !empty($_GET['device_token']))      ? $_GET['device_token']    : '';

    $error          = array();
    if(checkGlobal('dong_device', array('device_code' => $device_code, 'device_token' => $device_token)) == 0){
        $error['check_login'] = 'Thiết bị không tồn tại trên hệ thống<br />';
    }

    $member         = getGlobal('dong_member', array('member_device' => $device_code, 'member_status' => 1));
    $device_fullname= $member['member_name'];
    $device_address = $member['member_address'];
    $device_phone   = $member['member_phone'];
    $device_email   = $member['member_email'];

    if($submit){
        $device_fullname= (isset($_POST['device_fullname']) && !empty($_POST['device_fullname']))   ? $_POST['device_fullname'] : $device_fullname;
        $device_address = (isset($_POST['device_address'])  && !empty($_POST['device_address']))    ? $_POST['device_address']  : $device_address;
        $device_phone   = (isset($_POST['device_phone'])    && !empty($_POST['device_phone']))      ? $_POST['device_phone']    : $device_phone;
        $device_email   = (isset($_POST['device_email'])    && !empty($_POST['device_email']))      ? $_POST['device_email']    : $device_email;

        $data_update = array(
            'member_name'       => $device_fullname,
            'member_address'    => $device_address,
            'member_email'      => $device_email,
            'member_phone'      => $device_phone
        );
        // Sửa thông tin thành viên
        if(!updateGlobal('dong_member', $data_update, array('member_device' => $device_code))){
            echo json_decode(array('error' => "Can't Update Member"));
            exit();
        }

        // Xóa dữ liệu các sản phẩm cũ để thêm dữ liệu mới.
        if(!deleteGlobal('dong_cart', array('cart_member' => $member['id'], 'cart_device' => $device_code))){
          echo json_decode(array('error' => "Can't Delete Cart Old"));
          exit();
        }

        // Thêm dữ liệu vào bảng cart
        foreach (getGlobalAll('dong_product') AS $product){
            if($_POST['device_product_'.$product['id']] > 0){
                $data = array(
                    'cart_device'           => $device_code,
                    'cart_member'           => $member['id'],
                    'cart_product_id'       => $product['id'],
                    'cart_product_name'     => $product['product_name'],
                    'cart_product_price'    => $product['product_price'],
                    'cart_product_amount'   => $_POST['device_product_'.$product['id']],
                    'cart_time'             => time()
                );
                if(!insertGlobal('dong_cart', $data)){
                    echo json_decode(array('error' => "Can't Insert Cart Items"));
                    exit();
                }
            }
        }
        // Thêm dữ liệu vào bảng cart

    }
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicon.ico">

	<title>Đăng ký thông tin thiết bị</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
	<link rel="icon" type="image/png" href="assets/img/favicon.png" />

	<!--     Fonts and icons     -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />

	<!-- CSS Files -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/material-bootstrap-wizard.css" rel="stylesheet" />

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link href="assets/css/demo.css" rel="stylesheet" />
    <script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script src="assets/js/bootstrap-input-spinner.js" type="text/javascript"></script>
    <script>
        $("input[type='number']").inputSpinner()
    </script>
    <script language="JavaScript">
        $(document).ready(function(){
            $(".upercase").on('change keyup paste',function(){
                $(this).val($(this).val().toUpperCase());
            })
        })
    </script>

</head>

<body>
	<div class="image-container set-full-height" style="background-image: url('assets/img/bg.jpg')">
	    <!--   Creative Tim Branding   -->
	    <!--<a href="http://creative-tim.com">
	         <div class="logo-container">
	            <div class="logo">
	                <img src="assets/img/new_logo.png">
	            </div>
	            <div class="brand">
	                Creative Tim
	            </div>
	        </div>
	    </a>-->

		<!--  Made With Material Kit  -->
		<a href="http://citypost.com.vn" class="made-with-mk">
			<div class="brand">CP</div>
			<div class="made-with"><strong>CITYPOST.COM.VN</strong></div>
		</a>

	    <!--   Big container   -->
	    <div class="container">
	        <div class="row">
		        <div class="col-sm-8 col-sm-offset-2">
		            <!--      Wizard container        -->
		            <div class="wizard-container">
		                <div class="card wizard-card" data-color="blue" id="wizardProfile">
                            <form action="" method="post">
		                        <!--        You can switch " data-color="purple" "  with one of the next bright colors: "green", "orange", "red", "blue"       -->
		                    	<div class="wizard-header">
		                        	<h3 class="wizard-title">
		                        	   CITYPOST.COM.VN
		                        	</h3>
									<h5>Chuyển phát nhanh hỏa tốc.</h5>
		                    	</div>
								<div class="wizard-navigation">
                  <ul>
                    <li><a href="#account" data-toggle="tab">Thông tin</a></li>
			              <li><a href="#address" data-toggle="tab">Đơn hàng</a></li>
			            <ul>
								</div>

		                        <div class="tab-content">
		                            <div class="tab-pane" id="account">
		                                <h4 class="info-text"> Nhập thông tin của khách hàng </h4>
		                                <div class="row">
                                            <!-- Thông báo -->
                                            <div class="text-center text-danger">
                                                <?php
                                                if($error['check_login']){
                                                    echo $error['check_login'].'<br />';
                                                    echo '</div></div></div></div></div>';
                                                    echo '<div class="footer">
	                                                <div class="container text-center">
	                                                Create <i class="fa fa-heart heart"></i> by <a href="http://citypost.com.vn">CITYPOST</a>. Chuyển phát nhanh hỏa tốc</a></div></div>';
                                                    echo '</body>
                                                    <!--   Core JS Files   -->
                                                    <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
                                                    <script src="assets/js/jquery.bootstrap.js" type="text/javascript"></script>

                                                    <!--  Plugin for the Wizard -->
                                                    <script src="assets/js/material-bootstrap-wizard.js"></script>

                                                    <!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
                                                    <script src="assets/js/jquery.validate.min.js"></script>
                                                    </html>';
                                                    exit();
                                                }

                                                if(!$error && $submit){
                                                    echo 'Update thông tin thành công';
                                                }

                                                ?>
                                            </div>
                                            <!-- Thông báo -->
                                            <div class="col-sm-10 col-sm-offset-1">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="material-icons">person</i></span>
                                                    <div class="form-group label-floating">
                                                        <label class="control-label">Họ và tên</label>
                                                        <input name="device_fullname" required  value="<?php echo $device_fullname;?>" type="text" class="lowcase form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-10 col-sm-offset-1">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="material-icons">location_on</i></span>
                                                    <div class="form-group label-floating">
                                                        <label class="control-label">Địa chỉ</label>
                                                        <input name="device_address" required type="text"  value="<?php echo $device_address;?>" class="lowcase form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-10 col-sm-offset-1">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="material-icons">call</i></span>
                                                    <div class="form-group label-floating">
                                                        <label class="control-label">Số điện thoại</label>
                                                        <input name="device_phone" required type="text"  value="<?php echo $device_phone;?>" class="lowcase form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-10 col-sm-offset-1">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="material-icons">email</i></span>
                                                    <div class="form-group label-floating">
                                                        <label class="control-label">Email</label>
                                                        <input name="device_email" required type="text" value="<?php echo $device_email;?>" class="lowcase form-control">
                                                    </div>
                                                </div>
                                            </div>
		                                </div>
		                            </div>
		                            <div class="tab-pane" id="address">
		                                <div class="row">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <th width="40%" class="text-center">Tên hàng</th>
                                                        <th width="30%" class="text-center">Đơn giá</th>
                                                        <th width="30%" class="text-center">Số lượng</th>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach (getGlobalAll('dong_cart', array('cart_member' => $member['id'], 'cart_device' => $device_code)) AS $products){
                                                              $product = getGlobal('dong_product', array('id' => $products['cart_product_id']));
                                                            ?>
                                                                <tr>
                                                                    <td class="text-center"><?php echo $product['product_name'];?></td>
                                                                    <td class="text-center"><?php echo adddotstring($product['product_price']);?> đ</td>
                                                                    <td class="text-center">
                                                                      <input style="width: 60px; text-align: center" type="number" name="device_product_<?php echo $product['id'];?>" value="<?php echo $products['cart_product_amount'];?>" min="0" max="100" step="1"/>
                                                                    </td>
                                                                </tr>
                                                            <?php

                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="wizard-footer">
		                            <div class="pull-right">
		                                <input type='button' class='btn btn-next btn-fill btn-success btn-wd' name='next' value='Bước tiếp theo' />
		                                <input type='submit' class='btn btn-finish btn-fill btn-success btn-wd' name='submit' value='Cập nhập thông tin' />
		                            </div>

		                            <div class="pull-left">
		                                <input type='button' class='btn btn-previous btn-fill btn-default btn-wd' name='previous' value='Quay lại' />
		                            </div>
		                            <div class="clearfix"></div>
		                        </div>
		                    </form>
		                </div>
		            </div> <!-- wizard container -->
		        </div>
	        </div><!-- end row -->
	    </div> <!--  big container -->

	    <div class="footer">
	        <div class="container text-center">
	             Create <i class="fa fa-heart heart"></i> by <a href="http://citypost.com.vn">CITYPOST</a>. Chuyển phát nhanh hỏa tốc</a>
	        </div>
	    </div>
	</div>

</body>
	<!--   Core JS Files   -->
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="assets/js/jquery.bootstrap.js" type="text/javascript"></script>

	<!--  Plugin for the Wizard -->
	<script src="assets/js/material-bootstrap-wizard.js"></script>

    <!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
	<script src="assets/js/jquery.validate.min.js"></script>
</html>
