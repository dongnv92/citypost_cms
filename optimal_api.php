<?php
error_reporting(0);
header("Content-type: application/json; charset=utf-8");
date_default_timezone_set('Asia/Ho_Chi_Minh');

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
$type               = $data_source['type'];
$error              = array();
switch ($type){
    case 'truck':
        if(!$truck){
            $error['truck'] = 'No Truck';
        }
        if(!$address_start){
            $error['address_start'] = 'No Address Start';
        }
        if(!$address_end){
            $error['address_end'] = 'No address End';
        }
        if(!$address){
            $error['address_end'] = 'No Address';
        }
        if(!in_array($optimal_type, array('time', 'distance'))){
            $error['optimal_type'] = 'Optimal Wrong Type';
        }

        if($error){
            echo json_encode($error);
            break;
        }

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
        unset($address);
        $address = $address_temp;
        unset($address_temp);

        // Gắn mỗi địa chỉ vào 1 xe tương ứng
        foreach ($address as $list_address){
            addAddressToTruck($list_address);
        }
        $data = array(
            'type'          => $type,
            'address_start' => $address_start,
            'address_end'   => $address_end,
            'truck_list'    => $truck,
            'wayspoint'     => $address,
            'break_time'    => $break_time,
            'time_start'    => $time_start,
            'optimal_type'  => $optimal_type // distance | time
        );
        echo json_encode($data);
        break;
    case 'optimal_address':
        if(!$address_start){
            $error['address_start'] = 'No Address Start';
        }
        if(!$address_end){
            $error['address_end'] = 'No address End';
        }
        if(!$address){
            $error['address_end'] = 'No Address';
        }
        if($error){
            echo json_encode($error);
            break;
        }

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
        unset($address);
        $address = $address_temp;
        unset($address_temp);
        $data = array(
            'type'          => $type,
            'address_start' => $address_start,
            'address_end'   => $address_end,
            'wayspoint'     => $address,
            'break_time'    => $break_time,
            'time_start'    => $time_start,
            'optimal_type'  => $optimal_type // distance | time
        );
        echo json_encode($data);
        break;
    case 'district':
        $data = getDistrictFromAddress($address);
        echo json_encode($data);
        break;
}

/*----------------------------------------F...U...N...C...T...I...O...N---------------------------------------*/

function getDistrictFromAddress($address){
    $list = array();
    foreach ($address AS $list_address){
        $detail     = getDetailAddress($list_address['address']);
        $district   = $detail['results'][0]['address_components'];
        foreach ($district AS $district_detail){
            $num = array_search('administrative_area_level_2', $district_detail['types']);
            if(is_numeric($num)){
                $list[$district_detail['long_name']][] = $list_address;
            }
        }
    }
    return $list;
}

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
                $truck[$i]['status_Full'] = true;
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
    if(is_numeric($num)){
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
    $data['start']          = $address_start['address'] ? $address_start['address'].';'.$address_start['lat'].','.$address_start['lng'] :  $address_start['lat'].','.$address_start['lng'];
    $data['end']            = $address_end['address'] ? $address_end['address'].';'.$address_end['lat'].','.$address_end['lng'] :  $address_end['lat'].','.$address_end['lng'];
    $data['mode']           = 'fastest;car;traffic:enabled;';
    $data['improveFor']     = $optimal_type; // Tối ưu hóa theo (distance: tuyến đường, time: thời gian)
    $data['departure']      = $time_start; // Thời gian khởi hành
    $i                      = 0;
    foreach ($address AS $list_address){
        $i++;
        $lat = $list_address['lat'];
        $lng = $list_address['lng'];
        $add = $list_address['address'];
        if($add && (!$lat || !$lng)){
            $getLocation    = getLocationFromAddress($add);
            $lat            = $getLocation['lat'];
            $lng            = $getLocation['lng'];
        }
        $data['destination'.$i] = $add.';'. $lat .','. $lng .';st:'.$break_time;
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

function getLocationFromAddress($address){
    global $key;
    $google_search  = 'https://maps.googleapis.com/maps/api/geocode/json?address='. urlencode($address) .'&key='.$key;
    $google_search  = file_get_contents($google_search);
    $google_search  = json_decode($google_search, true);
    $lat            = $google_search['results'][0]['geometry']['location']['lat'];
    $lng            = $google_search['results'][0]['geometry']['location']['lng'];
    $name           = $google_search['results'][0]['address_components'][0]['short_name'].' '.$google_search['results'][0]['address_components'][1]['short_name'];
    $name           = str_replace(',', '', $name);
    $full_name      = $google_search['results'][0]['formatted_address'];
    return array('lat' => $lat, 'lng' => $lng, 'short_name' => $name, 'full_name' => $full_name);
}

function getDetailAddress($address){
    global $key;
    $google_search  = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='. urlencode($address) .'&key='.$key);
    $google_search  = json_decode($google_search, true);
    return $google_search;
}

