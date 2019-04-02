<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 2019-04-02
 * Time: 15:09
 */
error_reporting(0);
//define('_CLOUDFONE_HOME', 'http://112.78.11.14/cloudfone');
define('_CLOUDFONE_HOME', 'http://localhost/dong/citypost_cms/cloudfone');
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
    <title>Xem lịch sử cuộc gọi</title>
    <link rel="apple-touch-icon" href="../app-assets/images/ico/apple-icon-120.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700" rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/css/vendors.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN MODERN CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/css/app.css">
    <!-- END MODERN CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/core/colors/palette-gradient.css">
    <?php
    $css_plus = array(
        'app-assets/vendors/css/pickers/daterange/daterangepicker.css',
        'app-assets/vendors/css/pickers/pickadate/pickadate.css',
        'app-assets/css/plugins/pickers/daterange/daterange.min.css',
        'app-assets/css/chosen.css'
    );

    $js_plus = array(
        'app-assets/vendors/js/pickers/pickadate/picker.js',
        'app-assets/vendors/js/pickers/pickadate/picker.date.js',
        'app-assets/vendors/js/pickers/pickadate/picker.time.js',
        'app-assets/vendors/js/pickers/pickadate/legacy.js',
        'app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js',
        'app-assets/vendors/js/pickers/daterange/daterangepicker.js',
        'app-assets/js/scripts/ui/jquery-ui/autocomplete.js',
        'app-assets/js/chosen.jquery.js',
        'app-assets/js/prism.js',
        'app-assets/js/init.js',
    );
    foreach ($css_plus AS $css){
        echo '<link rel="stylesheet" type="text/css" href="../'. $css .'">'."\n";
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
                    <a class="navbar-brand" href="<?=_CLOUDFONE_HOME?>">
                        <img class="brand-logo" alt="modern admin logo" src="../images/logocitypost.png">
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
                            <span class="avatar avatar-online"><img src="../images/user.png" width="50px" alt="avatar"><i></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?=_CLOUDFONE_HOME?>"><i class="ft-power"></i> Thoát</a>
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
            <li class=" nav-item"><a href="<?php echo _CLOUDFONE_HOME?>"><i class="la la-phone-square"></i><span class="menu-title">Xem thống kê cuộc gọi</span></a></li>
        </ul>
    </div>
</div>
<div class="app-content content">
    <div class="content-wrapper">
        <!-- Content -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col">
                                    <label>Số gọi <small><i>(Có thể để trống)</i></small></label>
                                    <fieldset class="form-group">
                                        <input type="text" name="" value="" class="form-control round" placeholder="Nhập số gọi" />
                                    </fieldset>
                                </div>
                                <div class="col">
                                    <label>Thời gian bắt đầu</label>
                                    <fieldset class="form-group">
                                        <input type="text" name="time_start" value="<?php echo $time_start ? $time_start : date('Y-m-d', time());?>" class="form-control round pickadate" placeholder="Thời Gian Bắt Đầu" />
                                    </fieldset>
                                </div>
                                <div class="col">
                                    <label>Thời gian kết thúc</label>
                                    <fieldset class="form-group">
                                        <input type="text" name="time_stop" value="<?php echo $time_stop ? $time_stop : date('Y-m-d', time());?>" class="form-control round pickadate" placeholder="Thời Gian Kết Thúc" />
                                    </fieldset>
                                </div>
                                <div class="col text-right">
                                    <br />
                                    <fieldset class="form-group">
                                        <input type="submit" name="submit" value="Tìm Kiếm" class="btn btn-outline-blue round">
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="position-relative has-icon-left">
                                        <textarea id="timesheetinput7" rows="8" class="form-control" name="notes" placeholder="Nhập danh sách số điện thoại nhận, mỗi số điện thoại là 1 dòng"></textarea>
                                        <div class="form-control-position">
                                            <i class="ft-phone-call"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php echo $result;?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content -->
    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->
<footer class="footer footer-static footer-light navbar-border navbar-shadow">
    <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
        <span class="float-md-left d-block d-md-inline-block">Copyright &copy; 21019 <a class="text-bold-800 grey darken-2" href="http://citypost.com.vn" target="_blank">CITYPOST.COM.VN </a> Công Ty Cổ Phần Bưu Chính Thành Phố</span>
    </p>
</footer>
<!-- BEGIN PAGE VENDOR JS-->
<!--<script src="http://code.jquery.com/jquery-latest.min.js"></script>-->
<script src="../app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<!-- BEGIN MODERN JS-->
<script src="../app-assets/js/core/app-menu.js" type="text/javascript"></script>
<script src="../app-assets/js/core/app.js" type="text/javascript"></script>
<!-- PLUS -->
<script>tinymce.init({ selector:'textarea' });</script>
<!-- PLUS -->
<?php
foreach ($js_plus AS $js){
    echo '<script src="../'. $js .'" type="text/javascript"></script>'."\n";
}
?>
<script>
    $(document).ready(function () {
        $('.pickadate').pickadate({
            format: 'yyyy-mm-dd',
            hiddenPrefix: 'prefix__',
            hiddenSuffix: '__suffix'
        });


    });
</script>
</body>
</html>
