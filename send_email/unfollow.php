<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 2018-11-12
 * Time: 16:23
 */
require_once 'includes/core.php';
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Theo dõi định vị xe của công ty cổ phần bưu chính thành phố">
    <meta name="keywords" content="admin template, modern admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
    <meta name="author" content="PIXINVENT">
    <title>Hủy Nhận Tin Mới</title>
    <link rel="apple-touch-icon" href="app-assets/images/ico/apple-icon-120.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700" rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/vendors.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN MODERN CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/app.css">
    <!-- END MODERN CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/extensions/toastr.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/extensions/toastr.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/extensions/sweetalert.css">
</head>
<body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar"
      data-open="click" data-menu="vertical-menu" data-col="2-columns">
<!-- fixed-top-->
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light bg-info navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item">
                    <a class="navbar-brand" href="http://citypost.com.vn">
                        <img class="brand-logo" alt="modern admin logo" src="images/logocitypost.png">
                        <h3 class="brand-text">CITYPOST</h3>
                    </a>
                </li>
                <li class="nav-item d-md-none">
                    <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a>
                </li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
                    <li class="nav-item nav-search"><a class="nav-link nav-link-search" href="#">CÔNG TY CỔ PHẦN BƯU CHÍNH THÀNH PHỐ</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- ////////////////////////////////////////////////////////////////////////////-->
<br /><br />
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h4 class="card-title"></h4>Hủy Nhận Tin Mới</div>
            <div class="card-body text-center">
                <?php
                if($submit){
                    getApi('update_unfollow', array('email' => $_GET['email']));
                    echo 'Bạn Đã Hủy Nhận Tin Thành Công.';
                }else{
                    $api = getApi('get_detail_by_email', array('email' => $_GET['email']));
                    if($api['response'] != 200 || $api['list_unsubcrice'] == 1){
                        echo 'Email không có trong danh sách hoặc đã được hủy nhận tin';
                    }else {
                        ?>
                        <form action="" method="post">
                            Bạn có chắc chắn muốn hủy nhận tin không?
                            <hr>
                            <input type="submit" id="btn_cance" name="submit" class="btn btn-outline-blue round" value="Hủy Nhận Tin">
                            <button class="btn btn-outline-danger round">BỎ QUA</button>
                        </form>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->
<!-- BEGIN PAGE VENDOR JS-->
<!--<script src="http://code.jquery.com/jquery-latest.min.js"></script>-->
<script src="app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<!-- BEGIN MODERN JS-->
<script src="app-assets/js/core/app-menu.js" type="text/javascript"></script>
<script src="app-assets/js/core/app.js" type="text/javascript"></script>
<script src="app-assets/vendors/js/extensions/toastr.min.js" type="text/javascript"></script>
<script src="app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
<script src="app-assets/js/scripts/extensions/sweet-alerts.min.js" type="text/javascript"></script>

</body>
</html>
