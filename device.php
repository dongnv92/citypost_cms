<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 04/06/2018
 * Time: 11:24
 */
require_once 'includes/core.php';
if(!$user_id){
    header('location:'._URL_HOME.'/login.php');
}
$admin_active   = 'device';
switch ($act){
    case 'delete':
        $device = getGlobalAll(_DB_TABLE_DEVICE, array('id' => $id), array('onecolum' => 'limit'));
        if(!$device){
            header('location:'._URL_ADMIN.'/customer.php');
        }
        if($submit){
            if(deleteGlobal(_DB_TABLE_DEVICE, array('id' => $id))){
                header('location:'._URL_ADMIN.'/customer.php');
            }
        }
        $admin_title = 'Xóa thiết bị';
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header"><h4 class="card-title"><?php echo $admin_title;?></h4> </div>
                    <div class="card-content text-center">
                        <form action="" method="post">
                            Bạn có chắc muốn xóa thiết bị <strong style="color: red;"><i><?php echo $device['fullname'];?></i></strong> này không?
                            <div class="form-actions text-center">
                                <input type="submit" name="submit" class="btn btn-primary" value="Xóa thiết bị này" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
    case 'update':
        $device = getGlobalAll(_DB_TABLE_DEVICE, array('id' => $id), array('onecolum' => 'limit'));

        if(!$device){
            header('location:'._URL_ADMIN.'/device.php');
        }

        if($submit){
            $device_code    = (isset($_POST['device_code'])     && !empty($_POST['device_code']))   ? $_POST['device_code']     : '';
            $device_name    = (isset($_POST['device_name'])     && !empty($_POST['device_name']))   ? $_POST['device_name']     : '';
            $device_phone   = (isset($_POST['device_phone'])    && !empty($_POST['device_phone']))  ? $_POST['device_phone']    : '';
            $device_email   = (isset($_POST['device_email'])    && !empty($_POST['device_email']))  ? $_POST['device_email']    : '';
            $device_status  = (isset($_POST['device_status'])   && !empty($_POST['device_status'])) ? $_POST['device_status']   : '';
            $error          = array();

            if(!$device_code){
                $error['device_code'] = 'Bạn chưa nhập mã thiết bị';
            }
            if(($device_code != $device['deviceID']) && checkGlobal(_DB_TABLE_DEVICE, array('deviceID' => $device_code)) > 0){
                $error['device_code'] = 'Mã thiết bị này đã tồn tại';
            }
            if(!$device_name){
                $error['device_name'] = 'Bạn chưa nhập tên thiết bị';
            }
            if(!$device_email){
                $error['device_email'] = 'Bạn chưa nhập Emai';
            }
            if(($device_email != $device['mail']) && checkGlobal(_DB_TABLE_DEVICE, array('mail' => $device_email)) > 0){
                $error['device_email'] = 'Email này đã tồn tại';
            }
            if(!filter_var($device_email, FILTER_VALIDATE_EMAIL)){
                $error['device_email'] = 'Email chưa đúng định dạng';
            }
            if(!$device_phone){
                $error['device_phone'] = 'Bạn chưa nhập số điện thoại';
            }
            if(($device_phone != $device['phone']) && checkGlobal(_DB_TABLE_DEVICE, array('phone' => $device_phone)) > 0){
                $error['device_phone'] = 'Số điện thoại này đã tồn tại';
            }

            if(!$error){
                $data   = array(
                    'deviceID'    => $device_code,
                    'fullname'    => $device_name,
                    'mail'        => $device_email,
                    'phone'       => $device_phone,
                    'status'      => $device_status
                );
                $where  = array('id' => $id);
                updateGlobal(_DB_TABLE_DEVICE, $data, $where);
                $device = getGlobalAll(_DB_TABLE_DEVICE, array('id' => $id), array('onecolum' => 'limit'));
            }
        }
        $admin_title = 'Cập nhập thiết bị';
        require_once 'header.php';
        ?>
        <!-- Basic form layout section start -->
        <section id="horizontal-form-layouts">
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
                            <div class="card-body">
                                <?php if($submit && !$error){
                                    echo '<p class="card-text"><div class="alert alert-icon-left alert-arrow-left alert-success alert-dismissible mb-2" role="alert">
                                  <span class="alert-icon"><i class="la la-thumbs-o-up"></i></span>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                  <strong>Done!</strong> Chỉnh sửa thành công.</div></p>';
                                }
                                ?>
                                <form class="form form-horizontal" method="post" action="">
                                    <div class="form-body">
                                        <h4 class="form-section"><i class="ft-slack"></i> Thông tin thiết bị</h4>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Mã thiết bị</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Mã thiết bị" name="device_code" value="<?php echo $device['deviceID'];?>" />
                                                <?php echo ($error['device_code']) ? '<small style="color: red"><i>'. $error['device_code'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Thiết bị phát triể bởi</label>
                                            <div class="col-md-9">
                                                <select name="device_name" class="form-control">
                                                    <?php
                                                    $this_device_id = getDeveloperInfo();
                                                    echo '<option value="'. $device['fullname'] .'">'. $this_device_id[$device['fullname']] .'</option>';
                                                    foreach (getDeveloperInfo() AS $key => $value){
                                                        echo '<option value="'. $key .'">'. $value .'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <?php echo ($error['device_name']) ? '<small style="color: red"><i>'. $error['device_name'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Email thiết bị</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="Email người phát triển" name="device_email" value="<?php echo $device['mail'];?>">
                                                <?php echo ($error['device_email']) ? '<small style="color: red"><i>'. $error['device_email'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Số điện thoại thiết bị</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Số điện thoại người phát triển" name="device_phone" value="<?php echo $device['phone'];?>">
                                                <?php echo ($error['device_phone']) ? '<small style="color: red"><i>'. $error['device_phone'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Trạng thái</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="device_status">
                                                    <?php echo '<option value="'. $device['status'] .'">'. getStatusDevice($device['status']) .'</option>';?>
                                                    <option value="0"><?php echo getStatusDevice(0)?></option>
                                                    <option value="1"><?php echo getStatusDevice(1)?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions text-center">
                                        <button type="button" class="btn btn-warning mr-1" onclick=""><i class="ft-x"></i> Cancel</button>
                                        <input type="submit" name="submit" class="btn btn-primary" value="Sửa thiết bị" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- // Basic form layout section end -->
        <?php
        break;
    case 'add':
        if($submit){
            $device_code    = (isset($_POST['device_code'])     && !empty($_POST['device_code']))   ? $_POST['device_code']     : '';
            $device_name    = (isset($_POST['device_name'])     && !empty($_POST['device_name']))   ? $_POST['device_name']     : '';
            $device_phone   = (isset($_POST['device_phone'])    && !empty($_POST['device_phone']))  ? $_POST['device_phone']    : '';
            $device_email   = (isset($_POST['device_email'])    && !empty($_POST['device_email']))  ? $_POST['device_email']    : '';
            $error          = array();
            if(!$device_code){
                $error['device_code'] = 'Bạn chưa nhập mã thiết bị';
            }
            if(checkGlobal(_DB_TABLE_DEVICE, array('deviceID' => $device_code)) > 0){
                $error['device_code'] = 'Mã thiết bị này đã tồn tại';
            }
            if(!$device_name){
                $error['device_name'] = 'Bạn chưa nhập tên thiết bị';
            }
            if(!$device_email){
                $error['device_email'] = 'Bạn chưa nhập Emai';
            }
            if(checkGlobal(_DB_TABLE_DEVICE, array('mail' => $device_email)) > 0){
                $error['device_email'] = 'Email này đã tồn tại';
            }
            if(!filter_var($device_email, FILTER_VALIDATE_EMAIL)){
                $error['device_email'] = 'Email chưa đúng định dạng';
            }
            if(!$device_phone){
                $error['device_phone'] = 'Bạn chưa nhập số điện thoại';
            }
            if(checkGlobal(_DB_TABLE_DEVICE, array('phone' => $device_phone)) > 0){
                $error['device_phone'] = 'Số điện thoại này đã tồn tại';
            }

            if(!$error){
                $colum  = array('[deviceID]','[fullname]','[mail]','[phone]','[status]');
                $data   = array("'$device_code'", "N'$device_name'", "'$device_email'", "'$device_phone'", 0);
                if(insertSqlserver(_DB_TABLE_DEVICE, $colum, $data)){
                    header('location:'._URL_ADMIN.'/device.php');
                }
            }
        }
        $admin_title    = 'Thêm mới thiết bị';
        require_once 'header.php';
        ?>
        <!-- Basic form layout section start -->
        <section id="horizontal-form-layouts">
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
                            <div class="card-body">
                                <form class="form form-horizontal" method="post" action="">
                                    <div class="form-body">
                                        <h4 class="form-section"><i class="ft-slack"></i> Thông tin thiết bị</h4>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Mã thiết bị</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Mã thiết bị" name="device_code" value="<?php echo $device_code;?>" />
                                                <?php echo ($error['device_code']) ? '<small style="color: red"><i>'. $error['device_code'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Thiết bị phát triể bởi</label>
                                            <div class="col-md-9">
                                                <select name="device_name" class="form-control">
                                                    <?php
                                                    foreach (getDeveloperInfo() AS $key => $value){
                                                        echo '<option value="'. $key .'">'. $value .'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <?php echo ($error['device_name']) ? '<small style="color: red"><i>'. $error['device_name'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Email thiết bị</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="Email người phát triển" name="device_email" value="<?php echo $device_email;?>">
                                                <?php echo ($error['device_email']) ? '<small style="color: red"><i>'. $error['device_email'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Số điện thoại thiết bị</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Số điện thoại người phát triển" name="device_phone" value="<?php echo $device_phone;?>">
                                                <?php echo ($error['device_phone']) ? '<small style="color: red"><i>'. $error['device_phone'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions text-center">
                                        <button type="button" class="btn btn-warning mr-1" onclick=""><i class="ft-x"></i> Cancel</button>
                                        <input type="submit" name="submit" class="btn btn-primary" value="Thêm thiết bị" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- // Basic form layout section end -->
        <?php
        break;
    default:
        $admin_title    = 'Danh sách thiết bị';
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách các thiết bị</h4>
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
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Mã thiết bị</th>
                                    <th>Người phát triển</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Trạng thái</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $device = getGlobalAll(_DB_TABLE_DEVICE, '');
                                foreach ($device AS $devices){
                                    if($devices['status'] == 0){
                                        $status = '<font color="green">'. getStatusDevice($devices['status']) .'</font>';
                                    }else if ($devices['status'] == 1){
                                        $status = '<font color="red">'. getStatusDevice($devices['status']) .'</font>';
                                    }
                                    $developer = getDeveloperInfo();
                                    echo '<tr>';
                                    echo '<td><a href="'. _URL_ADMIN .'/device.php?act=update&id='. $devices['id'] .'">'. $devices['deviceID'] .'</a><br /><small><a href="'. _URL_ADMIN .'/device.php?act=update&id='. $devices['id'] .'">Sửa</a> - <a href="'. _URL_ADMIN .'/device.php?act=delete&id='. $devices['id'] .'" style="color: red">Xóa</a></small></td>';
                                    echo '<td>'. $developer[$devices['fullname']] .'</td>';
                                    echo '<td>'. $devices['mail'] .'</td>';
                                    echo '<td>'. $devices['phone'] .'</td>';
                                    echo '<td>'. $status .'</td>';
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
}
require_once 'footer.php';