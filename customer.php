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

$admin_active   = 'customer';
switch ($act){
    case 'delete':
        $customer = getGlobalAll(_DB_TABLE_CUSTOMER, array('id' => $id), array('onecolum' => 'limit'));
        if(!$customer){
            header('location:'._URL_ADMIN.'/customer.php');
        }
        if($submit){
            if(deleteGlobal(_DB_TABLE_CUSTOMER, array('id' => $id))){
                header('location:'._URL_ADMIN.'/customer.php');
            }
        }
        $admin_title = 'Xóa khách hàng';
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header"><h4 class="card-title"><?php echo $admin_title;?></h4> </div>
                    <div class="card-content text-center">
                        <form action="" method="post">
                            Bạn có chắc muốn xóa khách hàng <strong style="color: red;"><i><?php echo $customer['fullname'];?></i></strong> này không?
                            <div class="form-actions text-center">
                                <input type="submit" name="submit" class="btn btn-primary" value="Xóa khách hàng này" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
    case 'update':
        $customer = getGlobalAll(_DB_TABLE_CUSTOMER, array('id' => $id), array('onecolum' => 'limit'));

        if(!$customer){
            header('location:'._URL_ADMIN.'/customer.php');
        }

        if($submit){
            $customer_code          = (isset($_POST['customer_code'])           && !empty($_POST['customer_code']))         ? $_POST['customer_code']           : '';
            $customer_device        = (isset($_POST['customer_device'])         && !empty($_POST['customer_device']))       ? $_POST['customer_device']         : '';
            $customer_address       = (isset($_POST['customer_address'])        && !empty($_POST['customer_address']))      ? $_POST['customer_address']        : '';
            $customer_pass          = (isset($_POST['customer_pass'])           && !empty($_POST['customer_pass']))         ? $_POST['customer_pass']           : '';
            $customer_repass        = (isset($_POST['customer_repass'])         && !empty($_POST['customer_repass']))       ? $_POST['customer_repass']         : '';
            $customer_name          = (isset($_POST['customer_name'])           && !empty($_POST['customer_name']))         ? $_POST['customer_name']           : '';
            $customer_email         = (isset($_POST['customer_email'])          && !empty($_POST['customer_email']))        ? $_POST['customer_email']          : '';
            $customer_phone         = (isset($_POST['customer_phone'])          && !empty($_POST['customer_phone']))        ? $_POST['customer_phone']          : '';
            $customer_phone_company = (isset($_POST['customer_phone_company'])  && !empty($_POST['customer_phone_company']))? $_POST['customer_phone_company']  : '';
            $customer_status        = (isset($_POST['customer_status'])   && !empty($_POST['customer_status'])) ? $_POST['customer_status']   : '';
            $error                  = array();

            if(!$customer_code){
                $error['customer_code'] = 'Bạn chưa nhập mã khách hàng';
            }
            if(($customer_code != $customer['cusID']) && checkGlobal(_DB_TABLE_CUSTOMER, array('cusID' => $customer_code)) > 0){
                $error['users_login'] = 'Mã khách hàng này đã tồn tại';
            }
            if(($customer_pass && $customer_repass) && ($customer_pass != $customer_repass)){
                $error['customer_pass'] = 'Hai mật khẩu không giống nhau.';
            }
            if(($customer_device  != $customer['deviceID']) && checkGlobal(_DB_TABLE_CUSTOMER, array('deviceID' => $customer_device)) > 0){
                $error['users_device'] = 'Thiết bị này đang được sử dụng bởi người khác';
            }
            if(!$customer_name){
                $error['customer_name'] = 'Bạn chưa nhập tên khách hàng';
            }
            if(!$customer_email){
                $error['customer_email'] = 'Bạn chưa nhập Emai';
            }
            if(!filter_var($customer_email, FILTER_VALIDATE_EMAIL)){
                $error['customer_email'] = 'Email chưa đúng định dạng';
            }
            if(($customer_email  != $customer['mail']) && checkGlobal(_DB_TABLE_CUSTOMER, array('mail' => $customer_email)) > 0){
                $error['users_email'] = 'Email này đã được sử dụng';
            }
            if(!$customer_phone){
                $error['customer_phone'] = 'Bạn chưa nhập số điện thoại';
            }
            if(($customer_phone != $customer['phone']) && checkGlobal(_DB_TABLE_CUSTOMER, array('phone' => $customer_phone)) > 0){
                $error['users_email'] = 'Sô điện thoại này đã được sử dụng';
            }
            if(!$error){
                if($customer_pass && $customer_repass){
                    $pass   = md5($customer_pass);
                    $data   = array(
                            'cusID'         => $customer_code,
                            'deviceID'      => $customer_device,
                            'addr_receive'  => $customer_address,
                            'password'      => $pass,
                            'fullname'      => $customer_name,
                            'mail'          => $customer_email,
                            'status'        => $customer_status,
                            'phone_company' => $customer_phone_company,
                            'phone'         => $customer_phone
                    );
                    $where  = array('id' => $id);
                }else{
                    $data   = array(
                        'cusID'         => $customer_code,
                        'deviceID'      => $customer_device,
                        'addr_receive'  => $customer_address,
                        'fullname'      => $customer_name,
                        'mail'          => $customer_email,
                        'status'        => $customer_status,
                        'phone_company' => $customer_phone_company,
                        'phone'         => $customer_phone
                    );
                    $where  = array('id' => $id);
                }
                updateGlobal(_DB_TABLE_CUSTOMER, $data, $where);
                $customer = getGlobalAll(_DB_TABLE_CUSTOMER, array('id' => $id), array('onecolum' => 'limit'));
            }
        }
        $admin_title = 'Cập nhập khách hàng';
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
                                <p class="card-text text-left text-danger"><i>* Nếu không đổi mật khẩu hãy để trống trường nhập mật khẩu</i>
                                <?php if($submit && !$error){
                                    echo '<div class="alert alert-icon-left alert-arrow-left alert-success alert-dismissible mb-2" role="alert">
                                  <span class="alert-icon"><i class="la la-thumbs-o-up"></i></span>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                  <strong>Done!</strong> Chỉnh sửa thành công.</div>';}
                                ?>
                                </p>
                                <form class="form form-horizontal" method="post" action="">
                                    <div class="form-body">
                                        <h4 class="form-section"><i class="ft-slack"></i> Thêm mới một khách hàng</h4>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Mã khách hàng</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="Mã khách hàng" name="customer_code" value="<?php echo $customer['cusID'];?>" />
                                                <?php echo ($error['customer_code']) ? '<small style="color: red"><i>'. $error['customer_code'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Mã thiết bị</label>
                                            <div class="col-md-9">
                                                <select name="customer_device" class="form-control round">
                                                    <?php
                                                    echo '<option value="'. $customer['deviceID'] .'">'. $customer['deviceID'] .'</option>';
                                                    foreach (getGlobalAll(_DB_TABLE_DEVICE, '') AS $select_device){
                                                        if($customer['deviceID'] != $select_device['deviceID']){
                                                            echo '<option value="'. $select_device['deviceID'] .'">'. $select_device['deviceID'] .'</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <?php echo ($error['customer_device']) ? '<small style="color: red"><i>'. $error['customer_device'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Mật khẩu</label>
                                            <div class="col-md-9">
                                                <input type="password" class="form-control" placeholder="Mật khẩu" name="customer_pass" value="">
                                                <?php echo ($error['customer_pass']) ? '<small style="color: red"><i>'. $error['customer_pass'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Nhập lại mật khẩu</label>
                                            <div class="col-md-9">
                                                <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" name="customer_repass" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Tên khách hàng</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Tên khách hàng" name="customer_name" value="<?php echo $customer['fullname'];?>" />
                                                <?php echo ($error['customer_name']) ? '<small style="color: red"><i>'. $error['customer_name'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Email khách hàng</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="Email khách hàng" name="customer_email" value="<?php echo $customer['mail'];?>">
                                                <?php echo ($error['customer_email']) ? '<small style="color: red"><i>'. $error['customer_email'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Số điện thoại khách hàng</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Email khách hàng" name="customer_phone" value="<?php echo $customer['phone'];?>">
                                                <?php echo ($error['customer_phone']) ? '<small style="color: red"><i>'. $error['customer_phone'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Số điện thoại công ty</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Email khách hàng" name="customer_phone_company" value="<?php echo $customer['phone_company'];?>">
                                                <?php echo ($error['customer_phone_company']) ? '<small style="color: red"><i>'. $error['customer_phone_company'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="projectinput9">Địa chỉ</label>
                                            <div class="col-md-9">
                                                <textarea rows="5" class="form-control" name="customer_address" placeholder="Địa chỉ khách hàng"><?php echo $customer['addr_receive'];?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Trạng thái</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="customer_status">
                                                    <?php echo '<option value="'. $customer['status'] .'">'. getStatusCustomer($customer['status']) .'</option>';?>
                                                    <option value="0">Đang hoạt động</option>
                                                    <option value="1">Chưa hoạt động</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions text-center">
                                        <button type="button" class="btn btn-warning mr-1" onclick=""><i class="ft-x"></i> Cancel</button>
                                        <input type="submit" name="submit" class="btn btn-primary" value="Update" />
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
            $customer_code          = (isset($_POST['customer_code'])           && !empty($_POST['customer_code']))         ? $_POST['customer_code']           : '';
            $customer_device        = (isset($_POST['customer_device'])         && !empty($_POST['customer_device']))       ? $_POST['customer_device']         : '';
            $customer_address       = (isset($_POST['customer_address'])        && !empty($_POST['customer_address']))      ? $_POST['customer_address']        : '';
            $customer_pass          = (isset($_POST['customer_pass'])           && !empty($_POST['customer_pass']))         ? $_POST['customer_pass']           : '';
            $customer_repass        = (isset($_POST['customer_repass'])         && !empty($_POST['customer_repass']))       ? $_POST['customer_repass']         : '';
            $customer_name          = (isset($_POST['customer_name'])           && !empty($_POST['customer_name']))         ? $_POST['customer_name']           : '';
            $customer_email         = (isset($_POST['customer_email'])          && !empty($_POST['customer_email']))        ? $_POST['customer_email']          : '';
            $customer_phone         = (isset($_POST['customer_phone'])          && !empty($_POST['customer_phone']))        ? $_POST['customer_phone']          : '';
            $customer_phone_company = (isset($_POST['customer_phone_company'])  && !empty($_POST['customer_phone_company']))? $_POST['customer_phone_company']  : '';
            $error          = array();
            if(!$customer_code){
                $error['customer_code'] = 'Bạn chưa nhập mã khách hàng';
            }
            if(checkGlobal(_DB_TABLE_CUSTOMER, array('cusID' => $customer_code)) > 0){
                $error['customer_code'] = 'Mã khách hàng này đã tồn tại';
            }
            if(checkGlobal(_DB_TABLE_CUSTOMER, array('deviceID' => $customer_device)) > 0){
                $error['customer_device'] = 'Thiết bị này đã tồn tại';
            }
            if(!$customer_pass || !$customer_repass){
                $error['customer_pass'] = 'Bạn chưa nhập mật khẩu';
            }
            if($customer_pass != $customer_repass){
                $error['customer_pass'] = 'Hai mật khẩu không giống nhau.';
            }
            if(!$customer_name){
                $error['customer_name'] = 'Bạn chưa nhập tên khách hàng';
            }
            if(!$customer_email){
                $error['customer_email'] = 'Bạn chưa nhập Emai';
            }
            if(checkGlobal(_DB_TABLE_CUSTOMER, array('mail' => $customer_email)) > 0){
                $error['customer_email'] = 'Email này đã tồn tại';
            }
            if(!filter_var($customer_email, FILTER_VALIDATE_EMAIL)){
                $error['customer_email'] = 'Email chưa đúng định dạng';
            }
            if(!$customer_phone){
                $error['customer_phone'] = 'Bạn chưa nhập số điện thoại';
            }
            if(checkGlobal(_DB_TABLE_CUSTOMER, array('phone' => $customer_phone)) > 0){
                $error['customer_phone'] = 'Số điện thoại này đã tồn tại';
            }
            if(!$error){
                $date   = date('d/m/Y H:i:s', _CONFIG_TIME);
                $pass   = md5($customer_pass);
                $colum  = array('[deviceID]', '[cusID]' ,'[password]','[fullname]','[mail]','[phone]', '[phone_company]', '[timeReg]','[status]', '[addr_receive]');
                $data   = array("'$customer_device'", "'$customer_code'", "'$pass'", "N'$customer_name'", "'$customer_email'", "'$customer_phone'", "'$customer_phone_company'", "'$date'", 0, "N'$customer_address'");
                if(insertSqlserver(_DB_TABLE_CUSTOMER, $colum, $data)){
                    header('location:'._URL_ADMIN.'/customer.php');
                }
            }
        }
        $admin_title    = 'Thêm mới khách hàng';
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
                                        <h4 class="form-section"><i class="ft-slack"></i> Thông tin khách hàng</h4>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Mã khách hàng</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Mã khách hàng" name="customer_code" value="<?php echo $customer_code;?>" />
                                                <?php echo ($error['customer_code']) ? '<small style="color: red"><i>'. $error['customer_code'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Mã thiết bị sử dụng</label>
                                            <div class="col-md-9">
                                                <select name="customer_device" class="form-control round">
                                                    <?php
                                                    foreach (getGlobalAll(_DB_TABLE_DEVICE, '') AS $select_device){
                                                        echo '<option value="'. $select_device['deviceID'] .'">'. $select_device['deviceID'] .'</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <?php echo ($error['customer_device']) ? '<small style="color: red"><i>'. $error['customer_device'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Mật khẩu</label>
                                            <div class="col-md-9">
                                                <input type="password" class="form-control" required placeholder="Mật khẩu" name="customer_pass" value="<?php echo $customer_pass;?>">
                                                <?php echo ($error['customer_pass']) ? '<small style="color: red"><i>'. $error['customer_pass'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Nhập lại mật khẩu</label>
                                            <div class="col-md-9">
                                                <input type="password" class="form-control" required placeholder="Nhập lại mật khẩu" name="customer_repass" value="<?php echo $customer_repass;?>" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Tên khách hàng</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Tên khách hàng" name="customer_name" value="<?php echo $customer_name;?>" />
                                                <?php echo ($error['customer_name']) ? '<small style="color: red"><i>'. $error['customer_name'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Email khách hàng</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="Email khách hàng" name="customer_email" value="<?php echo $customer_email;?>">
                                                <?php echo ($error['customer_email']) ? '<small style="color: red"><i>'. $error['customer_email'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Số điện thoại khách hàng</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Số điện thoại khách hàng" name="customer_phone" value="<?php echo $customer_phone;?>">
                                                <?php echo ($error['customer_phone']) ? '<small style="color: red"><i>'. $error['customer_phone'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Số điện thoại công ty</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Số điện thoại công ty" name="customer_phone_company" value="<?php echo $customer_phone_company;?>">
                                                <?php echo ($error['customer_phone_company']) ? '<small style="color: red"><i>'. $error['customer_phone_company'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="projectinput9">Địa chỉ</label>
                                            <div class="col-md-9">
                                                <textarea rows="5" class="form-control" name="customer_address" placeholder="Địa chỉ khách hàng"><?php echo $customer_address;?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions text-center">
                                        <button type="button" class="btn btn-warning mr-1" onclick=""><i class="ft-x"></i> Cancel</button>
                                        <input type="submit" name="submit" class="btn btn-primary" value="Thêm khách hàng" />
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
        $admin_title    = 'Danh sách khách hàng';
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách các khách hàng</h4>
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
                        <!-- Pagination -->
                        <?php
                        $query_pagenum                  = 'SELECT [id] FROM '._DB_TABLE_CUSTOMER.' '.$parameters_list;
                        $query_pagenum                  = sqlsrv_query($connection, $query_pagenum, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
                        $page_num                       = sqlsrv_num_rows($query_pagenum);
                        $config_pagenavi['page_row']    = _CONFIG_PAGINATION;
                        $config_pagenavi['page_num']    = ceil($page_num/$config_pagenavi['page_row']);
                        $config_pagenavi['url']         = _URL_ADMIN.'/customer.php?';
                        $page_start                     = ($page-1) * $config_pagenavi['page_row'];
                        $page_start                     = $page_start == 0 ? 1 : $page_start+1;
                        $query                          = 'SELECT * FROM (SELECT ROW_NUMBER() OVER(ORDER BY id DESC) AS RowNumber,cusID,deviceID,addr_receive,fullname,mail,phone,phone_company,timeReg,status FROM '. _DB_TABLE_CUSTOMER .' '. $parameters_list .') AS Temp WHERE RowNumber BETWEEN '. $page_start .' AND '.($page_start + ($config_pagenavi['page_row'] - 1));
                        //echo $query; exit();
                        $data                           = getGlobalAll(_DB_TABLE_CUSTOMER, '', array('query' => $query));
                        echo '<div class="table-responsive">';
                        echo '<table class="table">';
                        echo '<thread>';
                        echo '<tr>';
                        echo '<th>Mã khách hàng</th>';
                        echo '<th>Mã thiết bị</th>';
                        echo '<th>Tên khách hàng</th>';
                        echo '<th>Điện thoại</th>';
                        echo '<th>Điện thoại công ty</th>';
                        echo '<th>Ngày đăng ký</th>';
                        echo '<th>Địa chỉ</th>';
                        echo '<th>Trạng thái</th>';
                        echo '</tr>';
                        echo '</thread>';
                        echo '<tbody>';
                        foreach ($data AS $customers){
                            if($customers['status'] == 0){
                                $status = '<font color="green">'. getStatusDevice($customers['status']) .'</font>';
                            }else if ($customers['status'] == 1){
                                $status = '<font color="red">'. getStatusDevice($customers['status']) .'</font>';
                            }
                            echo '<tr>';
                            echo '<td><a href="'. _URL_ADMIN .'/customer.php?act=update&id='. $customers['id'] .'">'. $customers['cusID'] .'</a><br /><a href="'. _URL_ADMIN .'/customer.php?act=update&id='. $customers['id'] .'">Sửa</a> - <a href="'. _URL_ADMIN .'/customer.php?act=delete&id='. $customers['id'] .'" style="color: red">Xóa</a></td>';
                            echo '<td>'. $customers['deviceID'] .'</td>';
                            echo '<td>'. $customers['fullname'] .'</td>';
                            echo '<td>'. $customers['phone'] .'</td>';
                            echo '<td>'. $customers['phone_company'] .'</td>';
                            echo '<td>'. $customers['timeReg']->format('d/m/Y') .'</td>';
                            echo '<td>'. $customers['addr_receive'] .'</td>';
                            echo '<td>'. $status .'</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                        echo '<nav aria-label="Page navigation">'.pagination($config_pagenavi).'</nav>';
                        ?>
                        <!-- Pagination -->
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
}
require_once 'footer.php';