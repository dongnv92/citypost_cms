<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/19/2018
 * Time: 1:56 PM
 */
require_once 'includes/core.php';
if(!$user_id){
    header('location:'._URL_HOME.'/login.php');
}
$admin_active = 'googlemap';
switch ($act){
    case 'del':
        $address        = getGlobalAll('maps_address', array('id' => $id), array('onecolum' => 'limit'));
        if(!$address){
            header('location:'._URL_ADMIN.'/googlemap.php');
        }
        if($submit){
            if(deleteGlobal('maps_address', array('id' => $id))){
                header('location:'._URL_ADMIN.'/googlemap.php?act=add');
            }
        }
        $admin_title = 'Xóa khách hàng';
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header"><h4 class="card-title"><?php echo $admin_title;?></h4> </div>
                    <div class="card-content text-center">
                        <form action="" method="post">
                            Bạn có chắc muốn xóa địa chỉ này không?
                            <div class="form-actions text-center">
                                <a href="<?php echo _DB_URL_BACK; ?>" class="btn btn-outline-cyan round">Quay Lại</a> <input type="submit" name="submit" class="btn btn-outline-cyan round" value="Xóa địa chỉ" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
    case 'add':
        if($id){
            $address        = getGlobalAll('maps_address', array('id' => $id), array('onecolum' => 'limit'));
            $address_name   = $address['address_name'];
            $address_value  = $address['address_value'];
        }
        if($submit){
            $address_name   = (isset($_POST['address_name'])    && !empty($_POST['address_name']))  ? $_POST['address_name']    : '';
            $address_value  = (isset($_POST['address_value'])   && !empty($_POST['address_value'])) ? $_POST['address_value']   : '';
            if($address_name && $address_value){
                if($id){
                    $data   = array(
                        'address_name'  => $address_name,
                        'address_value' => $address_value
                    );
                    $where  = array('id' => $id);
                    if(updateGlobal('maps_address', $data, $where)){
                        header('location:'._URL_ADMIN.'/googlemap.php?act=add');
                    }else{
                        echo 'ERROR';
                        break;
                    }
                }else{
                    $colum  = array('[address_name]','[address_value]', '[address_time]');
                    $data   = array("N'$address_name'", "N'$address_value'", _CONFIG_TIME);
                    if(!insertSqlserver('maps_address', $colum, $data)){
                        echo 'ERROR';
                        break;
                    }else{
                        $address_name   = '';
                        $address_value  = '';
                    }
                }
            }
        }
        $admin_title = 'Thêm địa chỉ';
        require_once 'header.php';
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col-4 text-left">
                    <input type="text" class="form-control round" required placeholder="Địa chỉ cụ thể" name="address_value" value="<?php echo $address_value;?>" />
                </div>
                <div class="col-4 text-left">
                    <input type="text" class="form-control round" required placeholder="Tên hiển thị" name="address_name" value="<?php echo $address_name;?>" />
                </div>
                <div class="col-4 text-right">
                    <input type="submit" name="submit" value="<?php echo $id ? 'Sửa địa chỉ' : 'Thêm địa chỉ mới' ?>" class="btn round btn-outline-cyan" />
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Danh sách các khách hàng</h4>
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

                            $query_pagenum                  = 'SELECT [id] FROM maps_address '.$parameters_list;
                            $query_pagenum                  = sqlsrv_query($connection, $query_pagenum, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
                            $page_num                       = sqlsrv_num_rows($query_pagenum);
                            $config_pagenavi['page_row']    = _CONFIG_PAGINATION;
                            $config_pagenavi['page_num']    = ceil($page_num/$config_pagenavi['page_row']);
                            $config_pagenavi['url']         = _URL_ADMIN.'/googlemap.php?act=add&'.$para_list.'&';
                            $page_start                     = ($page-1) * $config_pagenavi['page_row'];
                            $page_start                     = $page_start == 0 ? 1 : $page_start+1;
                            $query                          = 'SELECT * FROM (SELECT ROW_NUMBER() OVER(ORDER BY id DESC) AS RowNumber,address_name,address_value,id FROM maps_address '. $parameters_list .') AS Temp WHERE RowNumber BETWEEN '. $page_start .' AND '.($page_start + ($config_pagenavi['page_row'] - 1));
                            //echo $query; exit();
                            $data                           = getGlobalAll(_DB_TABLE_CUSTOMER, '', array('query' => $query));
                            echo '<div class="text-right"><nav aria-label="Page navigation">'.pagination($config_pagenavi).'</nav></div>';
                            echo '<div class="table-responsive">';
                            echo '<table class="table">';
                            echo '<thread>';
                            echo '<tr>';
                            echo '<th width="10%">#</th>';
                            echo '<th width="40%">Địa chỉ cụ thể</th>';
                            echo '<th width="40%">Tên hiển thị</th>';
                            echo '<th width="10%">Quản lý</th>';
                            echo '</tr>';
                            echo '</thread>';
                            echo '<tbody>';
                            $i = 0;
                            foreach ($data AS $address){
                                $i++;
                                echo '<tr>';
                                echo '<td width="10%">'. $i .'</td>';
                                echo '<td width="40%"><a href="'. _URL_ADMIN .'/googlemap.php?act=add&id='. $address['id'] .'">'. $address['address_value'] .'</a></td>';
                                echo '<td width="40%">'. $address['address_name'] .'</td>';
                                echo '<td width="10%"><a href="'. _URL_ADMIN .'/googlemap.php?act=del&id='. $address['id'] .'" class="text-danger">Xóa</a> </td>';
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
        </form>
        <?php
        break;
    default:
        $css_plus       = array('http://trungtamytemelinh.com/admin/app-assets/css/chosen.css');
        $js_plus        = array('http://trungtamytemelinh.com/admin/app-assets/js/chosen.jquery.js', 'http://trungtamytemelinh.com/admin/app-assets/js/prism.js','http://trungtamytemelinh.com/admin/app-assets/js/init.js');
        $data_address   = getGlobalAll('maps_address', '');
        $admin_title    = 'Hướng Dẫn Chỉ Đường';
        require_once 'header.php';
        ?>
        <div class="row">
            <div class="col-md-6">
                <fieldset class="form-group">
                    <p>Điểm Bắt Đầu</p>
                    <select class="form-control round chosen-select" id="start">
                        <option value="Phòng khám Đa khoa Yên Hòa">Phòng khám Đa khoa Yên Hòa</option>
                    </select>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <p>Điểm Cuối</p>
                    <select class="form-control round chosen-select" id="end">
                        <option value="Phòng khám Đa khoa Yên Hòa">Phòng khám Đa khoa Yên Hòa</option>
                    </select>
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <fieldset class="form-group">
                    <p>Các điểm đến</p>
                    <select multiple id="waypoints" class="chosen-select form-control round">
                        <option value=""></option>
                        <?php
                        foreach ($data_address AS $address){
                            echo '<option value="'. $address['address_value'] .'" selected="selected">'. $address['address_name'] .'</option>';
                        }
                        ?>
                    </select>
                </fieldset>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col text-left" id="button_to_app"></div>
            <div class="col text-right">
                <input type="submit" id="submit" value="Bắt đầu chỉ dẫn" class="btn round btn-outline-cyan">
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col">
                <div id="map" style="height: 100%;float: left;width: 100%;height: 500px;"></div>
                <script>
                    function initMap() {
                        var directionsService = new google.maps.DirectionsService;
                        var directionsDisplay = new google.maps.DirectionsRenderer;
                        var map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 12,
                            center: {lat: 21.017733, lng: 105.780973}
                        });
                        directionsDisplay.setMap(map);
                        calculateAndDisplayRoute(directionsService, directionsDisplay);
/*
                        document.getElementById('submit').addEventListener('click', function() {
                            calculateAndDisplayRoute(directionsService, directionsDisplay);
                        });
*/
                    }

                    function calculateAndDisplayRoute(directionsService, directionsDisplay) {
                        var waypts = [];
                        var checkboxArray = document.getElementById('waypoints');
                        for (var i = 0; i < checkboxArray.length; i++) {
                            if (checkboxArray.options[i].selected) {
                                waypts.push({
                                    location: checkboxArray[i].value,
                                    stopover: true
                                });
                            }
                        }

                        directionsService.route({
                            origin: document.getElementById('start').value,
                            destination: document.getElementById('end').value,
                            waypoints: waypts,
                            optimizeWaypoints: true,
                            travelMode: 'DRIVING' //DRIVING
                        }, function(response, status) {
                            if (status === 'OK') {
                                directionsDisplay.setDirections(response);
                                var route           = response.routes[0];
                                var summaryPanel    = document.getElementById('directions-panel');
                                summaryPanel.innerHTML = '';
                                var button_to_app    = document.getElementById('button_to_app');
                                button_to_app.innerHTML = '';
                                var maps_addr_list_1    = '';
                                var maps_addr_list_2    = '';
                                var maps_addr_list_3    = '';
                                var maps_addr_start     = document.getElementById('start').value;
                                var maps_addr_end       = document.getElementById('end').value;
                                var length_id           = route.legs.length;
                                // For each route, display summary information.
                                for (var i = 0; i < length_id; i++) {
                                    var routeSegment = i + 1;
                                    summaryPanel.innerHTML += '<b class="text-danger">Đoạn đường: ' + routeSegment + '</b><br>';
                                    summaryPanel.innerHTML += '<i class="la la-map-marker"></i> <b>Từ</b> ' + route.legs[i].start_address + ' <b>đến</b> ';
                                    summaryPanel.innerHTML += route.legs[i].end_address + '<br><b class="text-danger">Hướng dẫn chi tiết</b><br>';
                                    route.legs[i].steps.forEach(function(entry) {
                                        summaryPanel.innerHTML += '<i class="la la-map-pin"></i>' + entry.instructions + ' (Khoảng '+ entry.distance.text +')<br>';
                                    });
                                    summaryPanel.innerHTML += '<i class="la la-car"></i> <b>Độ dài</b>: ' + route.legs[i].distance.text + '<br>';
                                    summaryPanel.innerHTML += '<i class="la la-hourglass-half"></i> <b>Thời gian dự kiến</b>: ' + route.legs[i].duration .text + '<br>';
                                    summaryPanel.innerHTML += '<hr>';

                                    if(i <= 10){
                                        if(i > 0 && i < 10){
                                            maps_addr_list_1 += route.legs[i].start_address + '%7C';
                                        }
                                    }else if(i >= 11 && i <= 20){
                                        if(i != 20){
                                            maps_addr_list_2 += route.legs[i].start_address + '%7C';
                                        }
                                    }else if(i >= 21){
                                        maps_addr_list_3 += route.legs[i].start_address + '%7C';
                                    }

                                }

                                if(i <= 10){
                                    button_to_app.innerHTML += '<a id="round_1" class="btn btn-outline-cyan round" target="_blank" href="https://www.google.com/maps/dir/?api=1&origin='+ maps_addr_start +'&destination='+ maps_addr_end +'&travelmode=driving&waypoints='+ maps_addr_list_1 +'">Xem trên Google Maps</a>';
                                }else if(i >= 11 && i <= 20){
                                    button_to_app.innerHTML += '<a id="round_1" class="btn btn-outline-cyan round" target="_blank" href="https://www.google.com/maps/dir/?api=1&origin='+ maps_addr_start +'&destination='+ route.legs[10].start_address +'&travelmode=driving&waypoints='+ maps_addr_list_1 +'">Quãng 1</a>';
                                    button_to_app.innerHTML += '<a id="round_2" class="btn btn-outline-cyan round" target="_blank" href="https://www.google.com/maps/dir/?api=1&origin='+ route.legs[10].start_address +'&destination='+ maps_addr_end +'&travelmode=driving&waypoints='+ maps_addr_list_2 +'">Quãng 2</a>';
                                }else if(i >= 21){
                                    button_to_app.innerHTML += '<a id="round_1" class="btn btn-outline-cyan round" target="_blank" href="https://www.google.com/maps/dir/?api=1&origin='+ maps_addr_start +'&destination='+ route.legs[10].start_address +'&travelmode=driving&waypoints='+ maps_addr_list_1 +'">Quãng 1</a>';
                                    button_to_app.innerHTML += '<a id="round_2" class="btn btn-outline-cyan round" target="_blank" href="https://www.google.com/maps/dir/?api=1&origin='+ route.legs[10].start_address +'&destination='+ route.legs[20].start_address +'&travelmode=driving&waypoints='+ maps_addr_list_2 +'">Quãng 2</a>';
                                    button_to_app.innerHTML += '<a id="round_3" class="btn btn-outline-cyan round" target="_blank" href="https://www.google.com/maps/dir/?api=1&origin='+ route.legs[20].start_address +'&destination='+ maps_addr_end +'&travelmode=driving&waypoints='+ maps_addr_list_3 +'">Quãng 3</a>';
                                }

                            } else {
                                var errorMsg;
                                if(status == 'MAX_WAYPOINTS_EXCEEDED'){
                                    errorMsg = 'Tối đa được 23 địa chỉ';
                                }else{
                                    errorMsg = status;
                                }
                                window.alert('Yêu cầu chỉ đường không thành công vì ' + errorMsg);
                            }
                        });
                    }
                </script>
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbCKsqeLxW--Y_Ka8yOIenEg2QvCHKVzY&callback=initMap"></script>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Chỉ dẫn cụ thể</h4></div>
                    <div class="card-body">
                        <div id="directions-panel"></div>
                    </div>
                </div>
            </div>
        </div>
            <?php
        break;
}

require_once 'footer.php';
?>
