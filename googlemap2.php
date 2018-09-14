<?php
$key                    = 'AIzaSyBbCKsqeLxW--Y_Ka8yOIenEg2QvCHKVzY'; // google maps
$json                   = file_get_contents('http://115.84.183.206:8099/api/Lading/GetInformationLading?Id='.$_GET['id']);
$json                   = json_decode($json, true);
// Check Empty
if(count($json) == 0){
    echo '<center>Hiện chưa có địa chỉ phát nào.</center>';
    exit();
}

// Check Google Maps 23 Waypoint
if(count($json) > 23){
    echo '<center>Chỉ được tối đa 23 địa chỉ.</center>';
    exit();
}

$json                   = unique_multidim_array($json, 'Address');
$waypoints              = array();
$i                      = 0;
foreach ($json AS $address){
    $i++;
    $waypoints[] = '{location : "'. $address['Address'] .'", stopover: true}';
    if($i == 1){
        $detail = getLocationFromAddress($address['Address']);
        if($detail['city'] == 'Hà Nội'){
            $address_start          = 'Phòng khám đa khoa yên hòa, Hà Nội';
            $address_end            = 'Phòng khám đa khoa yên hòa, Hà Nội';
            $location_lat           = '21.022248';
            $location_lg            = '105.786536';
        }else if($detail['city'] == 'Hồ Chí Minh'){
            $address_start          = '2b Phổ Quang Phường 2 Tân Bình HCM';
            $address_end            = '2b Phổ Quang Phường 2 Tân Bình HCM';
            $location_lat           = '10.803155';
            $location_lg            = '106.666289';
        }else{
            $address_start          = 'Phòng khám đa khoa yên hòa, Hà Nội';
            $address_end            = 'Phòng khám đa khoa yên hòa, Hà Nội';
            $location_lat           = '21.022248';
            $location_lg            = '105.786536';
        }
    }
}
$waypoints              = implode(',', $waypoints);
function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

function getLocationFromAddress($address){
    global $key;
    $google_search  = 'https://maps.googleapis.com/maps/api/geocode/json?address='. urlencode($address) .'&key='.$key;
    $google_search  = file_get_contents($google_search);
    $google_search  = json_decode($google_search, true);
    if($google_search['status'] == 'OK'){
        $lat            = $google_search['results'][0]['geometry']['location']['lat'];
        $lng            = $google_search['results'][0]['geometry']['location']['lng'];
        $name           = $google_search['results'][0]['address_components'][0]['short_name'].' '.$google_search['results'][0]['address_components'][1]['short_name'];
        $name           = str_replace(',', '', $name);
        $full_name      = $google_search['results'][0]['formatted_address'];
        $full_name      = trim(str_replace(array(',', 'Việt Nam','Vietnam'), '', $full_name));
        $district       = $google_search['results'][0]['address_components'];
        foreach ($district AS $district_detail){
            $num = array_search('administrative_area_level_1', $district_detail['types']);
            if(is_numeric($num)){
                $city = $district_detail['long_name'];
            }
        }
        return array('lat' => $lat, 'lng' => $lng, 'short_name' => $name, 'long_name' => '', 'full_name' => $full_name, 'city' => $city);
    }else{
        return 'Không tìm thấy địa chỉ: '.$address;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">
    <title>Waypoints in Directions</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <style>
        html, body {
            height: 500px;
            margin: 0;
            padding: 0;
        }
        #map {
            height: 100%;
            float: top;
            width: 100%;
        }
        .scrollup {
            width: 48px;
            height: 48px;
            position: fixed;
            bottom: 10px;
            right: 10px;
            display: none;
            text-indent: -9999px;
            background: url('images/up.png') no-repeat
        }
    </style>
</head>
<body>
<div id="map"></div>
<div class="text-center">
    <a href="#maps_detail"><button type="button" id="button_detail" style="width : 100%" class="btn btn-outline-info">Xem chi tiết đường đi</button></a>
</div><br />
<div id="maps_detail">
    <ol id="maps_direct"></ol>
    <a href="#" class="scrollup" id="myBtn">Scroll</a>
</div>
<br />
<script language="JavaScript">
    /* Custom*/

    window.onscroll = function() {scrollFunction()};
    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("myBtn").style.display = "block";
        } else {
            document.getElementById("myBtn").style.display = "none";
        }
    }
    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

    function alertSize(type) {
        var myWidth = 0, myHeight = 0;
        if( typeof( window.innerWidth ) == 'number' ) {
            //Non-IE
            myWidth = window.innerWidth;
            myHeight = window.innerHeight;
        } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
            //IE 6+ in 'standards compliant mode'
            myWidth = document.documentElement.clientWidth;
            myHeight = document.documentElement.clientHeight;
        } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
            //IE 4 compatible
            myWidth = document.body.clientWidth;
            myHeight = document.body.clientHeight;
        }
        if(type == 'width'){
            return myWidth;
        }else if(type == 'widthButton'){
            return (myHeight - 95) - Math.ceil((myHeight * 85) / 100);
        }else{
            return (Math.ceil((myHeight * 92) / 100)) + 25;
        }
    }
    document.getElementById("map").style.width  = "100%";
    document.getElementById("map").style.height = "100%";
    document.getElementById("map").style.float  = "top";
    document.body.style.height                  = alertSize('height') + "px";
    document.getElementById("button_detail").style.height  = alertSize('widthButton') + "px";

    function initMap() {
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: {lat: 41.85, lng: -87.65}
        });
        directionsDisplay.setMap(map);
        calculateAndDisplayRoute(directionsService, directionsDisplay);
    }

    function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        var waypts = [<?php echo $waypoints;?>];
        /*var checkboxArray = document.getElementById('waypoints');
        for (var i = 0; i < checkboxArray.length; i++) {
            if (checkboxArray.options[i].selected) {
                waypts.push({
                    location: checkboxArray[i].value,
                    stopover: true
                });
            }
        }*/

        directionsService.route({
            origin: "<?php echo $address_start?>",
            destination: "<?php echo $address_end?>",
            waypoints: waypts,
            optimizeWaypoints: true,
            travelMode: 'DRIVING'
        }, function(response, status) {
            if (status === 'OK') {
                directionsDisplay.setDirections(response);
                var route = response.routes[0];
                var summaryPanel = document.getElementById('maps_direct');
                summaryPanel.innerHTML = '';
                // For each route, display summary information.
                for (var i = 0; i < route.legs.length; i++) {
                    if(i == 0){
                        summaryPanel.innerHTML += "<li>" + route.legs[i].start_address + "</li>";
                        summaryPanel.innerHTML += "<li>" + route.legs[i].end_address + "</li>";
                    }else{
                        summaryPanel.innerHTML += "<li>" + route.legs[i].end_address + "</li>";
                    }
                }
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo $key;?>&callback=initMap">
</script>
</body>
</html>