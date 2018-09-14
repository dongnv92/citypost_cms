<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 04/06/2018
 * Time: 15:34
 */
 
 /*-------------------Start functuon by Mr Phu----------------------------*/ 
function GetDataByID($field,$query){
    $result = "";
    global $connection;
    if(!$query){
        return false;
    }
    else{
        $re  = sqlsrv_query($connection, $query);
        // Debug
        if($re == FALSE){

            die('error: '.FormatErrors(sqlsrv_errors($connection)));
        }else{
            while ($rw = sqlsrv_fetch_object($re)){
                $result = $rw->$field;
            }
        }

    }
    return $result;

}
function GetMethoByTransID($transID){
    $method = "";
    global $connection;
    if(!$transID){
        return false;
    }
    else{
        $query  = "SELECT * FROM tblSMSReceive WHERE transID='$transID'";
        //echo $query;
        $re  = sqlsrv_query($connection, $query);
        // Debug
        if($re == FALSE){

            die('error: '.FormatErrors(sqlsrv_errors($connection)));
        }else{
            while ($rw = sqlsrv_fetch_object($re)){
                $method = $rw->method;
            }
        }

    }
    return $method;
}
function InsertData($table, $colum, $data){
    global $connection;
    if (!$table || !$data || !$colum) {
        return false;
    } else {
        $query  = "INSERT INTO $table($colum) VALUES($data)";
        $query  = sqlsrv_query($connection, $query);
        // Debug
        if($query == FALSE){

            die('error: '.FormatErrors(sqlsrv_errors($connection)));
        }else{
            return true;
        }
    }

}
function updateDataByVar($var,$table,$where)
{
    global $connection;

    $result = '';
    $query = "UPDATE $table SET $var WHERE $where";
    //echo  $query;
    $cmd = sqlsrv_query($connection, $query);

    if ($cmd == false) {
        $result = 'Loi truy van.';
        die(print_r(sqlsrv_errors(), true));
    } else {
        $result = 1;
    }
    sqlsrv_close($connection);
    return $result;

}
function getTime(){
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $datetime = new DateTime();
    $time =  $datetime ->getTimestamp();
    $timeReq = date('Y/m/d H:i:s',$time);
    return $timeReq;
}
 /*-------------------End functuon by Mr Phu----------------------------*/

function insertSqlserver($table, $colum, $data){
    global $connection;
    if (!$table || !$data || !$colum) {
        return false;
    } else {
        $colums = implode(',', $colum);
        $datas  = implode(',', $data);
        $query  = 'INSERT INTO '.$table.'('. $colums .') VALUES('. $datas .')';
        $query  = sqlsrv_query($connection, $query);
        // Debug
        if($query == FALSE){
            die('error: '.FormatErrors(sqlsrv_errors($connection)));
        }else{
            return true;
        }
    }
}

function getGlobalAll($table, $data = '', $option = ''){
    global $connection;
    if($data){
        foreach ($data as $key => $value) {
            $colums[] = '['.$key .'] = '. "N'". $value ."'";
        }
        $colums_list = implode(' AND ', $colums);
    }

    $extra = '';

    if($option['order_by'] && $option['order_by_soft']){
        $extra .= ' ORDER BY '.$option['order_by'].' '. $option['order_by_soft'].' ';
    }

    if($option['limit'] && $option['limit_offset']){
        $extra .= ' LIMIT '.$option['limit'].' OFFSET '.$option['limit_offset'].' ';
    }else if($option['limit'] && !$option['limit_offset']){
        $extra .= ' LIMIT '. $option['limit'] .' ';
    }

    if($option['query']){
        $query = $option['query'];
    }else{
        $query = 'SELECT '. ($option['select'] ? $option['select'] : '*') .' FROM '. $table .' '. (($data) ? 'WHERE '.$colums_list : '') .' '.$extra;
    }

    if($option['onecolum'] && ($option['onecolum'] != 'limit')){
        $q = sqlsrv_query($connection, $query);
        if($q == FALSE){
            die(FormatErrors( sqlsrv_errors()));
        }
        $r = sqlsrv_fetch_array($q,SQLSRV_FETCH_ASSOC);
        return $r[$option['onecolum']];
    }else if($option['onecolum'] == 'limit'){
        $q = sqlsrv_query($connection, $query);
        if($q == FALSE){
            die(FormatErrors( sqlsrv_errors()));
        }
        $r = sqlsrv_fetch_array($q,SQLSRV_FETCH_ASSOC);
        return $r;
    }else{
        $q = sqlsrv_query($connection, $query);
        if($q == FALSE){
            die(FormatErrors( sqlsrv_errors()));
        }
        while($r = sqlsrv_fetch_array($q, SQLSRV_FETCH_ASSOC)){
            $n[] = $r;
        }
        return $n;
    }
}

function updateGlobal($table, $data='', $where = ''){
    global $connection;

        foreach($data as $key => $value){
            $colums[] =  '['.$key ."] = N'". checkInsert($value) ."'";
        }
        $colums_list = implode(',', $colums);

        if($where) {
            foreach ($where as $key_w => $value_w) {
                $colums_w[] =  $key_w . " = '" . checkInsert($value_w) . "'";
            }
            $colums_list_w = ' WHERE '.implode(' AND ', $colums_w);
        }
    if(sqlsrv_query($connection, 'UPDATE '. $table .' SET '.$colums_list.' '.$colums_list_w)){
        return true;
    }else{
        return false;
    }
}

function deleteGlobal($table, $data = ''){
    global $connection;
    foreach ($data as $key => $value) {
        $colums[] = $key . " = " . checkInsert($value);
    }
    $colums_list = implode(' AND ', $colums);
    $q = sqlsrv_query($connection,'DELETE FROM '. $table .' WHERE '.$colums_list);
    if($q){
        return true;
    }else{
        return false;
    }
}

function checkGlobal($table, $data = '', $option = ''){
    global $connection;
    foreach ($data as $key => $value) {
        $colums[] = '['. $key . '] = ' . "'". checkInsert($value) ."'";
    }
    $colums_list = implode(' AND ', $colums);
    if($option['query']){
        $q = sqlsrv_query($connection, $option['query'], array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
    }else{
        $q = sqlsrv_query($connection,'SELECT * FROM '. $table .' WHERE '.$colums_list,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
    }
    $n = sqlsrv_num_rows( $q);
    if($n > 0){
        return $n;
    }else{
        return 0;
    }
}

function getStaticDevice($option){
    $return = 0;
    if($option['type'] == 'click_day'){
        $query  = "select * from [tblTransactions] where datediff(DAY, [timeReq], '". $option['time'] ."') = 0";
        $return = checkGlobal(_DB_TABLE_TRANSACTIONS, '', array('query' => $query));
    }else if($option['type'] == 'click_day_plus'){
        $query  = "select * from [tblTransactions] where (datediff(DAY, [timeReq], '". $option['time'] ."') = 0) ".$option['data'];
        $return = checkGlobal(_DB_TABLE_TRANSACTIONS, '', array('query' => $query));
    }else if($option['type'] == 'between'){
        $query  = "SELECT * FROM [tblTransactions] WHERE [timeReq] between '". $option['time_start'] ."' AND '". $option['time_end'] ."'";
        $return = checkGlobal(_DB_TABLE_TRANSACTIONS, '', array('query' => $query));
    }else if($option['type'] == 'between_plus'){
        $query  = "SELECT * FROM [tblTransactions] WHERE ([timeReq] between '". $option['time_start'] ."' AND '". $option['time_end'] ."') ".$option['data'];
        $return = checkGlobal(_DB_TABLE_TRANSACTIONS, '', array('query' => $query));
    }
    return $return;
}

function FormatErrors( $errors )
{
    /* Display errors. */
    echo "Error information: <br/>";

    foreach ( $errors as $error )
    {
        echo "SQLSTATE: ".$error['SQLSTATE']."<br/>";
        echo "Code: ".$error['code']."<br/>";
        echo "Message: ".$error['message']."<br/>";
    }
}

function checkInsert($text){
    return stripslashes($text);
}

function getStatusDevice($text){
    switch ($text){
        case '0':
            $result = 'Đang hoạt động';
            break;
        case '1':
            $result = 'Chưa hoạt động';
            break;
    }
    return $result;
}

function getStatusCustomer($text){
    switch ($text){
        case '1':
            $result = 'Đang hoạt động';
            break;
        case '0':
            $result = 'Chưa hoạt động';
            break;
    }
    return $result;
}

function getStatusUser($text){
    switch ($text){
        case '0':
            $result = 'Đã kích hoạt';
            break;
        case '1':
            $result = 'Chưa kích hoạt';
            break;
    }
    return $result;
}

function getStatusUserRule($text){
    switch ($text){
        case '0':
            $result = 'ADMIN';
            break;
        case '1':
            $result = 'Quản trị';
            break;
    }
    return $result;
}

function getDeveloperInfo(){
    return array(
        '1' => 'Nguyễn Hiếu',
        '2' => 'Nguyễn Bình',
        '3' => 'Nguyễn Dương',
        '4' => 'AMAZON BUTTON'
    );
}
function getButtonIDDetail(){
    return array(
        '1' => 'Thư',
        '2' => 'Hàng'
    );
}

/*
 * $config_pagenavi['page_row']    = int; Số bản ghi mỗi trang
   $config_pagenavi['page_num']    = ceil(mysqli_num_rows(mysqli_query($db_connect, "SELECT `id` FROM `dong_post`"))/$config_pagenavi['page_row']);
   $config_pagenavi['url']         = _URL_ADMIN.'/post.php?act=news';
*/
function pagination($config){
    $link = '';
    global $page;
    for($i=$page;$i<=($page+4) && $i<= $config['page_num'] ;$i++){
        if($page==$i){$link= '<li class="page-item active"><a href="javascript:;" class="page-link">'.$i.'</a></li>';}
        else{$link = $link.'<li class="page-item"><a href="'. $config['url'] .'page='.$i.'" class="page-link">'.$i.'</a></li>';}
    }
    if($page>4){$page4='<li class="page-item"><a href="'.$config['url'].'page='.($page-4).'" class="page-link">'.($page-4).'</a></li>';}
    if($page>3){$page3='<li class="page-item"><a href="'.$config['url'].'page='.($page-3).'" class="page-link">'.($page-3).'</a></li>';}
    if($page>2){$page2='<li class="page-item"><a href="'.$config['url'].'page='.($page-2).'" class="page-link">'.($page-2).'</a></li>';}
    if($page>1){
        $page1='<li class="page-item"><a href="'.$config['url'].'page='.($page-1).'" class="page-link">'.($page-1).'</a></li>';
        $link1='<li class="page-item" class="page-link" aria-label="Previous"><a href="'.$config['url'].'page='.($page-1).'" class="page-link"><span aria-hidden="true">« Trang sau</span><span class="sr-only">Previous</span></a></li>';
    }
    if($page < $config['page_num']){$link2='<li class="page-item"><a href="'.$config['url'].'page='.($page+1).'" class="page-link" aria-label="Next"><span aria-hidden="true">Trang tiếp »</span><span class="sr-only">Next</span></a></li>';}
    $linked=$page4.$page3.$page2.$page1;
    if($page<$config['page_num']-4){$page_end_pt='<li class="page-item"><a href="'.$config['url'].'page='.$config['page_num'].'" class="page-link">'.$config['page_num'].'</a></li>';}
    if($page>5){$page_start_pt=' <li class="page-item"><a href="'.$config['url'].'" class="page-link">1</a></li>';}

    if($config['page_num']>1 && $page<=$config['page_num']){
        return '<ul class="pagination justify-content-center pagination-separate">'.$link1.$page_start_pt.$linked.$link.$page_end_pt.$link2.'</ul>';
    }else{
        return false;
    }
}

function getStatusTable($text){
    $return = 'Không xác định';
    if($text['table'] == _DB_TABLE_DEVICE_CONFIG){
        switch ($text['value']){
            case 0:
                $return = 'Dừng hoạt động';
                break;
            case 1:
                $return = 'Đang hoạt động';
                break;
        }
    }
    return $return;
}

function getViewTime($time){
    return $time ? $time->format('H:i:s d/m/Y') : '';
}

function getProcessTransactions($tranID){
    $trans = getGlobalAll(_DB_TABLE_TRANSACTIONS, array('transID' => $tranID), array('onecolum' => 'limit'));
    if($trans['status_02'] != 0 && $trans['status_03'] != 0 && $trans['status_04'] != 0){
        $status = 'step_4';
    }else if($trans['status_02'] != 0 && $trans['status_03'] != 0 && $trans['status_04'] == 0){
        $status = 'step_3';
    }else if($trans['status_02'] != 0 && $trans['status_03'] == 0 && $trans['status_04'] == 0){
        $status = 'step_2';
    }else if($trans['status_02'] == 0 && $trans['status_03'] == 0 && $trans['status_04'] == 0){
        $status = 'step_1';
    }else if($trans['status_04'] == 301){
        $status = 'customer_delete';
    }else if($trans['status_04'] == 106){
        $status = 'dieuhanh_cance'; // điều hành hủy đơn
    }
    return $status;
}

/*
 * SQL QUERY SELECT DATETIME select * from tblTransactions where CONVERT(datetime, timeReq, 103) between CONVERT(datetime, '10/06/2018 00:00:00', 104) and CONVERT(datetime, '14/6/2018 23:59:59', 104)
 * */