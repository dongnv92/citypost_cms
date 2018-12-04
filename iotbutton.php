<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 04/06/2018
 * Time: 10:29
 */

require_once 'includes/core.php';

switch ($act){
    case 'add':
        $seri       = isset($_GET['seri'])  && !empty($_GET['seri'])    ? $_GET['seri'] : null;
        $type       = isset($_GET['type'])  && !empty($_GET['type'])    ? $_GET['type'] : null;
        $pin        = isset($_GET['pin'])   && !empty($_GET['pin'])     ? $_GET['pin']  : 0;
        if($seri && $type){
            $colum  = array('[iotbutton_seri]','[iotbutton_type]','[iotbutton_pin]','[iotbutton_time]');
            $data   = array("'$seri'", "N'$type'", "'$pin'", _CONFIG_TIME);
            if(insertSqlserver('iotbutton_aws', $colum, $data)){
                $check_data = checkGlobal(_DB_TABLE_TRANSACTIONS, array('deviceID' => $seri, 'btnID' => $type, 'status' => 0));
                //echo $check_data; exit();
                // Thực thi insert vào API
				if($type == 1 || $type == 2){
					// Check, Đang có giao dịch thì không insert nữa
				    /*if($check_data == 0){
						$url_fetch = "http://112.78.11.14:8080/index.php?method=sendMSG&deviceId=$seri&prodID=1&typeID=$type&deviceStatus=101";
						$url_email = file_get_contents("http://112.78.11.14/send_email.php?seri=$seri&type=$type");
						echo json_encode(array('type' => 'send', 'button' => $type, 'status' => 'ok', 'fetch' => file_get_contents($url_fetch)));
					}else{
						echo json_encode(array('Error' => 'Error: Existing transactions, Do not Isert To Database'));		
					}*/
                    $url_fetch = "http://112.78.11.14:8080/index.php?method=sendMSG&deviceId=$seri&prodID=1&typeID=$type&deviceStatus=101";
                    $url_email = file_get_contents("http://112.78.11.14/send_email.php?seri=$seri&type=$type");
                    echo json_encode(array('type' => 'send', 'button' => $type, 'status' => 'ok', 'fetch' => file_get_contents($url_fetch)));
				}else if($type == 3){
                    if((checkGlobal(_DB_TABLE_TRANSACTIONS, array('deviceID' => $seri, 'btnID' => '1', 'status' => 0)) + checkGlobal(_DB_TABLE_TRANSACTIONS, array('deviceID' => $seri, 'btnID' => '2', 'status' => 0))) > 0){
                        $url_fetch      = file_get_contents("http://112.78.11.14:8080/index.php?method=sendMSG&deviceId=$seri&prodID=1&typeID=1&deviceStatus=301");
                        $url_fetch_1    = file_get_contents("http://112.78.11.14:8080/index.php?method=sendMSG&deviceId=$seri&prodID=1&typeID=2&deviceStatus=301");
                        $url_email      = file_get_contents("http://112.78.11.14/send_email.php?seri=$seri&type=$type");
                        echo json_encode(array('type' => 'cance', 'button' => $type, 'status' => 'ok', 'fetch' => 'OK'));
                    }else{
                        echo json_encode(array('Error' => 'Error: No transactions, Do not Isert To Database '.$check_data));
                    }
				}
            }else{
                echo json_encode(array('Error' => 'Error: Can not insert to iotbutton_aws'));
            }
        }else{
            echo json_encode(array('Error' => 'Error: Missing parameters type or seri'));
        }
        exit();
        break;
    default:
        $admin_title = 'Danh Sách Nút Bấm';
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Danh sách nút bấm thiết bị IOTBUTTON AWS</h4> </div>
                    <div class="card-body">
                        <!-- Pagination -->
                        <?php
                        $para = array('');
                        foreach ($para AS $paras){
                            if(isset($_REQUEST[$paras]) && !empty($_REQUEST[$paras])){
                                $parameters[$paras] = $_REQUEST[$paras];
                            }
                        }
                        if($parameters){
                            foreach ($parameters as $key => $value) {
                                $colums[] = '['.$key .'] = '. "N'". $value ."'";
                            }
                            $parameters_list = ' WHERE '.implode(' AND ', $colums);
                        }
                        // Tạo Url Parameter động
                        foreach ($parameters as $key => $value) {
                            $para_url[] = $key .'='. $value;
                        }
                        $para_list                      = implode('&', $para_url);

                        $query_pagenum                  = 'SELECT [id] FROM iotbutton_aws '.$parameters_list;
                        $query_pagenum                  = sqlsrv_query($connection, $query_pagenum, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
                        $page_num                       = sqlsrv_num_rows($query_pagenum);
                        $config_pagenavi['page_row']    = _CONFIG_PAGINATION;
                        $config_pagenavi['page_num']    = ceil($page_num/$config_pagenavi['page_row']);
                        $config_pagenavi['url']         = _URL_ADMIN.'/iotbutton.php?'.$para_list.'&';
                        $page_start                     = ($page-1) * $config_pagenavi['page_row'];
                        $page_start                     = $page_start == 0 ? 1 : $page_start+1;
                        $query                          = 'SELECT * FROM (SELECT ROW_NUMBER() OVER(ORDER BY iotbutton_time DESC) AS RowNumber,id,iotbutton_seri,iotbutton_type,iotbutton_pin,iotbutton_time FROM iotbutton_aws '. $parameters_list .') AS Temp WHERE RowNumber BETWEEN '. $page_start .' AND '.($page_start + ($config_pagenavi['page_row'] - 1));
                        //echo $query; exit();
                        $data                           = getGlobalAll('iotbutton_aws', '', array('query' => $query));
                        echo '<div class="text-right"><nav aria-label="Page navigation">'.pagination($config_pagenavi).'</nav></div>';
                        echo '<div class="table-responsive">';
                        echo '<table class="table">';
                        echo '<thread>';
                        echo '<tr>';
                        echo '<th width="40%">Seri Thiết bị</th>';
                        echo '<th width="20%">Kiểu Bấm</th>';
                        echo '<th width="20%">Tình trạng PIN</th>';
                        echo '<th width="20%">Thời gian bấm</th>';
                        echo '</tr>';
                        echo '</thread>';
                        echo '<tbody>';
                        foreach ($data AS $iot){
                            echo '<tr>';
                                echo '<td width="40%">'. $iot['iotbutton_seri'] .'</td>';
                                echo '<td width="20%">'. $iot['iotbutton_type'] .'</td>';
                                echo '<td width="20%">'. $iot['iotbutton_pin'] .'</td>';
                                echo '<td width="20%">'. date('H:i:s d/m/Y', $iot['iotbutton_time']) .'</td>';
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
        </div>
        <?php
        break;
}
require_once 'footer.php';
