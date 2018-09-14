<?php
/**
 * Created by PhpStorm.
 * User: Dong
 * Date: 30/08/2018
 * Time: 14:25
 */
error_reporting(0);
date_default_timezone_set('Asia/Ho_Chi_Minh');
header("Content-type: application/json; charset=utf-8");

// Khởi tạo biến ban đầu
$truck          = $address = array();
$address_start  = array('address' => 'Phòng khám đa khoa yên hòa start'  , 'lat' => '21.022248'  , 'lng' => '105.786536');
$address_end    = array('address' => 'Phòng khám đa khoa yên hòa end'  , 'lat' => '21.022248'  , 'lng' => '105.786536');

// Lấy thông tin danh sách và thuộc tính của mỗi xe
$truck[]        = array('id' => 1, 'name' => 'Xe 01', 'payload' => '1250', 'list_address' => array(), 'status_Full' => false, 'weigh_now' => 0);
$truck[]        = array('id' => 2, 'name' => 'Xe 02', 'payload' => '1250', 'list_address' => array(), 'status_Full' => false, 'weigh_now' => 0);
$truck[]        = array('id' => 3, 'name' => 'Xe 03', 'payload' => '850', 'list_address' => array(), 'status_Full' => false, 'weigh_now' => 0);

// Lấy thông tin danh sách và thuộc tính của mỗi điểm phát
$address[]      = array('address' => '[Chu Quang Hung] 1608 16th FLOOR HOANG GIA BUIDING TO HIEU QUANG TRUNG HA DONG HN' , 'lat' => '20.9659173' , 'lng' => '105.7721131'    , 'weigh' => 60); // Chu Quang Hung
$address[]      = array('address' => '[Cao The Anh] 298 Đường Cầu Giấy Quan Hoa Cầu Giấy Hà Nội ' , 'lat' => '' , 'lng' => ''    , 'weigh' => 52); // cao the anh
$address[]      = array('address' => '[Ta Duc Manh] 78 Giải Phóng Phương Đình Đống Đa Hà Nội' , 'lat' => '20.9990499' , 'lng' => '105.8409891'    , 'weigh' => 63); // ta duc manh
$address[]      = array('address' => '[Cao The Anh] 87 Láng Hạ Chợ Dừa Ba Đình Hà Nội' , 'lat' => '21.0167425' , 'lng' => '105.8158368'    , 'weigh' => 89); // cao the anh
$address[]      = array('address' => '[Ta Duc Manh] 85 Nguyễn Chí Thanh Láng Thượng Đống Đa Hà Nội' , 'lat' => '21.022728' , 'lng' => '105.809803'    , 'weigh' => 63); // ta duc manh
$address[]      = array('address' => '[Chu Quang Hung] TANG 4 ME LINH PLAZA TO HIEU HA DONG HA NOI' , 'lat' => '20.9638829' , 'lng' => '105.7718681'    , 'weigh' => 96); // Chu Quang Hung
$address[]      = array('address' => '[Cao The Anh] 90 Trần Thái Tông Dịch Vọng Hậu Cầu Giấy Hà Nội' , 'lat' => '21.0307158' , 'lng' => '105.7877654'    , 'weigh' => 87); // cao the anh
$address[]      = array('address' => '[Chu Quang Hung] Nhà 3 ngõ 207 Lương Thế Vinh Thanh Xuân Hà Nội' , 'lat' => '20.9912464' , 'lng' => '105.7959218'    , 'weigh' => 56); // Chu Quang Hung
$address[]      = array('address' => '[Chu Quang Hung] Số 26 ngõ 80 Phố Lê Trọng Tấn Phường Khương Mai Quận Thanh Xuân Hà Nội' , 'lat' => '20.9984377' , 'lng' => '105.8278889'    , 'weigh' => 53); // Chu Quang Hung
$address[]      = array('address' => '[Cao The Anh] 9 Phố Duy Tân Dịch Vọng Hậu Cầu Giấy Hà Nội' , 'lat' => '21.031299' , 'lng' => '105.782182'    , 'weigh' => 89); // cao the anh
$address[]      = array('address' => '[Chu Quang Hung] P1.3-I10a Phường Thanh Xuân Bắc Quận Thanh Xuân Thành phố Hà Nội' , 'lat' => '20.993579' , 'lng' => '105.7982094'    , 'weigh' => 69); // Chu Quang Hung
$address[]      = array('address' => '[Cao The Anh] 72 Trần Đăng Ninh Dịch Vọng Cầu Giấy Hà Nội' , 'lat' => '21.0384813' , 'lng' => '105.7939319'    , 'weigh' => 78); // cao the anh
$address[]      = array('address' => '[Cao The Anh] 82 Phố Dịch Vọng Hậu Dịch Vọng Hậu Cầu Giấy Hà Nội' , 'lat' => '21.0296967' , 'lng' => '105.785713'    , 'weigh' => 400); // cao the anh
$address[]      = array('address' => '[Chu Quang Hung] Tháp B Tòa The Light CT2 KĐT Trung Văn đường Tố Hữu Phường Trung Văn Nam Từ Liêm Hà Nội' , 'lat' => '20.9929186' , 'lng' => '105.7883634'    , 'weigh' => 63); // Chu Quang Hung
$address[]      = array('address' => '[Chu Quang Hung] B3 - Khu Phức Hợp Mandarin Garden (Khu B) Phường Trung Hòa Cầu Giấy Hà Nội' , 'lat' => '21.0048114' , 'lng' => '105.7987278'    , 'weigh' => 12); // Chu Quang Hung
$address[]      = array('address' => '[Ta Duc Manh] Ngõ 99 Lê Hồng Phong Điện Biên Ba Đình Hà Nội Vietnam' , 'lat' => '21.033957' , 'lng' => '105.831969'    , 'weigh' => 78); // ta duc manh
$address[]      = array('address' => '[Ta Duc Manh] 161 Lương Thế Vinh P. Văn Quán Thanh Xuân Hà Nội Vietnam' , 'lat' => '20.990744' , 'lng' => '105.796445'    , 'weigh' => 12); // ta duc manh
$address[]      = array('address' => '[Ta Duc Manh] 8 Phạm Ngọc Thạch Trung Tự Đống Đa Hà Nội' , 'lat' => '21.007757' , 'lng' => '105.833112'    , 'weigh' => 45); // ta duc manh
$address[]      = array('address' => '[Chu Quang Hung] Tầng 4 Tòa 25T1 N05 Hoàng Đạo Thúy Trung Hòa Cầu Giấy Hà Nội' , 'lat' => '21.007388' , 'lng' => '105.800973'    , 'weigh' => 400); // Chu Quang Hung
$address[]      = array('address' => '[Cao The Anh] Số 24 Hoàng Quốc Việt Nghĩa Đô Cầu Giấy Hà Nội' , 'lat' => '21.0464818' , 'lng' => '105.7985304'    , 'weigh' => 45); // cao the anh
$address[]      = array('address' => '[Cao The Anh] 02 Đường Cầu Giấy Dịch Vọng Cầu Giấy Hà Nội' , 'lat' => '21.0356421' , 'lng' => '105.7939961'    , 'weigh' => 400); // cao the anh
$address[]      = array('address' => '[Ta Duc Manh] 22 Phố Hoàng Cầu Trung Liệt Đống Đa Hà Nội Vietnam' , 'lat' => '21.0140073' , 'lng' => '105.8192627'    , 'weigh' => 78); // ta duc manh
$address[]      = array('address' => '[Chu Quang Hung] Đô Thị Trung Hoà Nhân Chính Phường Nhân Chính Quận Thanh Xuân Thành phố Hà Nội' , 'lat' => '21.0046148' , 'lng' => '105.8040796'    , 'weigh' => 41); // Chu Quang Hung
$address[]      = array('address' => '[Cao The Anh] 241 Xuân Thủy Dịch Vọng Hậu Cầu Giấy Hà Nội' , 'lat' => '21.0364338' , 'lng' => '105.7824212'    , 'weigh' => 67); // cao the anh
$address[]      = array('address' => '[Ta Duc Manh] NGO 99 OLE HONG PHONG BA DINH HNKCN VINH TUY LINH NAM HOANG MAI' , 'lat' => '21.0337815' , 'lng' => '105.8140539'    , 'weigh' => 12); // ta duc manh
$address[]      = array('address' => '[Cao The Anh] tang 1 toa ct2a kdt moi co nhue bac tu liem hn' , 'lat' => '21.0502951' , 'lng' => '105.7850803'    , 'weigh' => 78); // cao the anh
$address[]      = array('address' => '[Cao The Anh] SO 18 KHU DCDC NGO 100 HOANG QUOC VIET HN' , 'lat' => '21.0483269' , 'lng' => '105.7956063'    , 'weigh' => 56); // cao the anh
$address[]      = array('address' => '[Chu Quang Hung] P1116 Royal City 72A Nguyễn Trãi Thanh Xuân Hà Nội' , 'lat' => '21.0009207' , 'lng' => '105.8160354'    , 'weigh' => 400); // Chu Quang Hung
$address[]      = array('address' => '[Cao The Anh] Toà Nhà CMC Duy Tân Quận Cầu Giấy Dịch Vọng Hậu Cầu Giấy Hà Nội ' , 'lat' => '21.0304205' , 'lng' => '105.7844869'    , 'weigh' => 54); // cao the anh
$address[]      = array('address' => '[Cao The Anh] 6 phố Đội Nhân Vĩnh Phú Ba Dinh District Hà Nội' , 'lat' => '21.0408404' , 'lng' => '105.8125851'    , 'weigh' => 23); // cao the anh
$address[]      = array('address' => '[Cao The Anh] 222 Trần Duy Hưng Trung Hoà Cầu Giấy Hà Nội' , 'lat' => '21.0071523' , 'lng' => '105.7934126'    , 'weigh' => 56); // cao the anh
$address[]      = array('address' => '[Cao The Anh] 2 Nguyễn Khả Trạc Mai Dịch Cầu Giấy Hà Nội' , 'lat' => '21.0449015' , 'lng' => '105.7768839'    , 'weigh' => 96); // cao the anh
$address[]      = array('address' => '[Cao The Anh] 11 Ngõ 65 Mai Dịch Mai Dịch Cầu Giấy Hà Nội' , 'lat' => '21.0396284' , 'lng' => '105.7735649'    , 'weigh' => 23); // cao the anh
$address[]      = array('address' => '[Cao The Anh] Tầng 1 Tòa nhà CT2A Ngõ 234 Hoàng Quốc Việt KĐT Nam Cường Nam Từ Liêm Hà Nội' , 'lat' => '21.0502951' , 'lng' => '105.7850803'    , 'weigh' => 0); // cao the anh
$address[]      = array('address' => '[Cao The Anh] 11 Phố Duy Tân Dịch Vọng Hậu Cầu Giấy Hà Nội' , 'lat' => '21.0307522' , 'lng' => '105.7844337'    , 'weigh' => 32); // cao the anh
$address[]      = array('address' => '[Ta Duc Manh] Nam Đồng Đống Đa Hà Nội Vietnam' , 'lat' => '21.0143557' , 'lng' => '105.8312314'    , 'weigh' => 56); // ta duc manh
$address[]      = array('address' => '[Cao The Anh] Số 136 Xuân Thủy Dịch Vọng Hậu Cầu Giấy Hà Nội' , 'lat' => '21.0374852' , 'lng' => '105.7833616'    , 'weigh' => 100); // cao the anh
$address[]      = array('address' => '[Cao The Anh] SO 5 72 Trần Vỹ Mai Dịch Cầu Giấy Hà Nội' , 'lat' => '21.0409125' , 'lng' => '105.7731298'    , 'weigh' => 23); // cao the anh
$address[]      = array('address' => '[Cao The Anh] 31 26 Trần Quốc Hoàn Dịch Vọng Hậu Cầu Giấy Hà Nội' , 'lat' => '21.0417752' , 'lng' => '105.7866713'    , 'weigh' => 12); // cao the anh
$address[]      = array('address' => '[Cao The Anh] P311 N10 KĐT Dịch Vọng phường Dịch Vọng quận Cầu Giấy thành phố Hà Nội' , 'lat' => '21.0347067' , 'lng' => '105.7923394'    , 'weigh' => 96); // cao the anh
$address[]      = array('address' => '[Cao The Anh] 40 Dương Quảng Hàm Quan Hoa Cầu Giấy Hà Nội' , 'lat' => '21.0338152' , 'lng' => '105.7989148'    , 'weigh' => 89); // cao the anh

$data = array(
    'type'          => 'district', // truck (sắp xếp theo khối lượng xe) | optimal_address (tối ưu hóa địa chỉ) | district (gom các địa chỉ cùng huyện vào nhau)
    'address_start' => $address_start,
    'address_end'   => $address_end,
    'truck_list'    => $truck,
    'wayspoint'     => $address,
    'break_time'    => 900,
    'time_start'    => date('Y-m-d', time()).'T09:00:00+07',
    'optimal_type'  => 'distance' // distance | time
);
echo json_encode($data);
