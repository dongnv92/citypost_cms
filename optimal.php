<?php
error_reporting(0);
date_default_timezone_set('Asia/Ho_Chi_Minh');
$starttime = microtime(true);

// Config API
$app_id         = 'Qh8TrDFLaB18FUtvss1Q'; // Here Maps
$app_code       = '05y5O5MgLVe0xHFNgwKYpw'; // Here Maps
$key            = 'AIzaSyBbCKsqeLxW--Y_Ka8yOIenEg2QvCHKVzY'; // google maps
$source         = $_GET['source'];

// Thiếu nguồn dữ liệu
if(!$source){
    echo json_encode(array('error' => 'Missing data source'));
    exit();
}
// sai định dạng url
 if($source && !filter_var($source,FILTER_VALIDATE_URL)){
     echo json_encode(array('error' => 'Wrong url format'));
     exit();
 }

// Khởi tạo biến ban đầu
$truck = $address   = $truck_unique   = $sortArray = array();
$data_source        = file_get_contents($source);
$data_source        = json_decode($data_source, true);
$truck              = $data_source['truck_list'];
$address            = $data_source['wayspoint'];
$address_start      = $data_source['address_start'];
$address_end        = $data_source['address_end'];
$break_time         = $data_source['break_time'];
$time_start         = $data_source['time_start'];
$optimal_type       = $data_source['optimal_type'];

// Sắp sếp theo trọng tải xe từ lớn đến bé
sortTruckByPayload();

// Tối ưu hóa quãng đường
$result = softAddress($address);

// Sắp xếp lại mảng Address
$i              = 0;
$address_temp   = array();
$num_result     = count($result['results'][0]['waypoints']);
foreach ($result['results'][0]['waypoints'] as $address_new){
    // Nếu mảng duyệt khác điểm đầu và điểm cuối thì làm việc
    if($i != 0 && $i != ($num_result - 1)){
        $check_address = checkArray($address, 'address', $address_new['id']);
        // Nếu bản ghi tồn tại
        if(isset($check_address)){
            $address_temp[] = array('address' => $address_new['id'], 'lat' => $address_new['lat']  , 'lng' => $address_new['lng'], 'weigh' => $address[$check_address]['weigh']);
        }else{
            echo 'Mảng không tồn tại: '.$address_new['id'];
            exit();
        }
    }
    $i++;
}

// Gán lại vị trí mảng Address
$address = $address_temp;
unset($address_temp);

// Gắn mỗi địa chỉ vào 1 xe tương ứng
foreach ($address as $list_address){
    addAddressToTruck($list_address);
}

switch ($_GET['act']){
    case 'detail':
        require_once 'includes/core.php';
        $css_plus       = array('http://trungtamytemelinh.com/admin/app-assets/css/chosen.css');
        $js_plus        = array('http://trungtamytemelinh.com/admin/app-assets/js/chosen.jquery.js', 'http://trungtamytemelinh.com/admin/app-assets/js/prism.js','http://trungtamytemelinh.com/admin/app-assets/js/init.js');
        $admin_title    = 'Hướng Dẫn Chỉ Đường';
        require_once 'header.php';
        ?>
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
                    }
                    function calculateAndDisplayRoute(directionsService, directionsDisplay) {
                        <?php
                            $id_truck = checkArray($truck, 'id', $id);
                            foreach ($truck[$id_truck]['list_address'] AS $truc_address_temp){
                                $waypts[] = '{location : {lat: '. $truc_address_temp['lat'] .', lng: '. $truc_address_temp['lng'] .'}, stopover: true}';
                            }
                            $waypts = implode(',', $waypts);
                        ?>
                        var waypts = [<?php echo $waypts;?>];
                        directionsService.route({
                            origin: 'Phòng khám đa khoa yên hòa',
                            destination: 'Phòng khám đa khoa yên hòa',
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
        require_once 'footer.php';
    break;
    default:
        echo 'Tổng có '. count($address) .' địa chỉ phát với tổng trọng lượng của hàng là: '.sumValueArray($address, 'weigh').' Kg<br />';
        echo 'Tổng có '. count($truck) .' xe, tổng trọng tải của xe là '.sumValueArray($truck, 'payload').' Kg<br />';
        echo '<h3>Chi tiết</h3>';
        foreach ($truck AS $truck_detail){
            if($truck_detail['list_address']){
                echo '<strong>'.$truck_detail['name'].'</strong> trọng tải '. $truck_detail['payload'] .' Kg : Chở '.count($truck_detail['list_address']).' đơn hàng với trọng tải hàng là '.$truck_detail['weigh_now'].'Kg với lộ trình sau<br />';
                echo '<ul>';
                echo '<li>'.$address_start['address'].'</li>';
                foreach ($truck_detail['list_address'] AS $list_address_temp){
                    echo '<li>'. $list_address_temp['address'] .' - '. $list_address_temp['weigh'] .' Kg</li>';
                }
                echo '<li>'.$address_end['address'].'</li>';
                echo '</ul>';
                $result = softAddress($truck_detail['list_address']);
                echo "Tổng quảng đường: ". ($result['results'][0]['distance'] / 1000) .' Km<br />';
                echo "Tổng thời gian: ". gmdate("H:i:s", $result['results'][0]['time']) .' Phút<br />';
                echo '<a href="http://112.78.11.14/optimal.php?act=detail&id='. $truck_detail['id'] .'">Xem trên maps</a><hr />';
            }else{
                echo '<strong>'.$truck_detail['name'].'</strong> trọng tải '. $truck_detail['payload'] .' Kg : Đã hết hàng để chở<br />';
            }
        }
        break;
}

/*----------------------------------------F...U...N...C...T...I...O...N...........................................*/

// Function thêm 1 địa chỉ vào 1 xe tương ứng
function addAddressToTruck($address, $i = 0){
    global $truck;
    if($i >= count($truck)){
        return false;
    }else{
        // Nếu xe chưa đầy thì thêm vào
        if($truck[$i]['status_Full'] == false){
            $truck_temp_payload     = $truck[$i]['payload'];
            $truck_temp_weigh_now   = $truck[$i]['weigh_now'];
            $truck_temp_weigh_after = $truck_temp_weigh_now + $address['weigh'];
            // Nếu trọng tải sau khi cộng lớn hơn trọng tải của xe thì thêm địa chỉ vào xe này
            if($truck_temp_weigh_after <= $truck_temp_payload){
                $truck[$i]['weigh_now']         = $truck_temp_weigh_after;
                $truck[$i]['list_address'][]    = $address;
            }else{
                $truck[$i]['status_Full'] == true;
                addAddressToTruck($address, $i + 1);
            }
        }else{
            addAddressToTruck($address, $i + 1);
        }
    }
}


/*
 * Tính tổng 1 cột trong các mảng array
 * countValueArray($text, array('key' => 'name', 'value' => 'chinh') ,  'num');
 * Data: Mảng cần tính
 * Where: Điều kiện các cột
 * Key: Tính tổng ở Colum Key
 *
 * */
function sumValueArray($data ,$key){
    $count = 0;
    foreach ($data as $detail){
        $count += $detail[$key];
    }
    return $count;
}

// Sắp xếp mảng đa chiều
function sortTruckByPayload(){
    global $truck;
    foreach($truck as $arrays){
        foreach($arrays as $key=>$value){
            if(!isset($sortArray[$key])){
                $sortArray[$key] = array();
            }
            $sortArray[$key][] = $value;
        }
    }
    return array_multisort($sortArray['payload'],SORT_DESC, $truck);
}

// Kiểm tra với xem dữ liệu trong array có tồn tại không
function checkArray($array, $key, $value){
    $num = array_search($value, array_column($array, $key));
    if(isset($num)){
        return $num;
    }else{
        return false;
    }
}

function softAddress($address){
    global $app_code, $app_id, $address_start, $address_end,$optimal_type,$time_start,$break_time;
    $url                    = 'https://wse.api.here.com/2/findsequence.json?';
    $data                   = array();
    $data['app_code']       = $app_code;
    $data['app_id']         = $app_id;
    $data['start']          = 'Phòng khám đa khoa yên hòa start;21.022248,105.786536';
    $data['end']            = 'Phòng khám đa khoa yên hòa end;21.022248,105.786536';
    $data['mode']           = 'fastest;car;traffic:enabled;';
    $data['improveFor']     = $optimal_type; // Tối ưu hóa theo (distance: tuyến đường, time: thời gian)
    $data['departure']      = $time_start; // Thời gian khởi hành
    $i                      = 0;
    foreach ($address AS $list_address){
        $i++;
        $data['destination'.$i] = $list_address['address'].';'.$list_address['lat'].','.$list_address['lng'].';st:'.$break_time;
    }
    foreach ($data as $key => $value){
        $datas[] = $key .'='. urlencode($value);
    }
    $data                   = implode('&', $datas);
    $ch = curl_init($url.$data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '');
    $result = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($result, true);
    if($result['responseCode'] == 200){
        return $result;
    }else{
        print_r($result);
        exit();
    }
}

$endtime = microtime(true);
echo '<br />Tải trang mất '. ceil(($endtime - $starttime)).' giây';