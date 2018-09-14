<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 26/06/2018
 * Time: 10:04
 */
require_once 'includes/core.php';
echo citypostSms($_REQUEST['act']);
function citypostSms($sms){
    $text   = '';
    $sms    = explode(' ', $sms);
    $citypost_act   = $sms[1];
    $citypost_but   = $sms[2];
    $citypost_dev   = $sms[3];
    switch ($citypost_act){
        case 'send':
            $text .= 'buttonid='.$citypost_but.' status=101.';
            break;
        case 'check':
            $button_1 = getGlobalAll(_DB_TABLE_TRANSACTIONS, array('deviceID' => "$citypost_but", 'btnID' => 1, 'status' => 0), array('onecolum' => 'limit', 'select' => ' TOP 1 * ', 'order_by' => 'timeReq', 'order_by_soft' => 'DESC'));
            if($button_1){
                $status_1 = getProcessTransactions($button_1['transID']);
                $status_1 = str_replace(array('step_4','step_3','step_2','step_1', 'customer_delete', ''), array(104,103,102,101,301,104), $status_1);
            }else{
                $status_1 = 104;
            }
            $button_2 = getGlobalAll(_DB_TABLE_TRANSACTIONS, array('deviceID' => "$citypost_but", 'btnID' => 2, 'status' => 0), array('onecolum' => 'limit', 'select' => ' TOP 1 * ', 'order_by' => 'timeReq', 'order_by_soft' => 'DESC'));
            if($button_2){
                $status_2 = getProcessTransactions($button_2['transID']);
                $status_2 = str_replace(array('step_4','step_3','step_2','step_1', 'customer_delete'), array(104,103,102,101,301), $status_2);
            }else{
                $status_2 = 104;
            }

            $text .= 'buttonid=1 status='.$status_1.'|buttonid=2 status='.$status_2.'.';
            break;
    }
    return $text;
}
