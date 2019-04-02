<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 2018-11-12
 * Time: 10:58
 */
require_once 'includes/core.php';

switch ($act){
    case 'get_list_email':
        $email_location         = isset($_REQUEST['email_location'])        && !empty($_REQUEST['email_location'])      ? trim($_REQUEST['email_location'])     : '';
        $email_ver              = isset($_REQUEST['email_ver'])             && !empty($_REQUEST['email_ver'])           ? trim($_REQUEST['email_ver'])          : '';
        $email_top              = isset($_REQUEST['email_top'])             && !empty($_REQUEST['email_top'])           ? trim($_REQUEST['email_top'])          : 100;
        $email_nunber_send      = isset($_REQUEST['email_nunber_send'])     && !empty($_REQUEST['email_nunber_send'])   ? trim($_REQUEST['email_nunber_send'])  : '';
        $data['email_top']          = $email_top;
        $data['email_location']     = $email_location       ? $email_location       : '';
        $data['email_ver']          = $email_ver            ? $email_ver            : '';
        $data['email_nunber_send']  = $email_nunber_send    ? $email_nunber_send    : '';
        $data['type']               = $type                 ? $type                 : '';
        $api = getApi('get_list_email', $data);
        echo '<div class="table-responsive">';
            echo '<table class="table">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th width="50%" class="text-center">Email</th>';
                        echo '<th width="50%" class="text-center">Trạng Thái</th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    foreach ($api as $list){
                        if(filter_var($list['list_email'], FILTER_VALIDATE_EMAIL)){
                            echo '<tr>';
                            echo '<td>'. $list['list_email'] .'</td>';
                            echo '<td>'. file_get_contents(_HOME.'/send.php?email='.$list['list_email']) .'</td>';
                            echo '</tr>';
                        }
                    }
        echo '</tbody>';
            echo '</table>';
        echo '</div>';
        break;
}