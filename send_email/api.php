<?php
error_reporting(0);
date_default_timezone_set('Asia/Ho_Chi_Minh');

$token = isset($_GET['token']) ? $_GET['token'] : false;
if(!$token){
    echo json_encode(array('response' => 400, 'message' => 'Missing Token'));
    exit();
}

if(!checkToken($token)){
    echo json_encode(array('response' => 203, 'message' => 'Wrong Token. Please try again'));
    exit();
}

header('Content-Type: text/html; charset=utf-8');
define('_TABLE_LOGIN', 'Email_Login');
define('_TABLE_LIST', 'Email_List');
define('_TABLE_ACTIVYTY', 'Email_Activity');

define('_DB_SERVER', 'WIN-T2JRC8V71J9');
define('_DB_USER', 'send_email_user');
define('_DB_PASS', 'CTabc@!2019');
define('_DB_NAME', 'Send_Email');
define('_CONFIG_TIME', time());

function checkToken($token){
    $arr_token = array();
    for ($i = 0; $i <= 500; $i++){
        $time_c         = time() + $i;
        $time_t         = time() - $i;
        $key_start      = 'DONG';
        $key_end        = 'CHINH';
        $arr_token[]    = md5(md5($key_start.$time_c.$key_end));
        $arr_token[]    = md5(md5($key_start.$time_t.$key_end));
    }
    if(in_array($token, $arr_token)){
        return true;
    }else{
        return false;
    }
}

$connection = sqlsrv_connect( _DB_SERVER, array( "Database"=> _DB_NAME, "UID"=>_DB_USER, "PWD"=>_DB_PASS, "CharacterSet" => "UTF-8"));
if( !$connection ) {
    $response = array('response' => 400, 'message' => 'Connect Fail First');
    echo json_encode($response);
    exit();
}

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

    if($option['query']){
        $query = $option['query'];
    }else{
        $query = 'SELECT '. ($option['limit'] ? ' TOP '.$option['limit'].' ' : '') .' '. ($option['select'] ? $option['select'] : '*') .' FROM '. $table .' '. (($data) ? 'WHERE '.$colums_list : '') .' '.$extra;
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

    if($data){
        foreach ($data as $key => $value) {
            $colums[] = '['. $key . '] = ' . "'". checkInsert($value) ."'";
        }
        $colums_list = ' WHERE '.implode(' AND ', $colums);
    }

    if($option['query']){
        $q = sqlsrv_query($connection, $option['query'], array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
    }else{
        $q = sqlsrv_query($connection,'SELECT * FROM '. $table .' '.$colums_list,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
    }
    $n = sqlsrv_num_rows( $q);
    if($n > 0){
        return $n;
    }else{
        return 0;
    }
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

$user           = isset($_REQUEST['user'])          && !empty($_REQUEST['user'])            ? trim($_REQUEST['user'])           : '';
$pass           = isset($_REQUEST['pass'])          && !empty($_REQUEST['pass'])            ? trim($_REQUEST['pass'])           : '';
$act            = isset($_REQUEST['act'])           && !empty($_REQUEST['act'])             ? trim($_REQUEST['act'])            : '';
$type           = isset($_REQUEST['type'])          && !empty($_REQUEST['type'])            ? trim($_REQUEST['type'])           : '';
$id             = isset($_REQUEST['id'])            && !empty($_REQUEST['id'])              ? trim($_REQUEST['id'])             : '';
$status         = isset($_REQUEST['status'])        && !empty($_REQUEST['status'])          ? trim($_REQUEST['status'])         : '';
$number         = isset($_REQUEST['number'])        && !empty($_REQUEST['number'])          ? trim($_REQUEST['number'])         : '';
$email          = isset($_REQUEST['email'])         && !empty($_REQUEST['email'])           ? trim($_REQUEST['email'])          : '';
$email_name     = isset($_REQUEST['email_name'])    && !empty($_REQUEST['email_name'])      ? trim($_REQUEST['email_name'])     : '';
$email_location = isset($_REQUEST['email_location'])&& !empty($_REQUEST['email_location'])  ? trim($_REQUEST['email_location']) : '';
$email_ver      = isset($_REQUEST['email_ver'])     && !empty($_REQUEST['email_ver'])       ? trim($_REQUEST['email_ver'])      : '';

switch ($act){
    // Kiểm tra xem đã gửi nội dung cho email nhận chưa.
    case 'check_send_templates':
        $email_to           = isset($_REQUEST['email_to'])          && !empty($_REQUEST['email_to'])        ? trim($_REQUEST['email_to'])           : '';
        $email_templates    = isset($_REQUEST['email_templates'])   && !empty($_REQUEST['email_templates']) ? trim($_REQUEST['email_templates'])    : '';
        $check_temp = checkGlobal(_TABLE_ACTIVYTY, array('activity_to' => $email_to, 'activity_templates' => $email_templates));
        $response['response']   = 200;
        $response['number']     = $check_temp;
        break;
    case 'statics':
        $today                          = date('Y/m/d', _CONFIG_TIME);
        $week_start                     = date('Y/m/d', strtotime('monday this week', _CONFIG_TIME));
        $week_end                       = date('Y/m/d 23:59:59', strtotime('sunday this week', _CONFIG_TIME));
        $month_start                    = date('Y/m/d', strtotime('first day of this month', _CONFIG_TIME));
        $month_end                      = date('Y/m/d 23:59:59', strtotime('last day of this month', _CONFIG_TIME));
        $year_start                     = date('Y/m/d', strtotime('first day of January', _CONFIG_TIME));
        $year_end                       = date('Y/m/d 23:59:59', strtotime('last day of December', _CONFIG_TIME));
        $response['response']           = 200;
        $response['email_all']          = checkGlobal(_TABLE_LIST, '');
        $response['email_hn']           = checkGlobal(_TABLE_LIST, array('list_address' => 'hn'));
        $response['email_dn']           = checkGlobal(_TABLE_LIST, array('list_address' => 'dn'));
        $response['email_hcm']          = checkGlobal(_TABLE_LIST, array('list_address' => 'hcm'));
        $response['email_bd']           = checkGlobal(_TABLE_LIST, array('list_address' => 'bd'));
        $response['email_empty']        = checkGlobal(_TABLE_LIST, array('list_address' => ''));
        $response['send_today_success'] = checkGlobal(_TABLE_ACTIVYTY, '', array('query' => "select * from [". _TABLE_ACTIVYTY ."] where (datediff(DAY, [activity_time], '$today') = 0) AND [activity_status] = 1"));
        $response['send_week_success']  = checkGlobal(_TABLE_ACTIVYTY, '', array('query' => "SELECT * FROM [". _TABLE_ACTIVYTY ."] WHERE ([activity_time] between '$week_start' AND '$week_end') AND [activity_status] = 1"));
        $response['send_month_success'] = checkGlobal(_TABLE_ACTIVYTY, '', array('query' => "SELECT * FROM [". _TABLE_ACTIVYTY ."] WHERE ([activity_time] between '$month_start' AND '$month_end') AND [activity_status] = 1"));
        $response['send_year_success']  = checkGlobal(_TABLE_ACTIVYTY, '', array('query' => "SELECT * FROM [". _TABLE_ACTIVYTY ."] WHERE ([activity_time] between '$year_start' AND '$year_end') AND [activity_status] = 1"));
        $response['send_today_false']   = checkGlobal(_TABLE_ACTIVYTY, '', array('query' => "select * from [". _TABLE_ACTIVYTY ."] where (datediff(DAY, [activity_time], '$today') = 0) AND [activity_status] = 0"));
        $response['send_week_false']    = checkGlobal(_TABLE_ACTIVYTY, '', array('query' => "SELECT * FROM [". _TABLE_ACTIVYTY ."] WHERE ([activity_time] between '$week_start' AND '$week_end') AND [activity_status] = 0"));
        $response['send_month_false']   = checkGlobal(_TABLE_ACTIVYTY, '', array('query' => "SELECT * FROM [". _TABLE_ACTIVYTY ."] WHERE ([activity_time] between '$month_start' AND '$month_end') AND [activity_status] = 0"));
        $response['send_year_false']    = checkGlobal(_TABLE_ACTIVYTY, '', array('query' => "SELECT * FROM [". _TABLE_ACTIVYTY ."] WHERE ([activity_time] between '$year_start' AND '$year_end') AND [activity_status] = 0"));
        $response['send_all_success']   = checkGlobal(_TABLE_ACTIVYTY, '', array('query' => "SELECT * FROM [". _TABLE_ACTIVYTY ."] WHERE [activity_status] = 1"));
        $response['send_all_false']     = checkGlobal(_TABLE_ACTIVYTY, '', array('query' => "SELECT * FROM [". _TABLE_ACTIVYTY ."] WHERE [activity_status] = 0"));

        break;
    case 'update_count': // Update Lượt Gửi Email Đến Email Này
        if(!$email){
            $response = array('response' => 401, 'error' => 'Empty Email');
            break;
        }

        $mail = getGlobalAll(_TABLE_LIST, array('list_email' => $email),array('onecolum' => 'limit'));
        if(!$mail){
            $response = array('response' => 404, 'error' => 'Email Empty');
            break;
        }

        if(updateGlobal(_TABLE_LIST, array('list_count' => ($mail['list_count'] + 1)), array('list_email' => $email))){
            $response['response']   = 200;
            $response['message']    = 'Update Success';
        }else{
            $response['response']   = 404;
            $response['message']    = 'Update Error';
        }
        break;
    case 'update_unfollow': // Update Ngừng Nhận Email
        if(!$email){
            $response = array('response' => 401, 'error' => 'Empty Email');
            break;
        }
        $mail = getGlobalAll(_TABLE_LIST, array('list_email' => $email),array('onecolum' => 'limit'));
        if(!$mail){
            $response = array('response' => 404, 'error' => 'Email Empty');
            break;
        }
        if(updateGlobal(_TABLE_LIST, array('list_unsubcrice' => 1), array('list_email' => $email))){
            $response['response']   = 200;
            $response['message']    = 'Update Success';
        }else{
            $response['response']   = 404;
            $response['message']    = 'Update Error';
        }
        break;
    case 'authencation':
        if(!$user || !$pass){
            $response = array('response' => 401, 'error' => 'Empty User Or Pass');
            break;
        }
        $user = getGlobalAll(_TABLE_LOGIN, array('login_user' => $user, 'login_pass' => md5($pass)),array('onecolum' => 'limit'));
        if(!$user){
            $response = array('response' => 404, 'error' => 'User Or Password Invalid');
            break;
        }
        $response               = $user;
        $response['response']   = 200;
        break;
    case 'add_activity':
        // Kiểm tra xem đã đủ thông tin chưa
        $email_from         = isset($_REQUEST['email_from'])        && !empty($_REQUEST['email_from'])      ? trim($_REQUEST['email_from'])         : '';
        $email_to           = isset($_REQUEST['email_to'])          && !empty($_REQUEST['email_to'])        ? trim($_REQUEST['email_to'])           : '';
        $email_status       = isset($_REQUEST['email_status'])      && !empty($_REQUEST['email_status'])    ? trim($_REQUEST['email_status'])       : '';
        $email_templates    = isset($_REQUEST['email_templates'])   && !empty($_REQUEST['email_templates']) ? trim($_REQUEST['email_templates'])    : '';
        if(!$email_from || !$email_to){
            $response = array('response' => 401, 'message' => 'Empty Email From or Email To, Or Status');
            break;
        }
        $email_status   = $email_status ? $email_status : 0;
        $colum  = array('[activity_from]','[activity_to]','[activity_status]','[activity_time]', '[activity_templates]');
        $data   = array("'$email_from'", "'$email_to'", "'$email_status'", "'". date('Y-m-d H:i:s', time()) ."'", "'$email_templates'");
        if(!insertSqlserver(_TABLE_ACTIVYTY, $colum, $data)){
            $response = array('response' => 402, 'message' => 'Can Not Insert To Database');
            break;
        }
        $response = array('response' => 200, 'message' => 'Add Email Success');

        break;
    case 'add_email':
        // Kiểm tra xem đã đủ thông tin chưa
        if(!$email || !$email_name){
            $response = array('response' => 401, 'message' => 'Empty Email Address Or Email Name Company Or Email Location');
            break;
        }
        // Kiểm tra xem đã đúng định dạng email chưa
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $response = array('response' => 402, 'message' => 'Email invalidate');
            break;
        }
        // Kiểm tra xem đã có dữ liệu chưa
        $check = getGlobalAll(_TABLE_LIST, array('list_email' => $email),array('onecolum' => 'limit'));
        if($check){
            $response = array('response' => 403, 'message' => 'Email Exist');
            break;
        }

        $colum  = array('[list_email]','[list_name]','[list_address]','[list_count]','[list_time]','[list_ver]','[list_unsubcrice]');
        $data   = array("'$email'", "N'$email_name'", "N'$email_location'", "'0'", "'". time() ."'", "'$email_ver'", "'0'");
        if(!insertSqlserver(_TABLE_LIST, $colum, $data)){
            $response = array('response' => 402, 'message' => 'Can Not Insert To Database');
            break;
        }
        $response = array('response' => 200, 'message' => 'Add Email Success: '.$email);
        break;
    case 'get_detail_by_email':
        // Kiểm tra xem đã đủ thông tin chưa
        if(!$email){
            $response = array('response' => 401, 'error' => 'Empty Email');
            break;
        }
        $email = getGlobalAll(_TABLE_LIST, array('list_email' => $email),array('onecolum' => 'limit'));
        if(!$email){
            $response = array('response' => 404, 'error' => 'Email Empty');
            break;
        }
        $response               = $email;
        $response['response']   = 200;
        break;
    case 'get_list_email':
        $para               = array();
        $email_nunber_send  = isset($_REQUEST['email_nunber_send'])     && !empty($_REQUEST['email_nunber_send'])       ? trim($_REQUEST['email_nunber_send'])      : '';
        if($email_location){
            $para['list_address'] = $email_location;
        }
        if($email_ver){
            $para['list_ver'] = $email_ver;
        }
        if($email_nunber_send){
            $para['list_count'] = $email_nunber_send;
        }else{
            $para['list_count'] = 0;
        }
        $para['list_unsubcrice'] = 0;
        $email_top      = isset($_REQUEST['email_top'])     && !empty($_REQUEST['email_top'])       ? trim($_REQUEST['email_top'])      : 100;

        if($type == 'rand'){
            $data = getGlobalAll(_TABLE_LIST, '', array('query' => 'SELECT TOP '. $email_top .' * FROM '. _TABLE_LIST .' ORDER BY NEWID()'));
        }else{
            $data = getGlobalAll(_TABLE_LIST, $para, array('limit' => $email_top));
        }

        if(!$data){
            $response = array('response' => 404, 'error' => 'No Data');
            break;
        }
        $response               = $data;
        $response['response']   = 200;
        break;
    case 'get_distince_ver_email_list':
        $data       = getGlobalAll('','',array('query' => 'SELECT DISTINCT list_ver FROM '._TABLE_LIST));
        $response   = $data;
        break;
    default:
        $response = array('response' => 404, 'message' => 'Default page');
        break;
}

echo json_encode($response);