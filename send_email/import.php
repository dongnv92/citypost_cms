<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 2018-11-08
 * Time: 08:43
 */
require_once 'includes/core.php';
require_once 'includes/class/PHPExcel.php';
$module = 'import';
if($submit){
    $file_name      = $_POST['file_name']       ? $_POST['file_name']       : '';
    $file_location  = $_POST['file_location']   ? $_POST['file_location']   : '';
    $file_ver       = $_POST['file_ver']        ? $_POST['file_ver']        : '';
    $excel_name     = $_POST['excel_name']      ? $_POST['excel_name']      : '';
    $excel_email    = $_POST['excel_email']     ? $_POST['excel_email']     : '';
    $error          = '';
    if(!$file_name){
        $error['file_name'] = '<small class="danger">Bạn Chưa Nhập Tên File</small>';
    }else if(!file_exists('includes/data/'.$file_name.'.xlsx')){
        $error['file_name'] = '<small class="danger">File Không Tồn Tại</small>';
    }
    if(!$error){
        $file = 'includes/data/'.$file_name.'.xlsx';
        $objFile = PHPExcel_IOFactory::identify($file);
        $objData = PHPExcel_IOFactory::createReader($objFile);
        $objData->setReadDataOnly(true);
        $objPHPExcel = $objData->load($file);
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $Totalrow = $sheet->getHighestRow();
        $LastColumn = $sheet->getHighestColumn();
        $TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
        $data = [];
        for ($i = 2; $i <= $Totalrow; $i++) {
            for ($j = 0; $j < $TotalCol; $j++) {
                $data[$i - 2][$j] = $sheet->getCellByColumnAndRow($j, $i)->getValue();;
            }
        }
    }
}

$header['title'] = 'Nhập Email Từ File Excel';
require_once 'header.php';
?>
<form action="" method="post">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title"><?php echo $header['title'];?></h4> </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <input class="form-control round border-blue" value="<?=$file_name?>" type="text" name="file_name" placeholder="Nhập Tên File Excel Có Trong Thư Mục includes/data/" autofocus>
                            <?php echo $error['file_name'] ? $error['file_name'] : '';?>
                        </div>
                        <div class="col-2">
                            <select class="form-control round border-blue" name="file_location">
                                <option value="" <?=$file_location == '' ? 'selected' : ''?>>Không xác định</option>
                                <option value="hn" <?=$file_location == 'hn' ? 'selected' : ''?>>Hà Nội</option>
                                <option value="dn" <?=$file_location == 'dn' ? 'selected' : ''?>>Đà Nẵng</option>
                                <option value="hcm" <?=$file_location == 'hcm' ? 'selected' : ''?>>HCM</option>
                                <option value="bd" <?=$file_location == 'bd' ? 'selected' : ''?>>Bình Dương</option>
                            </select>
                            <?php echo $error['file_location'] ? $error['file_location'] : '';?>
                        </div>
                        <div class="col-2">
                            <input class="form-control round border-blue" value="<?=$file_ver?>" type="text" name="file_ver" placeholder="Mã Version">
                            <?php echo $error['file_ver'] ? $error['file_ver'] : '';?>
                        </div>
                        <div class="col-2">
                            <input class="form-control round border-blue" value="<?=$excel_email?>" type="number" name="excel_email" placeholder="Cột Chứa Email">
                            <?php echo $error['excel_email'] ? $error['excel_email'] : '';?>
                        </div>
                        <div class="col-2">
                            <input class="form-control round border-blue" type="number" value="<?=$excel_name?>" name="excel_name" placeholder="Cột Chứa Tên Công Ty">
                            <?php echo $error['excel_name'] ? $error['excel_name'] : '';?>
                        </div>
                        <div class="col-1">
                            <input type="submit" name="submit" value="Nhập" class="btn round btn-outline-blue" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <?php if($data) {?>
            <div class="card">
                <div class="card-header"><h4 class="card-title">Kết Quả</h4> </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Email</th>
                                    <th>Địa Chỉ</th>
                                    <th>Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 0;
                            foreach ($data AS $email){
                                $detail_email   = trim($email[$excel_email]);
                                $detail_name    = trim($email[$excel_name]);
                                if($detail_email != '' && filter_var($detail_email, FILTER_VALIDATE_EMAIL)){
                                    $i++;
                                    $response = getApi('add_email', array('email' => $detail_email, 'email_name' => $detail_name, 'email_location' => $file_location, 'email_ver' => $file_ver, 'list_unsubcrice' => '0'));
                                    echo '<tr><td>'. $i .'</td><td>'. $detail_email .'</td><td>'. $detail_name .'</td><td class="text success">'. $response['message'] .'</td></tr>';
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php }?>
        </div>
    </div>
</form>
<?php
require_once 'footer.php';
