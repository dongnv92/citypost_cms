<?php
/**
 * Created by PhpStorm.
 * User: nguye
 * Date: 02/03/2018
 * Time: 20:33
 */
require_once 'includes/core.php';

$admin_title = $header['title'] ? $header['title'] : 'Trang chủ';
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
    <title><?php echo $admin_title;?></title>
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
    <?php
    foreach ($css_plus AS $css){
        echo '<link rel="stylesheet" type="text/css" href="'. $css .'">'."\n";
    }
    ?>
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
                    <a class="navbar-brand" href="<?php echo _HOME;?>">
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
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <span class="mr-1">Hi,<span class="user-name text-bold-700"><?php echo $user['users_name']?></span></span>
                            <span class="avatar avatar-online"><img src="images/avatar.png" alt="avatar"><i></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo _LOGOUT;?>"><i class="ft-power"></i> Thoát</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item <?php echo ($module == 'email' && in_array($act, array('send'))) ? 'active' : ''?>"><a href="<?php echo _HOME?>/email.php?act=send"><i class="la la-send"></i><span class="menu-title">Gửi Email Hàng Loạt</span></a></li>
            <li class=" nav-item <?php echo ($module == 'email' && in_array($act, array('add'))) ? 'active' : ''?>"><a href="<?php echo _HOME.'/email.php?act=add'?>"><i class="ft-plus"></i><span class="menu-title">Thêm một Email</span></a></li>
            <li class=" nav-item <?php echo ($module == 'email' && in_array($act, array('add_list'))) ? 'active' : ''?>"><a href="<?php echo _HOME.'/email.php?act=add_list'?>"><i class="ft-zap"></i><span class="menu-title">Thêm nhiều Email</span></a></li>
            <li class=" nav-item <?php echo ($module == 'import' && in_array($act, array(''))) ? 'active' : ''?>"><a href="<?php echo _HOME.'/import.php'?>"><i class="ft-upload"></i><span class="menu-title">Import Email</span></a></li>
            <li class=" nav-item <?php echo ($module == 'email' && in_array($act, array('statics'))) ? 'active' : ''?>"><a href="<?php echo _HOME.'/email.php?act=statics'?>"><i class="la la-pagelines"></i><span class="menu-title">Xem Thống Kê</span></a></li>
            <li class=" nav-item"><a href="<?php echo _LOGOUT;?>"><i class="la la-text-height"></i><span class="menu-title">Đăng xuất</span></a></li>
        </ul>
    </div>
</div>
<div class="app-content content">
    <div class="content-wrapper">
