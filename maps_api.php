<?php
require_once 'includes/core.php';
define('_WAYPOINT_ST', '900'); // Thời gian  nghỉ tại mỗi điểm giao hàng
$app_id                 = 'Qh8TrDFLaB18FUtvss1Q'; // Here Maps
$app_code               = '05y5O5MgLVe0xHFNgwKYpw'; // Here Maps
$key                    = 'AIzaSyBbCKsqeLxW--Y_Ka8yOIenEg2QvCHKVzY'; // google maps
$json                   = file_get_contents('http://115.84.183.206:8099/api/Lading/GetInformationLading?Id='.$id);
$json                   = json_decode($json, true);

// Check Empty
if(count($json) == 0){
    echo '<center>Hiện chưa có địa chỉ phát nào.</center>';
    exit();
}

function getLocationFromAddress($address){
    global $key;
    $google_search = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . $key;
    $google_search = file_get_contents($google_search);
    $google_search = json_decode($google_search, true);
    if ($google_search['status'] == 'OK') {
        $lat = $google_search['results'][0]['geometry']['location']['lat'];
        $lng = $google_search['results'][0]['geometry']['location']['lng'];
        $name = $google_search['results'][0]['address_components'][0]['short_name'] . ' ' . $google_search['results'][0]['address_components'][1]['short_name'];
        $name = str_replace(',', '', $name);
        $full_name = $google_search['results'][0]['formatted_address'];
        $full_name = trim(str_replace(array(',', 'Việt Nam', 'Vietnam'), '', $full_name));
        $district = $google_search['results'][0]['address_components'];
        foreach ($district AS $district_detail) {
            $num = array_search('administrative_area_level_1', $district_detail['types']);
            if (is_numeric($num)) {
                $city = $district_detail['long_name'];
            }
        }
        return array('lat' => $lat, 'lng' => $lng, 'short_name' => $name, 'long_name' => '', 'full_name' => $full_name, 'city' => $city);
    } else {
        return 'Không tìm thấy địa chỉ: ' . $address;
    }
}

function unique_multidim_array($array, $key){
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}
$json = unique_multidim_array($json, 'Address');

if(count($json) > 23) {
    $address = $data = array();
    $url = 'https://wse.api.here.com/2/findsequence.json?';
    $data['app_code'] = $app_code;
    $data['app_id'] = $app_id;
    $data['mode'] = 'fastest;car;';
    $data['improveFor'] = 'distance';
    $data['departure'] = date('Y-m-d', time()) . 'T09:00:00+07';
    $i = 0;

    foreach ($json AS $address) {
        $i++;
        $data_addr = getLocationFromAddress($address['Address']);
        $data_addr['short_name'] = str_replace(',', '', $data_addr['short_name']);
        $address['Address'] = str_replace(',', '', $address['Address']);
        $data['destination' . $i] = $data_addr['full_name'] . ' - ' . $i . ';' . $data_addr['lat'] . ',' . $data_addr['lng'] . ';st:' . _WAYPOINT_ST;
        if ($i == 1) {
            $city = $data_addr['city'];
            if ($city == 'Hà Nội') {
                $data['start'] = 'Phòng khám đa khoa yên hòa Start;21.022248,105.786536';
                $data['end'] = 'Phòng khám đa khoa yên hòa End;21.022248,105.786536';
                $location_default = explode(';', $data['start']);
                $location_default = explode(',', $location_default[1]);
            } else if ($city == 'Hồ Chí Minh') {
                $data['start'] = '2b Phổ Quang Phường 2 Tân Bình HCM Start;10.803155,106.666289';
                $data['end'] = '2b Phổ Quang Phường 2 Tân Bình HCM End;10.803155,106.666289';
                $location_default = explode(';', $data['start']);
                $location_default = explode(',', $location_default[1]);
            } else {
                $data['start'] = 'Phòng khám đa khoa yên hòa start;21.022248,105.786536';
                $data['end'] = 'Phòng khám đa khoa yên hòa end;21.022248,105.786536';
                $location_default = explode(';', $data['start']);
                $location_default = explode(',', $location_default[1]);
            }
        }
    }

    foreach ($data as $key => $value) {
        $datas[] = $key . '=' . urlencode($value);
    }
    $data = implode('&', $datas);
    $ch = curl_init($url . $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '');
    $result = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($result, true);
    if ($result['responseCode'] != 200) {
        print_r($result);
        exit();
    }

    $i = 0;
    foreach ($result['results'][0]['waypoints'] AS $address_away) {
        $waypoint[] = 'waypoint' . $i . ':\'' . $address_away['lat'] . ',' . $address_away['lng'] . "'";
        $i++;
    }
    $waypoints = implode(',', $waypoint);
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Tối ưu hóa tuyến đường - CITYPOST</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" type="text/css"
              href="https://js.api.here.com/v3/3.0/mapsjs-ui.css?dp-version=1533195059"/>
        <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-core.js"></script>
        <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-service.js"></script>
        <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-ui.js"></script>
        <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-mapevents.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
              integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
              crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
                integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
                integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
                integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
                crossorigin="anonymous"></script>
        <script language="JavaScript">
            function Watch_Tuturiol() {
                var x = document.getElementById("maps_detail");
                if (x.style.display === "none") {
                    x.style.display = "block";
                } else {
                    x.style.display = "none";
                }
            }
        </script>
        <style type="text/css">
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
    <div id="map" style=""></div>
    <div class="text-center">
        <a href="#maps_detail">
            <button type="button" id="button_detail" style="width : 100%" class="btn btn-outline-info">Xem chi tiết
                đường đi
            </button>
        </a>
    </div>
    <br/>
    <div id="maps_detail">
        <ol>
            <?php
            $i = 0;
            foreach ($result['results'][0]['interconnections'] AS $address_away) {
                if ($i == 0) {
                    echo '<li>' . $address_away['fromWaypoint'] . '</li>';
                    echo '<li>' . $address_away['toWaypoint'] . '</li>';
                } else {
                    echo '<li>' . $address_away['toWaypoint'] . '</li>';
                }

                $i++;
            }
            ?>
        </ol>
        <a href="#" class="scrollup" id="myBtn">Scroll</a>
    </div>
    <script language="JavaScript">
        window.onscroll = function () {
            scrollFunction()
        };

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
            if (typeof(window.innerWidth) == 'number') {
                //Non-IE
                myWidth = window.innerWidth;
                myHeight = window.innerHeight;
            } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
                //IE 6+ in 'standards compliant mode'
                myWidth = document.documentElement.clientWidth;
                myHeight = document.documentElement.clientHeight;
            } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
                //IE 4 compatible
                myWidth = document.body.clientWidth;
                myHeight = document.body.clientHeight;
            }
            if (type == 'width') {
                return myWidth;
            } else if (type == 'widthButton') {
                return (myHeight - 95) - Math.ceil((myHeight * 85) / 100);
            } else {
                return (Math.ceil((myHeight * 92) / 100)) + 25;
            }
        }

        document.getElementById("map").style.backgroundColor = "gray";
        document.getElementById("map").style.width = "100%";
        document.getElementById("map").style.height = alertSize('height') + "px";
        document.getElementById("button_detail").style.height = alertSize('widthButton') + "px";
    </script>
    <script type="text/javascript" charset="UTF-8">
        function calculateRouteFromAtoB(platform) {
            var router = platform.getRoutingService(),
                routeRequestParams = {
                    mode: 'fastest;car',
                    representation: 'display',
                    routeattributes: 'waypoints,summary,shape,legs',
                    maneuverattributes: 'direction,action',
                    <?php echo $waypoints;?>
                };

            router.calculateRoute(
                routeRequestParams,
                onSuccess,
                onError
            );
        }

        /**
         * This function will be called once the Routing REST API provides a response
         * @param  {Object} result          A JSONP object representing the calculated route
         *
         * see: http://developer.here.com/rest-apis/documentation/routing/topics/resource-type-calculate-route.html
         */
        function onSuccess(result) {
            var route = result.response.route[0];
            /*
             * The styling of the route response on the map is entirely under the developer's control.
             * A representitive styling can be found the full JS + HTML code of this example
             * in the functions below:
             */
            addRouteShapeToMap(route);
            addManueversToMap(route);

            addWaypointsToPanel(route.waypoint);
            addManueversToPanel(route);
            addSummaryToPanel(route.summary);
            // ... etc.
        }

        /**
         * This function will be called if a communication error occurs during the JSON-P request
         * @param  {Object} error  The error message received.
         */
        function onError(error) {
            alert('Ooops!');
        }

        // set up containers for the map  + panel
        var mapContainer = document.getElementById('map'),
            routeInstructionsContainer = document.getElementById('panel');

        //Step 1: initialize communication with the platform
        var platform = new H.service.Platform({
            app_id: '<?php echo $app_id;?>',
            app_code: '<?php echo $app_code;?>',
            useHTTPS: true,
            lang: 'vi'
        });
        var pixelRatio = window.devicePixelRatio || 1;
        var defaultLayers = platform.createDefaultLayers({
            tileSize: pixelRatio === 1 ? 256 : 512,
            ppi: pixelRatio === 1 ? undefined : 320
        });

        //Step 2: initialize a map - this map is centered over Berlin
        var map = new H.Map(mapContainer,
            defaultLayers.normal.map, {
                center: {lat:<?php echo $location_default[0];?>, lng:<?php echo $location_default[1];?>},
                zoom: 13,
                pixelRatio: pixelRatio
            });

        //Step 3: make the map interactive
        // MapEvents enables the event system
        // Behavior implements default interactions for pan/zoom (also on mobile touch environments)
        var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

        // Create the default UI components
        var ui = H.ui.UI.createDefault(map, defaultLayers);

        // Hold a reference to any infobubble opened
        var bubble;

        /**
         * Opens/Closes a infobubble
         * @param  {H.geo.Point} position     The location on the map.
         * @param  {String} text              The contents of the infobubble.
         */
        function openBubble(position, text) {
            if (!bubble) {
                bubble = new H.ui.InfoBubble(
                    position,
                    // The FO property holds the province name.
                    {content: text});
                ui.addBubble(bubble);
            } else {
                bubble.setPosition(position);
                bubble.setContent('Dong: ' + text);
                bubble.open();
            }
        }


        /**
         * Creates a H.map.Polyline from the shape of the route and adds it to the map.
         * @param {Object} route A route as received from the H.service.RoutingService
         */
        function addRouteShapeToMap(route) {
            var lineString = new H.geo.LineString(),
                routeShape = route.shape,
                polyline;

            routeShape.forEach(function (point) {
                var parts = point.split(',');
                lineString.pushLatLngAlt(parts[0], parts[1]);
            });

            polyline = new H.map.Polyline(lineString, {
                style: {
                    lineWidth: 5,
                    strokeColor: 'rgba(0, 128, 255, 0.7)'
                }
            });
            // Add the polyline to the map
            map.addObject(polyline);
            // And zoom to its bounding rectangle
            map.setViewBounds(polyline.getBounds(), true);
        }


        /**
         * Creates a series of H.map.Marker points from the route and adds them to the map.
         * @param {Object} route  A route as received from the H.service.RoutingService
         */
        function addManueversToMap(route) {
            var markupTemplate = '<svg xmlns="http://www.w3.org/2000/svg" width="28px" height="36px">' +
                '<path d="M 19 31 C 19 32.7 16.3 34 13 34 C 9.7 34 7 32.7 7 31 C 7 29.3 9.7 28 13 28 C 16.3 28 19 29.3 19 31 Z" fill="#000" fill-opacity=".2"/>' +
                '<path d="M 13 0 C 9.5 0 6.3 1.3 3.8 3.8 C 1.4 7.8 0 9.4 0 12.8 C 0 16.3 1.4 19.5 3.8 21.9 L 13 31 L 22.2 21.9 C 24.6 19.5 25.9 16.3 25.9 12.8 C 25.9 9.4 24.6 6.1 22.1 3.8 C 19.7 1.3 16.5 0 13 0 Z" fill="#fff"/>' +
                '<path d="M 13 2.2 C 6 2.2 2.3 7.2 2.1 12.8 C 2.1 16.1 3.1 18.4 5.2 20.5 L 13 28.2 L 20.8 20.5 C 22.9 18.4 23.8 16.2 23.8 12.8 C 23.6 7.07 20 2.2 13 2.2 Z" fill="#18d"/>' +
                '<text x="13" y="19" font-size="12pt" font-weight="bold" text-anchor="middle" fill="#fff">${text}</text>' +
                '</svg>',
                group = new H.map.Group(),
                i,
                j;

            // Add a marker for each maneuver
            for (i = 0; i < route.leg.length; i += 1) {
                for (j = 0; j < route.leg[i].maneuver.length; j += 1) {
                    if (j == (route.leg[i].maneuver.length - 1) || (i == 0 && j == 0)) {
                        maneuver = route.leg[i].maneuver[j];
                        var replaceText = (i == 0 && j == 0) ? i + 1 : i + 2;
                        if (i == (route.leg.length - 1) && j == (route.leg[i].maneuver.length - 1)) {
                            replaceText = 1;
                        }
                        var markup = markupTemplate.replace('${text}', replaceText,)
                        icon = new H.map.Icon(markup),
                            marker = new H.map.Marker({
                                    lat: maneuver.position.latitude,
                                    lng: maneuver.position.longitude
                                },
                                {icon: icon});
                        marker.instruction = maneuver.instruction;
                        group.addObject(marker);
                    }
                }
            }
            // End add a marker for each maneuver

            group.addEventListener('tap', function (evt) {
                map.setCenter(evt.target.getPosition());
                openBubble(
                    evt.target.getPosition(), evt.target.instruction);
            }, false);

            // Add the maneuvers group to the map
            map.addObject(group);
        }


        /**
         * Creates a series of H.map.Marker points from the route and adds them to the map.
         * @param {Object} route  A route as received from the H.service.RoutingService
         */
        function addWaypointsToPanel(waypoints) {
            var nodeH3 = document.createElement('h3'),
                waypointLabels = [],
                i;
            for (i = 0; i < waypoints.length; i += 1) {
                waypointLabels.push(waypoints[i].label)
            }
            nodeH3.textContent = waypointLabels.join(' - ');
            routeInstructionsContainer.innerHTML = '';
            routeInstructionsContainer.appendChild(nodeH3);
        }

        /**
         * Creates a series of H.map.Marker points from the route and adds them to the map.
         * @param {Object} route  A route as received from the H.service.RoutingService
         */
        function addSummaryToPanel(summary) {
            var summaryDiv = document.createElement('div'),
                content = '';
            content += '<b>Total distance</b>: ' + summary.distance + 'm. <br/>';
            content += '<b>Travel Time</b>: ' + summary.travelTime.toMMSS() + ' (in current traffic)';


            summaryDiv.style.fontSize = 'small';
            summaryDiv.style.marginLeft = '5%';
            summaryDiv.style.marginRight = '5%';
            summaryDiv.innerHTML = content;
            routeInstructionsContainer.appendChild(summaryDiv);
        }

        /**
         * Creates a series of H.map.Marker points from the route and adds them to the map.
         * @param {Object} route  A route as received from the H.service.RoutingService
         */
        function addManueversToPanel(route) {


            var nodeOL = document.createElement('ol'),
                i,
                j;

            nodeOL.style.fontSize = 'small';
            nodeOL.style.marginLeft = '5%';
            nodeOL.style.marginRight = '5%';
            nodeOL.className = 'directions';

            // Add a marker for each maneuver
            for (i = 0; i < route.leg.length; i += 1) {
                for (j = 0; j < route.leg[i].maneuver.length; j += 1) {
                    // Get the next maneuver.
                    maneuver = route.leg[i].maneuver[j];
                    var li = document.createElement('li'),
                        spanArrow = document.createElement('span'),
                        spanInstruction = document.createElement('span');

                    spanArrow.className = 'arrow ' + maneuver.action;
                    spanInstruction.innerHTML = maneuver.instruction;
                    li.appendChild(spanArrow);
                    li.appendChild(spanInstruction);

                    nodeOL.appendChild(li);
                }
            }

            routeInstructionsContainer.appendChild(nodeOL);
        }


        Number.prototype.toMMSS = function () {
            return Math.floor(this / 60) + ' phút ' + (this % 60) + ' giây.';
        }

        // Now use the map as required...
        calculateRouteFromAtoB(platform);
    </script>
    </body>
    </html>
    <?php
} else {
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
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta charset="utf-8">
        <title>Tối ưu hóa tuyến đường - CITYPOST</title>
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
    <div class="text-center"><a href="#maps_detail"><button type="button" id="button_detail" style="width : 100%" class="btn btn-outline-info">Xem chi tiết đường đi</button></a></div><br />
    <div id="maps_detail"><ol id="maps_direct"></ol><a href="#" class="scrollup" id="myBtn">Scroll</a></div><br />
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
                return (Math.ceil((myHeight * 92) / 100)) + 0;
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
                zoom: 12,
                center: {lat: <?php echo $location_lat?>, lng: <?php echo $location_lg?>}
            });
            directionsDisplay.setMap(map);
            calculateAndDisplayRoute(directionsService, directionsDisplay);
        }

        function calculateAndDisplayRoute(directionsService, directionsDisplay) {
            var waypts = [<?php echo $waypoints;?>];
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
    <?php
}