<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 2018-11-07
 * Time: 16:28
 */

require_once 'includes/core.php';
$module = 'email';

switch ($act){
    case 'statics':
        $number_email       = getApi('statics', array('type' => 'number_email'));
        $header['title']    = 'Thống Kê Email';
        require_once 'header.php'
        ?>
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">SỐ LƯỢNG EMAIL</h4>
                <p>Số lượng email có trong hệ thống để gửi</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-12 border-right-blue-grey border-right-lighten-5">
                                    <div class="float-left pl-2">
                                        <span class="font-large-3 text-bold-300"><?=$number_email['email_all']?></span>
                                    </div>
                                    <div class="float-left mt-2 ml-1">
                                        <span class="blue-grey darken-1 block">Tổng</span>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-12 border-right-blue-grey border-right-lighten-5">
                                    <div class="float-left pl-2">
                                        <span class="font-large-3 text-bold-300"><?=$number_email['email_hn']?></span>
                                    </div>
                                    <div class="float-left mt-2 ml-1">
                                        <span class="blue-grey darken-1 block">Hà Nội</span>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-12 border-right-blue-grey border-right-lighten-5">
                                    <div class="float-left pl-2">
                                        <span class="font-large-3 text-bold-300"><?=$number_email['email_dn']?></span>
                                    </div>
                                    <div class="float-left mt-2 ml-1">
                                        <span class="blue-grey darken-1 block">Đà Nẵng</span>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-12">
                                    <div class="float-left pl-2">
                                        <span class="font-large-3 text-bold-300"><?=$number_email['email_hcm']?></span>
                                    </div>
                                    <div class="float-left mt-2 ml-1">
                                        <span class="blue-grey darken-1 block">HỒ CHÍ MINH</span>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-12">
                                    <div class="float-left pl-2">
                                        <span class="font-large-3 text-bold-300"><?=$number_email['email_bd']?></span>
                                    </div>
                                    <div class="float-left mt-2 ml-1">
                                        <span class="blue-grey darken-1 block">BÌNH DƯƠNG</span>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-12">
                                    <div class="float-left pl-2">
                                        <span class="font-large-3 text-bold-300"><?=$number_email['email_empty']?></span>
                                    </div>
                                    <div class="float-left mt-2 ml-1">
                                        <span class="blue-grey darken-1 block">Unknown</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section id="stats-icon-subtitle-bg">
            <div class="row">
                <div class="col-12 mt-3 mb-1">
                    <h4 class="text-uppercase">THỐNG KÊ TẦN SUẤT GỬI EMAIL</h4>
                    <p>Tần suất gửi Email hằng ngày, tuần, tháng, năm và số lần gửi thành công / không thành công.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-content">
                            <div class="media align-items-stretch bg-gradient-x-info text-white rounded">
                                <div class="p-2 media-middle">
                                    <i class="ft-heart font-large-2 text-white"></i>
                                </div>
                                <div class="media-body p-2">
                                    <h4 class="text-white">Hôm nay</h4>
                                    <span>Lượt gửi Email hôm nay</span>
                                </div>
                                <div class="media-right p-2 media-middle">
                                    <h1><strong class="text-white"><?=$number_email['send_today_success']?></strong> / <strong class="text-danger"><?=$number_email['send_today_false']?></strong> </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="media align-items-stretch bg-gradient-x-warning text-white rounded">
                                <div class="p-2 media-middle">
                                    <i class="ft-heart font-large-2 text-white"></i>
                                </div>
                                <div class="media-body p-2">
                                    <h4 class="text-white">Tuần này</h4>
                                    <span>Lượt gửi Email tuần này</span>
                                </div>
                                <div class="media-right p-2 media-middle">
                                    <h1><strong class="text-white"><?=$number_email['send_week_success']?></strong> / <strong class="text-danger"><?=$number_email['send_week_false']?></strong> </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="media align-items-stretch bg-gradient-x-danger text-white rounded">
                                <div class="p-2 media-middle">
                                    <i class="ft-heart font-large-2 text-white"></i>
                                </div>
                                <div class="media-body p-2">
                                    <h4 class="text-white">Tháng này</h4>
                                    <span>Lượt gửi Email tháng này</span>
                                </div>
                                <div class="media-right p-2 media-middle">
                                    <h1><strong class="text-white"><?=$number_email['send_month_success']?></strong> / <strong class="text-danger"><?=$number_email['send_month_false']?></strong> </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="media align-items-stretch bg-gradient-x-success text-white rounded">
                                <div class="p-2 media-middle">
                                    <i class="ft-heart font-large-2 text-white"></i>
                                </div>
                                <div class="media-body p-2">
                                    <h4 class="text-white">Năm này</h4>
                                    <span>Lượt gửi Email năm này</span>
                                </div>
                                <div class="media-right p-2 media-middle">
                                    <h1><strong class="text-white"><?=$number_email['send_year_success']?></strong> / <strong class="text-danger"><?=$number_email['send_year_false']?></strong> </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="media align-items-stretch">
                                    <div class="align-self-center">
                                        <i class="la la-angellist info font-large-2 mr-2"></i>
                                    </div>
                                    <div class="media-body">
                                        <h4>Thành công</h4>
                                        <span>Tổng số lần gửi thành công</span>
                                    </div>
                                    <div class="align-self-center">
                                        <h1><?=$number_email['send_all_success']?></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="media align-items-stretch">
                                    <div class="align-self-center">
                                        <i class="la la-frown-o warning font-large-2 mr-2"></i>
                                    </div>
                                    <div class="media-body">
                                        <h4>Không thành công</h4>
                                        <span>Tổng số lần gửi không thành công</span>
                                    </div>
                                    <div class="align-self-center">
                                        <h1><?=$number_email['send_all_false']?></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        require_once 'footer.php';
        break;
    case 'send':
        $header['title'] = 'Cấu Hình Gửi Email Marketing';
        $css_plus       = array(
            'app-assets/vendors/css/extensions/toastr.css',
            'app-assets/css/plugins/extensions/toastr.css',
            'app-assets/vendors/css/forms/icheck/icheck.css',
            'app-assets/vendors/css/forms/icheck/custom.css',
            'app-assets/vendors/css/editors/tinymce/tinymce.min.css',
            'app-assets/vendors/css/forms/selects/select2.min.css',
            'app-assets/vendors/css/forms/toggle/bootstrap-switch.min.css',
            'app-assets/vendors/css/forms/toggle/switchery.min.css',
            'app-assets/css/plugins/forms/switch.min.css',
            'app-assets/css/core/colors/palette-switch.min.css',
            'app-assets/vendors/css/extensions/sweetalert.css'
        );
        $js_plus        = array(
            'app-assets/vendors/js/forms/icheck/icheck.min.js',
            'app-assets/js/scripts/forms/checkbox-radio.js',
            'app-assets/vendors/js/editors/tinymce/tinymce.js',
            'app-assets/js/scripts/editors/editor-tinymce.min.js',
            'app-assets/vendors/js/forms/select/select2.full.min.js',
            'app-assets/js/scripts/forms/select/form-select2.min.js',
            'app-assets/vendors/js/forms/toggle/bootstrap-switch.min.js',
            'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
            'app-assets/vendors/js/forms/toggle/switchery.min.js',
            'app-assets/js/scripts/forms/switch.min.js',
            'app-assets/vendors/js/extensions/sweetalert.min.js',
            'app-assets/js/scripts/extensions/sweet-alerts.min.js',
            'app-assets/vendors/js/extensions/toastr.min.js',
            ''
        );
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col-9">
                <div class="card">
                    <div class="card-header"><h4 class="card-title"><?php echo $header['title'];?></h4> </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <label class="label">Số Lượng</label>
                                <input type="number" name="email_top" min="1" max="50" class="form-control round border-blue" value="50" placeholder="Số Lượng" autofocus>
                            </div>
                            <div class="col">
                                <label class="label">Khu Vực</label>
                                <select id="email_location" name="email_location" class="form-control round border-blue">
                                    <option value="">Khu Vực</option>
                                    <option value="hn">Hà Nội</option>
                                    <option value="dn">Đà Nẵng</option>
                                    <option value="hcm">Hồ Chí Minh</option>
                                    <option value="bd">Bình Dương</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="label">Phiên Bản Excel Khách Hàng</label>
                                <select id="email_ver" name="email_ver" class="form-control round border-blue">
                                    <option value="">Tất Cả</option>
                                    <?php
                                    $list_ver = getApi('get_distince_ver_email_list');
                                    foreach ($list_ver AS $ver){
                                        echo '<option value="'. $ver['list_ver'] .'">'. $ver['list_ver'] .'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <label class="label">Số Lượng Đã Gửi</label>
                                <input type="number" name="email_number" min="1" max="20" value="0" class="form-control round border-blue" placeholder="Số Lượng Đã Gửi">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" id="result_parent" style="display: none;">
                    <div class="card-header"><h4 class="card-title">Kết Quả</h4> </div>
                    <div class="card-body" id="result"></div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center">
                        <p class="text-left">
                            <input type="checkbox" id="switcherySize2" class="switchery" name="type" value="rand" data-size="sm" checked />
                            <label for="switcherySize2" class="font-medium-2 text-bold-600 ml-1">Gửi ngẫu nhiên</label><br />
                        </p>
                        <button id="send" class="btn round btn-outline-blue" style="width: 100%">Gửi Email</button>
                        <img id="images_loading" src="http://xoidua.com/media/images/system/gif/<?php echo rand(1,17)?>.gif" height="60px" style="display: none">
                    </div>
                </div>
            </div>
        </div>
        <?php
        require_once 'footer.php';
        break;
    case 'add':
        $header['title'] = 'Thêm Email Vào Danh Sách';
        $css_plus = array(
            'app-assets/vendors/css/extensions/toastr.css',
            'app-assets/css/plugins/extensions/toastr.css',
            'app-assets/vendors/css/extensions/sweetalert.css'
        );
        $js_plus = array(
            'app-assets/vendors/js/extensions/toastr.min.js',
            'app-assets/vendors/js/extensions/sweetalert.min.js',
            'app-assets/js/scripts/extensions/sweet-alerts.min.js'
        );
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header"><h4 class="card-title"><?php echo $header['title'];?></h4> </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="text" class="round form-control border-blue" name="email_address" autofocus placeholder="Địa Chỉ Email">
                        </div>
                        <div class="form-group">
                            <input type="text" class="round form-control border-blue" value="Quý công ty" name="email_name" placeholder="Tên công ty">
                        </div>
                        <div class="form-group">
                            <input type="text" class="round form-control border-blue" name="email_ver" placeholder="Phiên bản Email">
                        </div>
                        <div class="form-group">
                            <select name="email_location" class="form-control round border-blue">
                                <option value="">Chọn Khu Vực</option>
                                <option value="hn">Hà Nội</option>
                                <option value="dn">Đà Nẵng</option>
                                <option value="hcm">Hồ Chí Minh</option>
                                <option value="bd">Bình Dương</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <button style="width: 100%" id="add_email" class="btn round btn-outline-blue">Thêm Email</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require_once 'footer.php';
        break;
    case 'add_list':
        if($submit){
            $list_email     = $_REQUEST['list_email']       ? $_REQUEST['list_email']       : '';
            $email_name     = $_REQUEST['email_name']       ? $_REQUEST['email_name']       : '';
            $email_ver      = $_REQUEST['email_ver']        ? $_REQUEST['email_ver']        : '';
            $email_location = $_REQUEST['email_location']   ? $_REQUEST['email_location']   : '';
            $list_emails    = explode("\n", $list_email);
            $datas          = array();
            foreach ($list_emails as $email){
                if(filter_var(trim($email), FILTER_VALIDATE_EMAIL)){
                    $response   = getApi('add_email', array('email' => $email, 'email_name' => $email_name, 'email_location' => $email_location, 'email_ver' => $email_ver, 'list_unsubcrice' => '0'));
                    $datas[]    = $response['message'];
                }
            }
        }
        $header['title'] = 'Thêm Email Vào Danh Sách';
        $css_plus = array(
            'app-assets/vendors/css/extensions/toastr.css',
            'app-assets/css/plugins/extensions/toastr.css',
            'app-assets/vendors/css/extensions/sweetalert.css'
        );
        $js_plus = array(
            'app-assets/vendors/js/extensions/toastr.min.js',
            'app-assets/vendors/js/extensions/sweetalert.min.js',
            'app-assets/js/scripts/extensions/sweet-alerts.min.js'
        );
        require_once 'header.php';
        ?>
        <form action="" method="post" class="form-control">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title"><?php echo $header['title'];?></h4> </div>
                        <div class="card-body">
                            <div class="form-group">
                                <textarea name="list_email" class="form-group round" style="width: 100%" placeholder="Mỗi Email là 1 dòng" autofocus rows="20"><?=$list_email?></textarea>
                            </div>
                            <div class="form-group">
                                <input type="text" class="round form-control border-blue" value="<?=$email_name?>" name="email_name" placeholder="Tên công ty">
                            </div>
                            <div class="form-group">
                                <input type="text" class="round form-control border-blue" name="email_ver" value="<?=$email_ver?>" placeholder="Phiên bản Email">
                            </div>
                            <div class="form-group">
                                <select name="email_location" class="form-control round border-blue">
                                    <option value="">Chọn Khu Vực</option>
                                    <option value="hn">Hà Nội</option>
                                    <option value="dn">Đà Nẵng</option>
                                    <option value="hcm">Hồ Chí Minh</option>
                                    <option value="bd">Bình Dương</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php if($datas) {?>
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Kết Quả</h4> </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Trạng Thái</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($datas AS $data){
                                            $i++;
                                            echo '<tr><td>'. $i .'</td><td class="text success">'. $data .'</td></tr>';
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <input type="submit" name="submit" value="Thêm Email" class="btn round btn-outline-blue">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
        require_once 'footer.php';
        break;
}