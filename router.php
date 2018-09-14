<?php
/**
 * Created by PhpStorm.
 * User: Dong
 * Date: 31/08/2018
 * Time: 13:49
 */
$admin_title = 'Tìm địa chỉ';
require_once 'header.php';
?>
    <form class="form form-horizontal">
        <div class="row">
            <div class="col"><input id="geocomplete" class="form-control  round" type="text" placeholder="Nhập địa chỉ" value="Tòa nhà Sông Đà" /></div>
            <div class="col"><input class="form-control  round" name="formatted_address" placeholder="Địa chỉ chuẩn" type="text" value=""></div>
        </div>
        <div class="row" style="padding-top: 10px">
            <div class="col"><input class="form-control round" name="lat" type="text" value="" placeholder="Lat"></div>
            <div class="col"><input class="form-control round" name="lng" type="text" value="" placeholder="Lng"></div>
            <div class="col text-right"><input id="find" type="button" class="btn btn-round btn-primary" value="Lấy thông tin" /></div>
        </div>
    </form>
<div class="row">
    <div class="col">
        <div class="map_canvas" style="width: 100%;height: 400px;margin: 10px 20px 10px 0;"></div>
        <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBbCKsqeLxW--Y_Ka8yOIenEg2QvCHKVzY&libraries=places"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="app-assets/js/jquery.geocomplete.js"></script>
        <script>
            $(function(){
                $("#geocomplete").geocomplete({
                    map: ".map_canvas",
                    details: "form ",
                    markerOptions: {
                        draggable: true
                    }
                });

                $("#geocomplete").bind("geocode:dragged", function(event, latLng){
                    $("input[name=lat]").val(latLng.lat());
                    $("input[name=lng]").val(latLng.lng());
                    $("#reset").show();
                });


                $("#reset").click(function(){
                    $("#geocomplete").geocomplete("resetMarker");
                    $("#reset").hide();
                    return false;
                });

                $("#find").click(function(){
                    $("#geocomplete").trigger("geocode");
                }).click();
            });
        </script>
    </div>
</div>
<?php
require_once 'footer.php';

