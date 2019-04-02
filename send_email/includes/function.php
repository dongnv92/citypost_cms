<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 04/06/2018
 * Time: 15:34
 */

function getToken(){
    $time       = time();
    $key_start  = 'DONG';
    $key_end    = 'CHINH';
    return md5(md5($key_start.$time.$key_end));
}

function getApi($action, $param = ''){
    if(!$action){
        return false;
    }
    $para_list = '';
    $param['token'] = getToken();
    if(count($param) > 0){
        foreach($param as $key => $value){
            $para[] =  $key ."=".urlencode($value);
        }
        $para_list  .= '&'.implode('&', $para);
    }
    $data       = json_decode(file_get_contents(_URL_API.'/?act='.$action.$para_list), true);
    return $data;
}

function convert_seconds($seconds){
    $dt1 = new DateTime("@0");
    $dt2 = new DateTime("@$seconds");
    if($seconds < 60){
        return $dt1->diff($dt2)->format('%s Giây');
    }else if($seconds >= 60 && $seconds < 86400){
        return $dt1->diff($dt2)->format('%h Giờ, %i Phút, %s Giây');
    }else if($seconds > 86400){
        return $dt1->diff($dt2)->format('%a Ngày, %h Giờ, %i Phút, %s Giây');
    }
}