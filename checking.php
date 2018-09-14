<?php
/**
 * Created by PhpStorm.
 * User: Dong
 * Date: 05/09/2018
 * Time: 09:09
 */
    require_once 'includes/core.php';
    $device             = $_GET['device'];
    $button_1           = getGlobalAll(_DB_TABLE_TRANSACTIONS, array('deviceID' => "$device"), array('onecolum' => 'limit', 'select' => ' TOP 1 * ', 'order_by' => 'timeReq', 'order_by_soft' => 'DESC'));
    $transID            = $button_1['transID'];
    $transactions       = getGlobalAll(_DB_TABLE_TRANSACTIONS, array('transID' => $transID), array('onecolum' => 'limit'));
    $transactions_his   = getGlobalAll('tblTransactionHistory', array('transID' => $transID, 'status' => 1), array('onecolum' => 'limit'));
    $post_man           = getGlobalAll('tblPostMan', array('postManID' => $transactions_his['postmanID']), array('onecolum' => 'limit'));
    $deviceID           = getGlobalAll(_DB_TABLE_DEVICE, array('deviceID' => $transactions['deviceID']), array('onecolum' => 'limit'));
    $cusID              = getGlobalAll(_DB_TABLE_CUSTOMER, array('cusID' => $transactions['cusID']), array('onecolum' => 'limit'));
    $device             = getDeveloperInfo();
    $tab                = array();

    if($transactions['status_02'] == 0 && $transactions['status_03'] == 0 && $transactions['status_04'] != 104){ // Nếu trường status 02, 03, 04 bằng 0
    $tab['class_1'] = 'first done current';
    $tab['class_2'] = 'disabled';
    $tab['class_3'] = 'disabled';
    $tab['class_4'] = 'disabled last';
    $tab['text_1']  = getViewTime($transactions['timeReq']);
    $tab['text_2']  = '';
    $tab['text_3']  = '';
    $tab['text_4']  = '';
    }else if($transactions['status_02'] != 0 && $transactions['status_03'] == 0 && $transactions['status_04'] != 104){ // Nếu trường status 02 khác 0 và 03, 04 bằng 0
    $tab['class_1'] = 'first done';
    $tab['class_2'] = 'current';
    $tab['class_3'] = 'disabled';
    $tab['class_4'] = 'disabled last';
    $tab['text_1']  = getViewTime($transactions['timeReq']);
    $tab['text_2']  = getViewTime($transactions['time_02']);
    $tab['text_3']  = '';
    $tab['text_4']  = '';
    }else if($transactions['status_02'] != 0 && $transactions['status_03'] != 0 && $transactions['status_04'] != 104){ // Nếu trường status 02,03 khác 0 và 04 bằng 0
    $tab['class_1'] = 'first done';
    $tab['class_2'] = 'done';
    $tab['class_3'] = 'current';
    $tab['class_4'] = 'disabled last';
    $tab['text_1']  = getViewTime($transactions['timeReq']);
    $tab['text_2']  = getViewTime($transactions['time_02']);
    $tab['text_3']  = getViewTime($transactions['time_03']);
    $tab['text_4']  = '';
    }else if($transactions['status_02'] != 0 && $transactions['status_03'] != 0 && $transactions['status_04'] == 104){ // Nếu trường status 02,03,04 khác 0
    $tab['class_1'] = 'first done';
    $tab['class_2'] = 'done';
    $tab['class_3'] = 'done';
    $tab['class_4'] = 'done current last';
    $tab['text_1']  = getViewTime($transactions['timeReq']);
    $tab['text_2']  = getViewTime($transactions['time_02']);
    $tab['text_3']  = getViewTime($transactions['time_03']);
    $tab['text_4']  = getViewTime($transactions['time_04']);
    }
    if($transactions['status_04'] == 301){
    $tab['text_04'] = '<strong class="danger">Khách hàng đã bấm hủy</strong>';
    $tab['text_4']  = getViewTime($transactions['time_04']);
    }
    else if($transactions['status_04'] == 106){
    $tab['text_04'] = '<strong class="danger">Điều hành hủy tin</strong>';
    $tab['text_4']  = getViewTime($transactions['time_04']);
    }
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
    <title>Kiểm tra đơn hàng</title>
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
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/ui/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/ui/jqueryui.css">
    <!-- END MODERN CSS-->
    <!-- BEGIN Page Level CSS-->
    <script src="app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/cryptocoins/cryptocoins.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/charts/morris.css">
    <script src="app-assets/vendors/js/charts/raphael-min.js" type="text/javascript"></script>
    <script src="app-assets/vendors/js/charts/morris.min.js" type="text/javascript"></script>
    <script src="jquery/sweetalert.min.js" type="text/javascript"></script>
    <!-- END Page Level CSS-->
    <style>
        #map {
            height: 100%;
            float: left;
            width: 70%;
            height: 100%;
        }
    </style>
    <!-- BEGIN Custom CSS-->

</head>
<body class="vertical-layout  menu-expanded fixed-navbar">
<div class="app-content content">
    <div class="content-wrapper">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header"><h4 class="card-title"><?php echo $admin_title;?></h4> </div>
                <div class="card-body">
                    <!-- STEP -->
                    <div class="icons-tab-steps wizard-circle wizard clearfix" role="application">
                        <div class="steps clearfix">
                            <ul role="tablist">
                                <li role="tab" class="<?php echo $tab['class_1'];?>" aria-disabled="false" aria-selected="false">
                                    <a id="steps-uid-2-t-0" href="javascript:;" aria-controls="steps-uid-2-p-0">
                                        <span class="step"><i class="la la-hand-pointer-o"></i></span>  Khách hàng bấm<hr /><?php echo $tab['text_1'];?>
                                    </a>
                                </li>
                                <li role="tab" class="<?php echo $tab['class_2'];?>" aria-disabled="false" aria-selected="true">
                                    <a id="steps-uid-2-t-1" href="javascript:;" aria-controls="steps-uid-2-p-1">
                                        <span class="current-info audible">current step: </span>
                                        <span class="step"><i class="step-icon la la-edit"></i></span> Điều hành tiếp nhận<hr /><?php echo $tab['text_2'];?>
                                    </a>
                                </li>
                                <li role="tab" class="<?php echo $tab['class_3'];?>" aria-disabled="true">
                                    <a id="steps-uid-2-t-2" href="javascript:;" aria-controls="steps-uid-2-p-2">
                                        <span class="step"><i class="step-icon la la-truck"></i></span> Bưu tá đang đến lấy hàng<hr /><?php echo $tab['text_3'];?>
                                    </a>
                                </li>
                                <li role="tab" class="<?php echo $tab['class_4'];?>" aria-disabled="true">
                                    <a id="steps-uid-2-t-3" href="javascript:;" aria-controls="steps-uid-2-p-3">
                                        <span class="step"><i class="step-icon la la-angellist"></i></span> <?php echo $tab['text_04'] ? $tab['text_04']: 'Hoàn thành';?><hr /><?php echo $tab['text_4'];?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- STEP -->
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr class="bg-blue bg-lighten-5">
                                <td class="primary">Mã giao dịch</td>
                                <td><?php echo $transactions['transID'];?></td>
                            </tr>
                            <tr class="bg-blue bg-lighten-5">
                                <td class="primary">Mã thiết bị</td>
                                <td><a href="<?php echo _URL_ADMIN.'/device.php?act=update&id='.$deviceID['id'];?>"><?php echo $transactions['deviceID'].' ('. $device[$deviceID['fullname']] .')';?></a></td>
                            </tr>
                            <tr class="bg-blue bg-lighten-5">
                                <td class="primary">Tên khách hàng</td>
                                <td><a href="<?php echo _URL_ADMIN.'/customer.php?act=update&id='.$cusID['id'];?>"><?php echo $cusID['fullname'];?></a></td></td>
                            </tr>
                            <tr class="bg-blue bg-lighten-5">
                                <td class="primary">Địa chỉ khách hàng</td>
                                <td><?php echo $cusID['addr_receive'];?></td>
                            </tr>
                            <tr class="bg-blue bg-lighten-5">
                                <td class="primary">Bộ phận</td>
                                <td><?php echo $post_man['deparmentName'];?></td>
                            </tr>
                            <tr class="bg-blue bg-lighten-5">
                                <td class="primary">Tên Bưu Tá</td>
                                <td><?php echo $post_man['postManName'];?></td>
                            </tr>
                            <tr class="bg-blue bg-lighten-5">
                                <td class="primary">Bưu cục</td>
                                <td><?php echo $post_man['POName'];?></td>
                            </tr>
                            <tr class="bg-blue bg-lighten-5">
                                <td class="primary">Số điện thoại</td>
                                <td><?php echo $post_man['POPhone'];?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
require_once 'footer.php';