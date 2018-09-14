<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 07/06/2018
 * Time: 09:24
 */
require_once 'includes/core.php';
if(!$user_id){
    header('location:'._URL_HOME.'/login.php');
}
$admin_active   = 'transactions';
switch ($act){
    case 'del':
        $tranactions = getGlobalAll(_DB_TABLE_TRANSACTIONS, array('id' => $id), array('onecolum' => 'limit'));
        if(!$tranactions){
            header('location:'._URL_ADMIN.'/transactions.php');
        }

        if($submit){
            if(deleteGlobal(_DB_TABLE_TRANSACTIONS, array('id' => $id))){
                header('location:'._URL_ADMIN.'/transactions.php');
            }
        }
         $admin_title = 'Xóa giao dịch';
         require_once 'header.php';
         ?>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title"><?php echo $admin_title;?></h4> </div>
                        <div class="card-content text-center">
                            <form action="" method="post">
                                Bạn có chắc muốn xóa giao dịch <strong style="color: red;"><i><?php echo $tranactions['transID'];?></i></strong> này không?
                                <div class="form-actions text-center">
                                    <input type="submit" name="submit" class="btn btn-outline-cyan round" value="Xóa giao dịch này" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        break;
    case 'update':
        $tranactions = getGlobalAll(_DB_TABLE_TRANSACTIONS, array('id' => $id), array('onecolum' => 'limit'));
        if(!$tranactions){
            header('location:'._URL_ADMIN.'/transactions.php');
        }

        if($submit){
            $status_01  = (isset($_POST['status_01'])   && !empty($_POST['status_01'])) ? $_POST['status_01']   : '';
            $status_02  = (isset($_POST['status_02'])   && !empty($_POST['status_02'])) ? $_POST['status_02']   : '';
            $status_03  = (isset($_POST['status_03'])   && !empty($_POST['status_03'])) ? $_POST['status_03']   : '';
            $status_04  = (isset($_POST['status_04'])   && !empty($_POST['status_04'])) ? $_POST['status_04']   : '';
            $status     = (isset($_POST['status'])      && !empty($_POST['status']))    ? $_POST['status']      : '';
            $time_01    = (isset($_POST['time_01'])     && !empty($_POST['time_01']))   ? $_POST['time_01']     : '';
            $time_02    = (isset($_POST['time_02'])     && !empty($_POST['time_02']))   ? $_POST['time_02']     : '';
            $time_03    = (isset($_POST['time_03'])     && !empty($_POST['time_03']))   ? $_POST['time_03']     : '';
            $time_04    = (isset($_POST['time_04'])     && !empty($_POST['time_04']))   ? $_POST['time_04']     : '';
            $data       = array(
                'status_01' => $status_01,
                'status_02' => $status_02,
                'status_03' => $status_03,
                'status_04' => $status_04,
                'time_01'   => $time_01,
                'time_02'   => $time_02,
                'time_03'   => $time_03,
                'time_04'   => $time_04,
                'status'    => $status
            );
            $where  = array('id' => $id);
            $error = false;

            if(!updateGlobal(_DB_TABLE_TRANSACTIONS, $data, $where)){
                $error = true;
            }

            $tranactions = getGlobalAll(_DB_TABLE_TRANSACTIONS, array('id' => $id), array('onecolum' => 'limit'));
        }

        $admin_title = 'Chỉnh sửa giao dịch '.$tranactions['transID'];
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic"><?php echo $admin_title;?></h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <?php if($submit && !$error){echo '<p class="text-center text-success">Update thành công</p>';}?>
                        <?php if($submit && $error){echo '<p class="text-center text-danger">Có lỗi Update</p>';}?>
                        <form class="form form-horizontal" action="" method="post">
                            <div class="form-group row">
                                <label class="col-md-3 label-control">Mã Status 01</label>
                                <div class="col-md-9"><input type="text" class="form-control" placeholder="Mã Status 01" name="status_01" value="<?php echo $tranactions['status_01'];?>" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control">Thời gian 01</label>
                                <div class="col-md-9"><input type="text" class="form-control" placeholder="Thời gian 01" name="time_01" value="<?php echo $tranactions['time_01']->format('Y-m-d H:i:s.000');?>" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control">Mã Status 02</label>
                                <div class="col-md-9"><input type="text" class="form-control" placeholder="Mã Status 02" name="status_02" value="<?php echo $tranactions['status_02'];?>" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control">Thời gian 02</label>
                                <div class="col-md-9"><input type="text" class="form-control" placeholder="Thời gian 02" name="time_02" value="<?php echo $tranactions['time_02']->format('Y-m-d H:i:s.000');?>" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control">Mã Status 03</label>
                                <div class="col-md-9"><input type="text" class="form-control" placeholder="Mã Status 03" name="status_03" value="<?php echo $tranactions['status_03'];?>" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control">Thời gian 03</label>
                                <div class="col-md-9"><input type="text" class="form-control" placeholder="Thời gian 03" name="time_03" value="<?php echo $tranactions['time_03']->format('Y-m-d H:i:s.000');?>" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control">Mã Status 04</label>
                                <div class="col-md-9"><input type="text" class="form-control" placeholder="Mã Status 04" name="status_04" value="<?php echo $tranactions['status_04'];?>" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control">Thời gian 04</label>
                                <div class="col-md-9"><input type="text" class="form-control" placeholder="Thời gian 04" name="time_04" value="<?php echo $tranactions['time_04'] ? $tranactions['time_04']->format('Y-m-d H:i:s.000') : '';?>" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control">Status</label>
                                <div class="col-md-9"><input type="text" class="form-control" placeholder="Status" name="status" value="<?php echo $tranactions['status'];?>" /></div>
                            </div>
                            <div class="form-actions text-center">
                                <a href="transactions.php?act=del&id=<?php echo $id;?>" class="btn btn-outline-danger round">Xóa</a>
                                <button type="button" class="btn btn-outline-cyan round" onclick="javascript:location.href='<?php echo _URL_ADMIN.'/transactions.php?act=detail&transID='.$tranactions['transID'];?>'">Quay lại</button>
                                <input type="submit" name="submit" class="btn btn-outline-cyan round" value="Cập nhập" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
    case 'detail':
        $transID            = $_REQUEST['transID'];
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

        $admin_title    = 'Chi tiết giao dịch - '.$transactions['transID'];
        require_once 'header.php';
        ?>
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
                                        <td><?php echo $transactions['transID'];?> <a href="<?php echo _URL_ADMIN.'/transactions.php?act=update&id='.$transactions['id'];?>">Chỉnh sửa</a></td>
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
        break;
    default:
        $today          = date('Y/m/d', _CONFIG_TIME);
        $week_start     = date('Y/m/d', strtotime('monday this week', _CONFIG_TIME));
        $week_end       = date('Y/m/d 23:59:59', strtotime('sunday this week', _CONFIG_TIME));
        $month_start    = date('Y/m/d', strtotime('first day of this month', _CONFIG_TIME));
        $month_end      = date('Y/m/d 23:59:59', strtotime('last day of this month', _CONFIG_TIME));
        $year_start     = date('Y/m/d', strtotime('first day of January', _CONFIG_TIME));
        $year_end       = date('Y/m/d 23:59:59', strtotime('last day of December', _CONFIG_TIME));
        $css_plus       = array('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        $js_plus        = array('https://code.jquery.com/ui/1.12.1/jquery-ui.js', 'app-assets/js/combobox.js');
        $admin_title    = 'Theo dõi hoạt động';
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col-md-12">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control round" name="btnID">
                                <?php
                                if($_GET['btnID'] == 1){
                                    echo '<option value="1">Thư</option>';
                                    echo '<option value="">Tất cả</option>';
                                    echo '<option value="2">Hàng</option>';
                                }else if($_GET['btnID'] == 2){
                                    echo '<option value="2">Hàng</option>';
                                    echo '<option value="">Tất Cả</option>';
                                    echo '<option value="1">Thư</option>';
                                }else{
                                    echo '<option value="">Kiểu hàng</option>';
                                    echo '<option value="1">Thư</option>';
                                    echo '<option value="2">Hàng</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control round" name="deviceID">
                                <?php
                                $device_developer = getDeveloperInfo();
                                $form_device = getGlobalAll(_DB_TABLE_DEVICE, '');
                                if($_GET['deviceID']){
                                    echo '<option value="'. $_GET['deviceID'] .'">'. $device_developer[getGlobalAll(_DB_TABLE_DEVICE, array('deviceID' => $_GET['deviceID']), array('onecolum' => 'fullname'))] .'</option>';
                                    foreach ($form_device AS $form_devices){
                                        if($form_devices['deviceID'] != $_GET['deviceID']){
                                            echo '<option value="'. $form_devices['deviceID'] .'">'. $device_developer[$form_devices['fullname']] .'</option>';
                                        }
                                    }
                                    echo '<option value="">Tât cả</option>';
                                }else{
                                    echo '<option value="">Thiết bị</option>';
                                    foreach ($form_device AS $form_devices){
                                        echo '<option value="'. $form_devices['deviceID'] .'">'. $device_developer[$form_devices['fullname']] .'</option>';
                                    }
                                }

                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control round" name="type">
                                <?php
                                if($type){
                                   switch ($type){
                                       case 'this_day':
                                           echo '<option value="'. $type .'">Hôm nay</option>';
                                           echo '<option value="">Tất cả</option>';
                                           break;
                                       case 'this_week':
                                           echo '<option value="'. $type .'">Tuần này</option>';
                                           echo '<option value="">Tất cả</option>';
                                           break;
                                       case 'this_month':
                                           echo '<option value="'. $type .'">Tháng này</option>';
                                           echo '<option value="">Tất cả</option>';
                                           break;
                                       case 'this_year':
                                           echo '<option value="'. $type .'">Năm nay</option>';
                                           echo '<option value="">Tất cả</option>';
                                           break;
                                   }
                                }
                                ?>
                                <?php if(!$type){?><option value="">Thời gian</option><?php }?>
                                <?php if($type != 'this_day'){?><option value="this_day">Hôm nay</option><?php }?>
                                <?php if($type != 'this_week'){?><option value="this_week">Tuần này</option><?php }?>
                                <?php if($type != 'this_month'){?><option value="this_month">Tháng này</option><?php }?>
                                <?php if($type != 'this_year'){?><option value="this_year">Năm nay</option><?php }?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control round">
                                <option>Trạng thái</option>
                                <option>Hoàn thành</option>
                                <option>Chưa hoàn thành</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="submit" value="Tìm kiếm" class="btn btn-outline-primary round btn-min-width mr-1 mb-1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <select class="form-control round ac-combobox" name="cusID">
                                <?php
                                $form_customer = getGlobalAll(_DB_TABLE_CUSTOMER, '', array('query' => 'SELECT DISTINCT [cusID] FROM '. _DB_TABLE_TRANSACTIONS .';'));
                                if($_GET['cusID']) {
                                    echo '<option value="'. $_GET['cusID'] .'">'. getGlobalAll(_DB_TABLE_CUSTOMER, array('cusID' => $_GET['cusID']), array('onecolum' => 'fullname')) .'</option>';
                                    foreach ($form_customer AS $form_customers) {
                                        if($form_customers['cusID'] != $_GET['cusID']){
                                            echo '<option value="' . $form_customers['cusID'] . '">'. getGlobalAll(_DB_TABLE_CUSTOMER, array('cusID' => $form_customers['cusID']), array('onecolum' => 'fullname')) .'</option>';
                                        }
                                    }
                                    echo '<option value="">Tất cả</option>';
                                }else{
                                    echo '<option value="">Khách hàng</option>';
                                    foreach ($form_customer AS $form_customers) {
                                        echo '<option value="' . $form_customers['cusID'] . '">'. getGlobalAll(_DB_TABLE_CUSTOMER, array('cusID' => $form_customers['cusID']), array('onecolum' => 'fullname')) .'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <?php
                        $para = array('btnID', 'deviceID','status_04','cusID');
                        foreach ($para AS $paras){
                            if(isset($_REQUEST[$paras]) && !empty($_REQUEST[$paras])){
                                $parameters[$paras] = $_REQUEST[$paras];
                            }
                        }
                        if($parameters){
                            foreach ($parameters as $key => $value) {
                                $colums[] = '['.$key .'] = '. "N'". $value ."'";
                            }
                            $parameters_list = ' WHERE '.implode(' AND ', $colums);
                        }

                        // Advance Query
                        if($type == 'this_day'){
                            $query_advances[] = "datediff(DAY, [timeReq], '". $today ."') = 0";
                        }else if($type == 'this_week'){
                            $query_advances[] = "([timeReq] between '". $week_start ."' AND '". $week_end ."')";
                        }else if($type == 'this_month'){
                            $query_advances[] = "([timeReq] between '". $month_start ."' AND '". $month_end ."')";
                        }else if($type == 'this_year'){
                            $query_advances[] = "([timeReq] between '". $year_start ."' AND '". $year_end ."')";
                        }else if($type == 'this_day_success'){
                            $query_advances[] = "datediff(DAY, [timeReq], '". $today ."') = 0 AND ([status_04] = 104 OR [status_04] = 204)";
                        }else if($type == 'this_week_success'){
                            $query_advances[] = "([timeReq] between '". $week_start ."' AND '". $week_end ."') AND ([status_04] = 104 OR [status_04] = 204)";
                        }else if($type == 'this_month_success'){
                            $query_advances[] = "([timeReq] between '". $month_start ."' AND '". $month_end ."') AND ([status_04] = 104 OR [status_04] = 204)";
                        }else if($type == 'this_day_false'){
                            $query_advances[] = "datediff(DAY, [timeReq], '". $today ."') = 0 AND ([status_04] <> 104 AND [status_04] <> 204)";
                        }else if($type == 'this_week_false'){
                            $query_advances[] = "([timeReq] between '". $week_start ."' AND '". $week_end ."') AND ([status_04] <> 104 AND [status_04] <> 204)";
                        }else if($type == 'this_month_false'){
                            $query_advances[] = "([timeReq] between '". $month_start ."' AND '". $month_end ."') AND ([status_04] <> 104 AND [status_04] <> 204)";
                        }
                        if($type){
                            $parameters_list                .= ($parameters ? ' AND ' : ($type ? ' WHERE ' : '')).implode(' AND ', $query_advances);
                            $parameters['type']             = $type;
                        }
                        // Advance Query

                        // Tạo Url Parameter động
                        foreach ($parameters as $key => $value) {
                            $para_url[] = $key .'='. $value;
                        }
                        $para_list                      = implode('&', $para_url);

                        $query_pagenum                  = 'SELECT [id] FROM '._DB_TABLE_TRANSACTIONS.' '.$parameters_list;
                        $query_pagenum                  = sqlsrv_query($connection, $query_pagenum, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
                        $page_num                       = sqlsrv_num_rows($query_pagenum);
                        $config_pagenavi['page_row']    = _CONFIG_PAGINATION;
                        $config_pagenavi['page_num']    = ceil($page_num/$config_pagenavi['page_row']);
                        $config_pagenavi['url']         = _URL_ADMIN.'/transactions.php?'.$para_list.'&';
                        $page_start                     = ($page-1) * $config_pagenavi['page_row'];
                        $page_start                     = $page_start == 0 ? 1 : $page_start+1;
                        $query                          = 'SELECT * FROM (SELECT ROW_NUMBER() OVER(ORDER BY id DESC) AS RowNumber, id,btnID, status,transID,cusID,deviceID,timeReq,status_04 FROM '. _DB_TABLE_TRANSACTIONS .' '. $parameters_list .') AS Temp WHERE RowNumber BETWEEN '. $page_start .' AND '.($page_start + ($config_pagenavi['page_row'] - 1));
                        //echo $query; exit();
                        $data                           = getGlobalAll(_DB_TABLE_TRANSACTIONS, '', array('query' => $query));
                        echo '<p class="text-right"><nav aria-label="Page navigation">'.pagination($config_pagenavi).'</nav></p>';
                        echo '<div class="table-responsive">';
                        echo '<table class="table">';
                        echo '<thread>';
                        echo '<tr>';
                        echo '<th>Mã giao dịch</th>';
                        echo '<th>Kiểu hàng</th>';
                        echo '<th>Tên khách hàng</th>';
                        echo '<th>Mã thiết bị</th>';
                        echo '<th>Địa chỉ</th>';
                        echo '<th>Thời gian bấm</th>';
                        echo '<th>Trạng thái</th>';
                        echo '</tr>';
                        echo '</thread>';
                        echo '<tbody>';
                        foreach ($data AS $datas){
                            $cusID      = getGlobalAll(_DB_TABLE_CUSTOMER, array('cusID' => $datas['cusID']), array('onecolum' => 'limit'));
                            $deviceID   = getGlobalAll(_DB_TABLE_DEVICE, array('deviceID' => $datas['deviceID']), array('onecolum' => 'limit'));
                            $device     = getDeveloperInfo();
                            $transdetail= getButtonIDDetail();
                            $status     = getProcessTransactions($datas['transID']);
                            if($status == 'step_4'){
                                $status = 'Đã hoàn tất';
                            }else if($status == 'step_3'){
                                $status = 'Đang đến lấy hàng';
                            }else if($status == 'step_2'){
                                $status = 'Điều hành đã tiếp nhận';
                            }else if($status == 'step_1'){
                                $status = 'Chưa xử lý';
                            }else if($status == 'customer_delete'){
                                $status = '<font color="red">Khách hàng đã bấm hủy</font>';
                            }else if($status == 'dieuhanh_cance'){
                                $status = '<font color="red">Điều hành hủy tin</font>';
                            }
                            echo '<tr>';
                            echo '<td><a href="'. _URL_ADMIN .'/transactions.php?act=detail&transID='. $datas['transID'] .'">'. $datas['transID'] .'</a></td>';
                            echo '<td>'. $transdetail[$datas['btnID']] .'</td>';
                            echo '<td><a href="'. _URL_ADMIN .'/customer.php?act=update&id='. $cusID['id'] .'">'. $cusID['fullname'] .'</a></td>';
                            echo '<td><a href="'. _URL_ADMIN .'/device.php?act=update&id='. $deviceID['id'] .'">'. $deviceID['deviceID'] .' ('. $device[$deviceID['fullname']] .')</a></td>';
                            echo '<td>'. $cusID['addr_receive'] .'</td>';
                            echo '<td>'. getViewTime($datas['timeReq']) .'</td>';
                            echo '<td>'.$status.'</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                        echo '<nav aria-label="Page navigation">'.pagination($config_pagenavi).'</nav>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php
        break;
}
require_once 'footer.php';