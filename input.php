<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 02/07/2018
 * Time: 09:37
 */
require_once 'includes/core.php';
if(!$user_id){
    header('location:'._URL_HOME.'/login.php');
}
$admin_active   = 'input';
switch ($act){
    default:
        if($submit){
            $device_phone   = (isset($_POST['device_phone'])    && !empty($_POST['device_phone']))  ? $_POST['device_phone']    : '';
            $device_contents = (isset($_POST['device_content'])  && !empty($_POST['device_content']))? $_POST['device_content']  : '';
            if(isset($device_contents)){
                $text = '';
                $device_content = explode(' ', $device_contents);
                $output_default = $device_content[0]; // Mặc định
                $output_method  = $device_content[1]; // Type ID
                switch ($output_method){
                    case 'send'://citypost send 1 867856038937204
                        $output_typeid  = $device_content[2];
                        $output_device  = $device_content[3];
                        //http://112.78.11.14:8080
                        //http://localhost:8818/CITYBUTTON_API
                        $urlAPI = "http://112.78.11.14:8080/index.php?method=sendMSG&deviceId=$output_device&prodID=1&typeID=$output_typeid&deviceStatus=101";
                        $request = file_get_contents($urlAPI);
                        if($request != ""){
                            $json = json_decode($request, true);
                            $transID = $json['transID'];
                            //echo $transID;
                            $time_req = getTime();
                            $colums = "sms_code,sms_device,sms_type,sms_content,method,transID,time_req,status";
                            $datas = "'$output_default','$output_device',$output_typeid,'$device_contents','$output_method','$transID','$time_req','1'";
                            if(InsertData("tblSMSReceive",$colums,$datas) > 0){
                                header("location:"._URL_ADMIN.'/sms.php');
                            }
                        }

                        /*
                        $status_1 = getProcessTransactions($transID);
                        $status_2 = str_replace(array('step_4','step_3','step_2','step_1', 'customer_delete'), array(104,103,101,101,301), $status_2);
                        */
                        //$text   = "buttonid=$output_typeid status=101";
                        break;
                    case 'cance'://citypost cance 2 867856038937204
                        $output_typeid  = $device_content[2];
                        $output_device  = $device_content[3];
                        //112.78.11.14:8080
                        //http://localhost:8818/CITYBUTTON_API
                        $urlAPI = "http://112.78.11.14:8080/index.php?method=sendMSG&deviceId=$output_device&prodID=1&typeID=$output_typeid&deviceStatus=301";
                        $request = file_get_contents($urlAPI);
                        $text = $request;
                        if($request != ""){
                            $json = json_decode($request, true);
                            $transID = $json['transID'];
                            //echo $transID;
                            $time_req = getTime();
                            $colums = "sms_code,sms_device,sms_type,sms_content,method,transID,time_req,status";
                            $datas = "'$output_default','$output_device',$output_typeid,'$device_contents','$output_method','$transID','$time_req','1'";
                            if(InsertData("tblSMSReceive",$colums,$datas) > 0){
                                header("location:"._URL_ADMIN.'/sms.php');
                                $r1 =  updateDataByVar("status_04=301,time_04='$time_req',status=1","tblTransactions","transID='$transID'");
                                $r2 = updateDataByVar("status_04=301,time_04='$time_req',status=1","tblTransactionHistory","transID='$transID'");
                                if($r1 > 0 && $r2 >0){
                                    header("location:"._URL_ADMIN.'/sms.php');
                                }
                            }
                        }
                        break;
                    case 'check'://citypost check 867856038937204 -> return: buttonid=1 status=101
                        $output_device  = $device_content[2];
                        $time_req = getTime();
                        $colums = "sms_code,sms_device,sms_content,method,time_req,status";
                        $datas = "'$output_default','$output_device','$device_contents','$output_method','$time_req','1'";
                        if(InsertData("tblSMSReceive",$colums,$datas) > 0){
                            //header("location:"._URL_ADMIN.'/sms.php');
                            $syntax = file_get_contents("http://112.78.11.14/api.php?act=citypost%20check%20$output_device");
                        }

                        /*
                        $button_1 = getGlobalAll(_DB_TABLE_TRANSACTIONS, array('deviceID' => "$output_device", 'btnID' => 1), array('onecolum' => 'limit', 'select' => ' TOP 1 * ', 'order_by' => 'timeReq', 'order_by_soft' => 'DESC'));
                        $status_1 = getProcessTransactions($button_1['transID']);
                        $status_1 = str_replace(array('step_4','step_3','step_2','step_1', 'customer_delete', ''), array(104,103,101,101,301,104), $status_1);
                        $button_2 = getGlobalAll(_DB_TABLE_TRANSACTIONS, array('deviceID' => "$output_device", 'btnID' => 2), array('onecolum' => 'limit', 'select' => ' TOP 1 * ', 'order_by' => 'timeReq', 'order_by_soft' => 'DESC'));
                        $status_2 = getProcessTransactions($button_2['transID']);
                        $status_2 = str_replace(array('step_4','step_3','step_2','step_1', 'customer_delete'), array(104,103,101,101,301), $status_2);
                        $text .= 'buttonid=1 status='.$status_1.'|buttonid=2 status='.$status_2.'.';
                        */


                        break;
                }

            }

        }
        $admin_title = 'Nhập liệu';
        require_once 'header.php';
        ?>
        <form action="" method="post" class="form form-horizontal">

            <div class="row">

                <div class="col">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Nhập liệu dữ liệu thiết bị gửi lên</h4> </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 label-control">Số điện thoại thiết bị</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" required placeholder="Số điện thoại thiết bị" name="device_phone" value="<?php echo $device_phone;?>" />
                                    <?php echo ($error['device_phone']) ? '<small style="color: red"><i>'. $error['device_phone'] .'</i></small>' : '';?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control">Nội dung</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Nội dung" name="device_content" value="<?php echo $device_contents;?>">
                                    <?php echo ($error['device_content']) ? '<small style="color: red"><i>'. $error['device_content'] .'</i></small>' : '';?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control">Nội dung trả về</label>
                                <div class="col-md-9">
                                    <textarea rows="6" class="form-control"><?php echo $syntax;//echo $datas;?></textarea>
                                </div>
                            </div>
                            <div class="form-actions text-center">
                                <button type="button" class="btn btn-warning mr-1" onclick="window.location.href=sms.php"> Cancel</button>
                                <input type="submit" name="submit" class="btn btn-primary" value="Update" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
        break;
}
require_once 'footer.php';