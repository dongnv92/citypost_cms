<?php
    require_once 'includes/core.php';
    $admin_title = isset($admin_title) ? $admin_title : 'CITYPOST - Chuyển phát nhanh hỏa tốc';
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Modern admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities with bitcoin dashboard.">
    <meta name="keywords" content="admin template, modern admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
    <meta name="author" content="PIXINVENT">
    <title><?php echo $admin_title;?></title>
    <link rel="apple-touch-icon" href="app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="images/star.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700"
          rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/vendors.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/wizard.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN MODERN CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/app.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/tables/datatable/datatables.min.css">
    <!-- END MODERN CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/cryptocoins/cryptocoins.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/charts/morris.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="app-assets/vendors/js/charts/raphael-min.js" type="text/javascript"></script>
    <script src="app-assets/vendors/js/charts/morris.min.js" type="text/javascript"></script>

    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END Custom CSS-->
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
                    <a class="navbar-brand" href="<?php echo _URL_HOME;?>">
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
                    <li class="nav-item nav-search"><a class="nav-link nav-link-search" href="#"><i class="ficon ft-search"></i></a>
                        <div class="search-input">
                            <input class="input" type="text" placeholder="Tìm kiếm ...">
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                <span class="mr-1">Hi,
                  <span class="user-name text-bold-700"><?php echo $data_user['fullname'];?></span>
                </span>
                            <span class="avatar avatar-online">
                  <img src="images/user.png" alt="avatar"><i></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#"><i class="ft-user"></i> Edit Profile</a>
                            <a class="dropdown-item" href="#"><i class="ft-mail"></i> My Inbox</a>
                            <a class="dropdown-item" href="#"><i class="ft-check-square"></i> Task</a>
                            <a class="dropdown-item" href="#"><i class="ft-message-square"></i> Chats</a>
                            <div class="dropdown-divider"></div><a class="dropdown-item" href="#"><i class="ft-power"></i> Logout</a>
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
            <li class=" navigation-header">
                <span data-i18n="nav.category.layouts">Device</span><i class="la la-ellipsis-h ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Layouts"></i>
            </li>
            <li class=" nav-item">
                <a href="<?php echo _URL_HOME.'/transactions.php'?>"><i class="ft-activity"></i>
                    <span class="menu-title">Theo dõi hoạt động</span>
                </a>
            </li>
            <li class=" nav-item">
                <a href="#"><i class="ft-speaker"></i><span class="menu-title" data-i18n="nav.page_layouts.main">Quản lý thiết bị</span></a>
                <ul class="menu-content">
                    <li <?php echo ($admin_active == 'device' && in_array($act, array('update','delete',''))) ? 'class="active"' : ''; ?>><a class="menu-item" href="<?php echo _URL_ADMIN;?>/device.php">Danh sách thiết bị</a></li>
                    <li <?php echo ($admin_active == 'device' && in_array($act, array('active','add_customer','update_config'))) ? 'class="active"' : ''; ?>><a class="menu-item" href="<?php echo _URL_ADMIN;?>/device.php?act=active">Thiết bị đã kich hoạt</a></li>
                    <li <?php echo ($admin_active == 'device' && in_array($act, array('realtime'))) ? 'class="active"' : ''; ?>><a class="menu-item" href="<?php echo _URL_ADMIN;?>/device.php?act=realtime">Thiết bị đang hoạt động</a></li>
                    <li <?php echo ($admin_active == 'device' && in_array($act, array('add'))) ? 'class="active"' : ''; ?>><a class="menu-item" href="<?php echo _URL_ADMIN;?>/device.php?act=add">Thêm mới</a></li>
                </ul>
            </li>
            <li class=" nav-item">
                <a href="#"><i class="ft-heart"></i><span class="menu-title" data-i18n="nav.page_layouts.main">Quản lý khách hàng</span></a>
                <ul class="menu-content">
                    <li <?php echo ($admin_active == 'customer' && in_array($act, array('update','delete',''))) ? 'class="active"' : ''; ?>><a class="menu-item" href="<?php echo _URL_ADMIN;?>/customer.php">Danh sách khách hàng</a></li>
                    <li <?php echo ($admin_active == 'customer' && in_array($act, array('add'))) ? 'class="active"' : ''; ?>><a class="menu-item" href="<?php echo _URL_ADMIN;?>/customer.php?act=add">Thêm mới</a></li>
                </ul>
            </li>
            <li class=" nav-item">
                <a href="#"><i class="ft-users"></i><span class="menu-title" data-i18n="nav.page_layouts.main">Quản lý thành viên</span></a>
                <ul class="menu-content">
                    <li <?php echo ($admin_active == 'users' && in_array($act, array('update','delete',''))) ? 'class="active"' : ''; ?>><a class="menu-item" href="<?php echo _URL_ADMIN;?>/users.php">Danh sách thành viên</a></li>
                    <li <?php echo ($admin_active == 'users' && in_array($act, array('add'))) ? 'class="active"' : ''; ?>><a class="menu-item" href="<?php echo _URL_ADMIN;?>/users.php?act=add">Thêm thành viên</a></li>
                </ul>
            </li>
            <li class=" nav-item">
                <a href="<?php echo _URL_HOME.'/logout.php'?>"><i class="la la-text-height"></i>
                    <span class="menu-title">Đăng xuất</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <h2 class="content-header-title">
                    <?php
                    switch ($admin_active){
                        case 'customer':
                            switch ($act){
                                case 'add':
                                    echo 'Thêm khách hàng';
                                    break;
                                case 'update':
                                    echo 'Cập nhập khách hàng';
                                    break;
                                case 'delete':
                                    echo 'Xóa khách hàng';
                                    break;
                                default:
                                    echo '<a href="'._URL_ADMIN.'/customer.php">Danh sách khách hàng</a> <a href="'._URL_ADMIN.'/customer.php?act=add" class="btn btn-outline-success round btn-min-width mr-1 mb-1">Thêm khách hàng</a>';
                                    break;
                            }
                            break;
                        case 'device':
                            switch ($act){
                                case 'add':
                                    echo 'Thêm thiết bị';
                                    break;
                                case 'update':
                                    echo 'Cập nhập thiết bị';
                                    break;
                                case 'delete':
                                    echo 'Xóa thiết bị';
                                    break;
                                default:
                                    echo 'Danh sách thiết bị <a href="'._URL_ADMIN.'/device.php?act=add" class="btn btn-outline-success round btn-min-width mr-1 mb-1">Thêm thiết bị</a>';
                                    break;
                            }
                            break;
                        case 'users':
                            switch ($act){
                                case 'add':
                                    echo 'Thêm thành viên';
                                    break;
                                case 'update':
                                    echo 'Cập nhập thành viên';
                                    break;
                                case 'delete':
                                    echo 'Xóa thành viên';
                                    break;
                                default:
                                    echo 'Danh sách thành viên <a href="'._URL_ADMIN.'/users.php?act=add" class="btn btn-outline-success round btn-min-width mr-1 mb-1">Thêm thành viên</a>';
                                    break;
                            }
                            break;
                        case 'transactions':
                            switch ($act){
                                case 'detail':
                                    echo $admin_title;
                                    break;
                                default:
                                    echo 'Quản lý giao dịch';
                                    break;
                            }
                            break;
                        default:
                            echo 'CITYPOST - Chuyển phát nhanh hỏa tốc';
                            break;
                    }
                    ?>
                </h2>
            </div>
            <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo _URL_ADMIN;?>">Trang chủ</a></li>
                        <?php
                        switch ($admin_active){
                            case 'customer':
                                switch ($act){
                                    case 'add':
                                        echo '<li class="breadcrumb-item active"><a href="'. _URL_ADMIN .'/customer.php">Quản lý khách hàng</a> </li>';
                                        echo '<li class="breadcrumb-item active"> Thêm khách hàng</li>';
                                        break;
                                    case 'update':
                                        echo '<li class="breadcrumb-item active"><a href="'. _URL_ADMIN .'/customer.php">Quản lý khách hàng</a> </li>';
                                        echo '<li class="breadcrumb-item active"> Cập nhập khách hàng</li>';
                                        break;
                                    case 'delete':
                                        echo '<li class="breadcrumb-item active"><a href="'. _URL_ADMIN .'/customer.php">Quản lý khách hàng</a> </li>';
                                        echo '<li class="breadcrumb-item active"> Xóa khách hàng</li>';
                                        break;
                                    default:
                                        echo '<li class="breadcrumb-item active"> Danh sách khách hàng</li>';
                                        break;
                                }
                                break;
                            case 'device':
                                switch ($act){
                                    case 'add':
                                        echo '<li class="breadcrumb-item active"><a href="'. _URL_ADMIN .'/device.php">Quản lý thiết bị</a> </li>';
                                        echo '<li class="breadcrumb-item active"> Thêm thiết bị</li>';
                                        break;
                                    case 'update':
                                        echo '<li class="breadcrumb-item active"><a href="'. _URL_ADMIN .'/device.php">Quản lý Quản lý thiết bị</a> </li>';
                                        echo '<li class="breadcrumb-item active"> Cập nhập thiết bị</li>';
                                        break;
                                    case 'delete':
                                        echo '<li class="breadcrumb-item active"><a href="'. _URL_ADMIN .'/device.php">Quản lý Quản lý thiết bị</a> </li>';
                                        echo '<li class="breadcrumb-item active"> Xóa thiết bị</li>';
                                        break;
                                    default:
                                        echo '<li class="breadcrumb-item active"> Danh sách thiết bị</li>';
                                        break;
                                }
                                break;
                            case 'users':
                                switch ($act){
                                    case 'add':
                                        echo '<li class="breadcrumb-item"><a href="'. _URL_ADMIN .'/users.php">Quản lý thành viên</a> </li>';
                                        echo '<li class="breadcrumb-item active"> Thêm thành viên</li>';
                                        break;
                                    case 'update':
                                        echo '<li class="breadcrumb-item"><a href="'. _URL_ADMIN .'/users.php">Quản lý thành viên</a> </li>';
                                        echo '<li class="breadcrumb-item active"> Cập nhập thành viên</li>';
                                        break;
                                    case 'delete':
                                        echo '<li class="breadcrumb-item"><a href="'. _URL_ADMIN .'/users.php">Quản lý thành viên</a> </li>';
                                        echo '<li class="breadcrumb-item active"> Xóa thành viên</li>';
                                        break;
                                    default:
                                        echo '<li class="breadcrumb-item active"> Danh sách thành viên</li>';
                                        break;
                                }
                                break;
                            case 'transactions':
                                switch ($act){
                                    case 'detail':
                                        echo '<li class="breadcrumb-item"><a href="'. _URL_ADMIN .'/transactions.php">Quản lý giao dịch</a> </li>';
                                        echo '<li class="breadcrumb-item active"> Chi tiết giao dịch</li>';
                                        break;
                                    default:
                                        echo '<li class="breadcrumb-item active"> Quản lý giao dịch</li>';
                                        break;
                                }
                                break;
                            default:
                                echo '<li class="breadcrumb-item active"> Thống kê</li>';
                               break;
                        }
                        ?>
                    </ol>
                </div>
            </div>
        </div>