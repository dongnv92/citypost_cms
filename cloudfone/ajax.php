<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 2019-04-02
 * Time: 16:14
 */
error_reporting(0);
$date_start     = isset($_REQUEST['date_start'])    && !empty($_REQUEST['date_start'])  ? trim($_REQUEST['date_start']) : '';
$date_end       = isset($_REQUEST['date_end'])      && !empty($_REQUEST['date_end'])    ? trim($_REQUEST['date_end'])   : '';
$call_num       = isset($_REQUEST['call_num'])      && !empty($_REQUEST['call_num'])    ? trim($_REQUEST['call_num'])   : '';
$receive_num    = isset($_REQUEST['receive_num'])   && !empty($_REQUEST['receive_num']) ? trim($_REQUEST['receive_num']): '';

$data = array(
    'ServiceName'   => 'CF-P-25243',
    'AuthUser'      => 'ODS5481',
    'AuthKey'       => 'da6068e-ef0a-4647-9163-d99b7d2f69f5',
    'TypeGet'       => 0, // 0: tất cả, 1: gọi đến, 2: gọi đi, 3: gọi nội bộ, 4: gợi nhở, 5: gọi nhóm
    'PageIndex'     => 1, // Số trang
    'PageSize'      => 200 // Số dòng trên 1 trang, max 200
);

if($date_start){
    $data['DateStart'] = $date_start;
}
if($date_end){
    $data['DateEnd'] = $date_end.' 23:59:59';
}
if($call_num){
    $data['CallNum'] = $call_num;
}
if($receive_num){
    $data['ReceiveNum'] = $receive_num;
}

$data = json_encode($data);
$ch = curl_init('https://api.cloudfone.vn/api/CloudFone/GetCallHistory');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data))
);
$result = curl_exec($ch);
curl_close($ch);