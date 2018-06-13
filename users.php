<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 05/06/2018
 * Time: 11:16
 */
require_once 'includes/core.php';
if(!$user_id){
    header('location:'._URL_HOME.'/login.php');
}
$admin_active = 'users';

switch ($act){
    case 'delete':
        $users          = getGlobalAll(_DB_TABLE_USERS, array('id' => $id), array('onecolum' => 'limit'));
        if(!$users){
            header('location:'._URL_ADMIN.'/users.php');
        }
        if($submit){
            if(deleteGlobal(_DB_TABLE_USERS, array('id' => $id))){
                header('location:'._URL_ADMIN.'/users.php');
            }
        }
        $admin_title = 'Xóa thành viên';
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header"><h4 class="card-title"><?php echo $admin_title;?></h4> </div>
                    <div class="card-content text-center">
                        <form action="" method="post">
                            Bạn có chắc muốn xóa thành viên <strong style="color: red;"><i><?php echo $users['fullname'];?></i></strong> này không?
                            <div class="form-actions text-center">
                                <input type="submit" name="submit" class="btn btn-primary" value="Xóa thành viên này" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
    case 'update':
        $users          = getGlobalAll(_DB_TABLE_USERS, array('id' => $id), array('onecolum' => 'limit'));
        if(!$users){
            header('location:'._URL_ADMIN.'/users.php');
        }
        if($submit){
            $users_login    = (isset($_POST['users_login'])     && !empty($_POST['users_login']))   ? $_POST['users_login'] : '';
            $users_pass     = (isset($_POST['users_pass'])      && !empty($_POST['users_pass']))    ? $_POST['users_pass']  : '';
            $users_repass   = (isset($_POST['users_repass'])    && !empty($_POST['users_repass']))  ? $_POST['users_repass']: '';
            $users_name     = (isset($_POST['users_name'])      && !empty($_POST['users_name']))    ? $_POST['users_name']  : '';
            $users_phone    = (isset($_POST['users_phone'])     && !empty($_POST['users_phone']))   ? $_POST['users_phone'] : '';
            $users_email    = (isset($_POST['users_email'])     && !empty($_POST['users_email']))   ? $_POST['users_email'] : '';
            $users_status   = (isset($_POST['users_status'])    && !empty($_POST['users_status']))  ? $_POST['users_status']: '';
            $users_rule     = (isset($_POST['users_rule'])      && !empty($_POST['users_rule']))    ? $_POST['users_rule']  : '';
            $error          = array();
            if(!$users_login){
                $error['users_login'] = 'Bạn chưa nhập tên đăng nhập';
            }
            if(($users_login != $users['username']) && checkGlobal(_DB_TABLE_USERS, array('username' => $users_login)) > 0){
                $error['users_login'] = 'Tên đăng nhập này đã tồn tại';
            }
            if(($users_pass && $users_repass) && ($users_pass != $users_repass)){
                $error['users_pass'] = 'Hai mật khẩu không giống nhau.';
            }
            if(!$users_name){
                $error['users_name'] = 'Bạn chưa nhập tên thành viên';
            }
            if(!$users_email){
                $error['users_email'] = 'Bạn chưa nhập Emai';
            }
            if(($users_email != $users['email']) && checkGlobal(_DB_TABLE_USERS, array('email' => $users_email)) > 0){
                $error['users_email'] = 'Email này đã tồn tại';
            }
            if(!filter_var($users_email, FILTER_VALIDATE_EMAIL)){
                $error['users_email'] = 'Email chưa đúng định dạng';
            }
            if(!$users_phone){
                $error['users_phone'] = 'Bạn chưa nhập số điện thoại';
            }
            if(($users_phone != $users['phone']) && checkGlobal(_DB_TABLE_USERS, array('phone' => $users_phone)) > 0){
                $error['users_email'] = 'Số điện thoại này đã tồn tại';
            }
            if(!$error){
                $pass   = md5($users_pass);

                if($users_pass && $users_repass){
                    $data   = array(
                        'username'  => $users_login,
                        'password'  => $pass,
                        'rule'      => $users_rule,
                        'email'     => $users_email,
                        'fullname'  => $users_name,
                        'status'    => $users_status,
                        'phone'     => $users_phone
                    );
                    $where  = array('id' => $id);
                }else{
                    $data   = array(
                        'username'  => $users_login,
                        'rule'      => $users_rule,
                        'email'     => $users_email,
                        'fullname'  => $users_name,
                        'status'    => $users_status,
                        'phone'     => $users_phone
                    );
                    $where  = array('id' => $id);
                }
                updateGlobal(_DB_TABLE_USERS, $data, $where);
                $users          = getGlobalAll(_DB_TABLE_USERS, array('id' => $id), array('onecolum' => 'limit'));
            }
        }
        $admin_title = 'Thêm thành viên';
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
                                <?php if($submit && !$error){echo '<div class="alert alert-icon-left alert-arrow-left alert-success alert-dismissible mb-2" role="alert">
                                <span class="alert-icon"><i class="la la-thumbs-o-up"></i></span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                                <strong>Done!</strong> Chỉnh sửa thành công.</div>';}?>
                                <form class="form form-horizontal" method="post" action="">
                                    <div class="form-body">
                                        <h4 class="form-section"><i class="ft-slack"></i> Thông tin thành viên</h4>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Tên đăng nhập</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Tên đăng nhập" name="users_login" value="<?php echo $users['username'];?>" />
                                                <?php echo ($error['users_login']) ? '<small style="color: red"><i>'. $error['users_login'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Mật khẩu</label>
                                            <div class="col-md-9">
                                                <input type="password" class="form-control" placeholder="Mật khẩu" name="users_pass" value="<?php echo $users_pass;?>">
                                                <?php echo ($error['users_pass']) ? '<small style="color: red"><i>'. $error['users_pass'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Nhập lại mật khẩu</label>
                                            <div class="col-md-9">
                                                <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" name="users_repass" value="<?php echo $users_repass;?>" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Tên thành viên</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Tên thành viên" name="users_name" value="<?php echo $users['fullname'];?>" />
                                                <?php echo ($error['users_name']) ? '<small style="color: red"><i>'. $error['users_name'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Email thành viên</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="Email thành viên" name="users_email" value="<?php echo $users['email'];?>">
                                                <?php echo ($error['users_email']) ? '<small style="color: red"><i>'. $error['users_email'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Số điện thoại</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Số điện thoại" name="users_phone" value="<?php echo $users['phone'];?>">
                                                <?php echo ($error['users_phone']) ? '<small style="color: red"><i>'. $error['users_phone'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Trạng thái</label>
                                            <div class="col-md-9">
                                                <select name="users_status" class="form-control">
                                                    <option value="<?php echo $users['status'];?>"><?php echo getStatusUser($users['status']);?></option>
                                                    <option value="0"><?php echo getStatusUser(0);?></option>
                                                    <option value="1"><?php echo getStatusUser(1);?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Phân quyền</label>
                                            <div class="col-md-9">
                                                <select name="users_rule" class="form-control">
                                                    <option value="<?php echo $users['rule'];?>"><?php echo getStatusUserRule($users['rule']);?></option>
                                                    <option value="0"><?php echo getStatusUserRule(0);?></option>
                                                    <option value="1"><?php echo getStatusUserRule(1);?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions text-center">
                                        <button type="button" class="btn btn-warning mr-1" onclick=""><i class="ft-x"></i> Cancel</button>
                                        <input type="submit" name="submit" class="btn btn-primary" value="Cập nhập thành viên" />
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
            $users_login   = (isset($_POST['users_login'])    && !empty($_POST['users_login']))  ? $_POST['users_login']    : '';
            $users_pass    = (isset($_POST['users_pass'])     && !empty($_POST['users_pass']))   ? $_POST['users_pass']     : '';
            $users_repass  = (isset($_POST['users_repass'])   && !empty($_POST['users_repass'])) ? $_POST['users_repass']   : '';
            $users_name    = (isset($_POST['users_name'])     && !empty($_POST['users_name']))   ? $_POST['users_name']     : '';
            $users_phone   = (isset($_POST['users_phone'])    && !empty($_POST['users_phone']))  ? $_POST['users_phone']    : '';
            $users_email   = (isset($_POST['users_email'])    && !empty($_POST['users_email']))  ? $_POST['users_email']    : '';
            $error         = array();
            if(!$users_login){
                $error['users_login'] = 'Bạn chưa nhập tên đăng nhập';
            }
            if(checkGlobal(_DB_TABLE_USERS, array('username' => $users_login)) > 0){
                $error['users_login'] = 'Tên đăng nhập này đã tồn tại';
            }
            if(!$users_pass || !$users_repass){
                $error['users_pass'] = 'Bạn chưa nhập mật khẩu';
            }
            if($users_pass != $users_repass){
                $error['users_pass'] = 'Hai mật khẩu không giống nhau.';
            }
            if(!$users_name){
                $error['users_name'] = 'Bạn chưa nhập tên thành viên';
            }
            if(!$users_email){
                $error['users_email'] = 'Bạn chưa nhập Emai';
            }
            if(checkGlobal(_DB_TABLE_USERS, array('email' => $users_email)) > 0){
                $error['users_email'] = 'Email này đã tồn tại';
            }
            if(!filter_var($users_email, FILTER_VALIDATE_EMAIL)){
                $error['users_email'] = 'Email chưa đúng định dạng';
            }
            if(!$users_phone){
                $error['users_phone'] = 'Bạn chưa nhập số điện thoại';
            }
            if(checkGlobal(_DB_TABLE_USERS, array('phone' => $users_phone)) > 0){
                $error['users_phone'] = 'Số điện thoại này đã tồn tại';
            }
            if(!$error){
                $date   = date('d/m/Y H:i:s', _CONFIG_TIME);
                $pass   = md5($users_pass);
                $colum  = array('[username]','[password]','[rule]','[email]','[phone]','[fullname]','[status]');
                $data   = array("'$users_login'", "'$pass'", 0, "'$users_email'", "'$users_phone'", "N'$users_name'", 0);
                if(insertSqlserver(_DB_TABLE_USERS, $colum, $data)){
                    header('location:'._URL_ADMIN.'/users.php');
                }
            }
        }
        $admin_title = 'Thêm thành viên';
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
                                        <h4 class="form-section"><i class="ft-slack"></i> Thông tin thành viên</h4>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Tên đăng nhập</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Tên đăng nhập" name="users_login" value="<?php echo $users_login;?>" />
                                                <?php echo ($error['users_login']) ? '<small style="color: red"><i>'. $error['users_login'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Mật khẩu</label>
                                            <div class="col-md-9">
                                                <input type="password" class="form-control" required placeholder="Mật khẩu" name="users_pass" value="<?php echo $users_pass;?>">
                                                <?php echo ($error['users_pass']) ? '<small style="color: red"><i>'. $error['users_pass'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Nhập lại mật khẩu</label>
                                            <div class="col-md-9">
                                                <input type="password" class="form-control" required placeholder="Nhập lại mật khẩu" name="users_repass" value="<?php echo $users_repass;?>" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Tên thành viên</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Tên thành viên" name="users_name" value="<?php echo $users_name;?>" />
                                                <?php echo ($error['users_name']) ? '<small style="color: red"><i>'. $error['users_name'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Email thành viên</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="Email thành viên" name="users_email" value="<?php echo $users_email;?>">
                                                <?php echo ($error['users_email']) ? '<small style="color: red"><i>'. $error['users_email'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">Số điện thoại</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" required placeholder="Số điện thoại" name="users_phone" value="<?php echo $users_phone;?>">
                                                <?php echo ($error['users_phone']) ? '<small style="color: red"><i>'. $error['users_phone'] .'</i></small>' : '';?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions text-center">
                                        <button type="button" class="btn btn-warning mr-1" onclick=""><i class="ft-x"></i> Cancel</button>
                                        <input type="submit" name="submit" class="btn btn-primary" value="Thêm thành viên" />
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
        $admin_title    = 'Danh sách thành viên';
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách thành viên</h4>
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
                                    <th>Tên đăng nhập</th>
                                    <th>Tên hiển thị</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Phân quyền</th>
                                    <th>Trạng thái</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $device = getGlobalAll(_DB_TABLE_USERS, '');
                                foreach ($device AS $devices){
                                    echo '<tr>';
                                    echo '<td>'. $devices['username'] .'<br /><a href="'. _URL_ADMIN .'/users.php?act=update&id='. $devices['id'] .'">Sửa</a> - <a href="'. _URL_ADMIN .'/users.php?act=delete&id='. $devices['id'] .'" style="color: red">Xóa</a></td>';
                                    echo '<td>'. $devices['fullname'] .'</td>';
                                    echo '<td>'. $devices['email'] .'</td>';
                                    echo '<td>'. $devices['phone'] .'</td>';
                                    echo '<td>'. getStatusUserRule($devices['rule']) .'</td>';
                                    echo '<td>'. getStatusUser($devices['status']) .'</td>';
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