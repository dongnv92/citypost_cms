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
    case 'detail':
        $transID        = $_REQUEST['transID'];
        $transactions   = getGlobalAll(_DB_TABLE_TRANSACTIONS, array('transID' => $transID), array('onecolum' => 'limit'));
        $deviceID       = getGlobalAll(_DB_TABLE_DEVICE, array('deviceID' => $transactions['deviceID']), array('onecolum' => 'limit'));
        $cusID          = getGlobalAll(_DB_TABLE_CUSTOMER, array('cusID' => $transactions['cusID']), array('onecolum' => 'limit'));
        $device         = getDeveloperInfo();
        $tab            = array();

        if($transactions['status_02'] == 0 && $transactions['status_03'] == 0 && ($transactions['status_04'] != 104 && $transactions['status_04'] != 204)){ // Nếu trường status 02, 03, 04 bằng 0
            $tab['class_1'] = 'first done current';
            $tab['class_2'] = 'disabled';
            $tab['class_3'] = 'disabled';
            $tab['class_4'] = 'disabled last';
            $tab['text_1']  = $transactions['timeReq'];
            $tab['text_2']  = '';
            $tab['text_3']  = '';
            $tab['text_4']  = '';
        }else if($transactions['status_02'] != 0 && $transactions['status_03'] == 0 && ($transactions['status_04'] != 104 && $transactions['status_04'] != 204)){ // Nếu trường status 02 khác 0 và 03, 04 bằng 0
            $tab['class_1'] = 'first done';
            $tab['class_2'] = 'current';
            $tab['class_3'] = 'disabled';
            $tab['class_4'] = 'disabled last';
            $tab['text_1']  = $transactions['timeReq'];
            $tab['text_2']  = $transactions['time_02'];
            $tab['text_3']  = '';
            $tab['text_4']  = '';
        }else if($transactions['status_02'] != 0 && $transactions['status_03'] != 0 && ($transactions['status_04'] != 104 && $transactions['status_04'] != 204)){ // Nếu trường status 02,03 khác 0 và 04 bằng 0
            $tab['class_1'] = 'first done';
            $tab['class_2'] = 'done';
            $tab['class_3'] = 'current';
            $tab['class_4'] = 'disabled last';
            $tab['text_1']  = $transactions['timeReq'];
            $tab['text_2']  = $transactions['time_02'];
            $tab['text_3']  = $transactions['time_03'];
            $tab['text_4']  = '';
        }else if($transactions['status_02'] != 0 && $transactions['status_03'] != 0 && ($transactions['status_04'] == 104 || $transactions['status_04'] == 204)){ // Nếu trường status 02,03,04 khác 0
            $tab['class_1'] = 'first done';
            $tab['class_2'] = 'done';
            $tab['class_3'] = 'done';
            $tab['class_4'] = 'done current last';
            $tab['text_1']  = $transactions['timeReq'];
            $tab['text_2']  = $transactions['time_02'];
            $tab['text_3']  = $transactions['time_03'];
            $tab['text_4']  = $transactions['time_04'];
        }
        if($transactions['status_04'] == 301){
            $tab['text_04'] = '<strong class="danger">Khách hàng đã bấm hủy</strong>';
            $tab['text_4']  = $transactions['time_04'];
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
                                        <td><?php echo $transactions['addr_send'];?></td>
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
        $today          = date('d/m/Y', _CONFIG_TIME);
        $week_start     = date('d/m/Y', strtotime('monday this week', _CONFIG_TIME));
        $week_end       = date('d/m/Y', strtotime('sunday this week', _CONFIG_TIME));
        $month_start    = date('d/m/Y', strtotime('first day of this month', _CONFIG_TIME));
        $month_end      = date('d/m/Y', strtotime('last day of this month', _CONFIG_TIME));
        $year_start     = date('d/m/Y', strtotime('first day of January', _CONFIG_TIME));
        $year_end       = date('d/m/Y', strtotime('last day of December', _CONFIG_TIME));

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
                            <select class="form-control round" name="cusID">
                                <?php
                                $form_customer = getGlobalAll(_DB_TABLE_CUSTOMER, '');
                                if($_GET['cusID']) {
                                    echo '<option value="'. $_GET['cusID'] .'">'. getGlobalAll(_DB_TABLE_CUSTOMER, array('cusID' => $_GET['cusID']), array('onecolum' => 'fullname')) .'</option>';
                                    foreach ($form_customer AS $form_customers) {
                                        if($form_customers['cusID'] != $_GET['cusID']){
                                            echo '<option value="' . $form_customers['cusID'] . '">' . $form_customers['fullname'] . '</option>';
                                        }
                                    }
                                    echo '<option value="">Tất cả</option>';
                                }else{
                                    echo '<option value="">Khách hàng</option>';
                                    foreach ($form_customer AS $form_customers) {
                                        echo '<option value="' . $form_customers['cusID'] . '">' . $form_customers['fullname'] . '</option>';
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
                </form>
                <hr />
                <div class="card">
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
                        $query                          = 'SELECT * FROM (SELECT ROW_NUMBER() OVER(ORDER BY id DESC) AS RowNumber, id,btnID, status,transID,cusID,deviceID,addr_send,timeReq,status_04 FROM '. _DB_TABLE_TRANSACTIONS .' '. $parameters_list .') AS Temp WHERE RowNumber BETWEEN '. $page_start .' AND '.($page_start + ($config_pagenavi['page_row'] - 1));
                        //echo $query; exit();
                        $data                           = getGlobalAll(_DB_TABLE_TRANSACTIONS, '', array('query' => $query));
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
                            echo '<tr>';
                            echo '<td><a href="'. _URL_ADMIN .'/transactions.php?act=detail&transID='. $datas['transID'] .'">'. $datas['transID'] .'</a></td>';
                            echo '<td>'. $transdetail[$datas['btnID']] .'</td>';
                            echo '<td>'. $cusID['fullname'] .'</td>';
                            echo '<td>'. $device[$deviceID['id']] .'</td>';
                            echo '<td>'. $datas['addr_send'] .'</td>';
                            echo '<td>'. $datas['timeReq'] .'</td>';
                            echo '<td>Coming ...</td>';
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
        <?php
        break;
}
require_once 'footer.php';