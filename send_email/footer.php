<?php
require_once 'includes/core.php';
?>
</div>
    </div>
<!-- ////////////////////////////////////////////////////////////////////////////-->
<footer class="footer footer-static footer-light navbar-border navbar-shadow">
    <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
        <span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2018 <a class="text-bold-800 grey darken-2" href="http://citypost.com.vn" target="_blank">CITYPOST.COM.VN </a> Công Ty Cổ Phần Bưu Chính Thành Phố - Tầng 6, Tháp B, Tòa Nhà Sông Đà, đường Phạm Hùng, Nam Từ Niêm, Hà Nội</span>
    </p>
</footer>
<!-- BEGIN PAGE VENDOR JS-->
<!--<script src="http://code.jquery.com/jquery-latest.min.js"></script>-->
<script src="app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<!-- BEGIN MODERN JS-->
<script src="app-assets/js/core/app-menu.js" type="text/javascript"></script>
<script src="app-assets/js/core/app.js" type="text/javascript"></script>
<!-- PLUS -->
<script>tinymce.init({ selector:'textarea' });</script>
<!-- PLUS -->
<?php
foreach ($js_plus AS $js){
    echo '<script src="'. $js .'" type="text/javascript"></script>'."\n";
}
?>
<script>
    $(document).ready(function () {
        <?php if($module == 'email' && $act == 'add'){ ?>
        //toastr.info('Thêm Địa Chỉ Email Thành Công', 'Thêm Email', {"closeButton": true, positionClass: 'toast-bottom-right', containerId: 'toast-bottom-right'});
        $('#add_email').click(function () {
            var email_address   = $('input[name=email_address]').val();
            var email_name      = $('input[name=email_name]').val();
            var email_location  = $('select[name=email_location] option:selected').val();
            var email_ver       = $('input[name=email_ver]').val();
            if(!email_address){
                swal("Warning!", "Bạn chưa nhập địa chỉ Email","warning");
                return false;
            }
            if(!/(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@[*[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+]*/.test(email_address)) {
                swal("Warning!", "Email không đúng định dạng","warning");
                return false;
            }

            $.ajax({
                url         : '<?php echo _URL_API?>',
                data        : {'act' : 'add_email', 'email' : email_address, 'email_name' : email_name, 'email_location' : email_location, 'email_ver' : email_ver, 'list_unsubcrice' : 0},
                dataType    : 'json',
                method      : 'POST',
                beforeSend  : function () {
                    $('#add_email').html('Đang Gửi Email ....');
                    $('#images_loading').show();
                },
                success     : function (data) {
                    toastr.info(data.message, 'Get Danh Sách', {"closeButton": true, positionClass: 'toast-bottom-right', containerId: 'toast-bottom-right'});
                    $('#add_email').html('Thêm Email');
                }
            });

            return false;
        });
        <?php } else if($module == 'email' && $act == 'send'){ ?>
        $('#send').click(function () {
            var email_top       = $('input[name=email_top]').val();
            var email_location  = $('#email_location').val();
            var email_ver       = $('#email_ver').val();
            var email_number    = $('input[name=email_number]').val();
            if($('input[name=type]').is(':checked')){
                var type = $('input[name=type]').val();
            }else{
                var type = '';
            }
            $.ajax({
                url         : '<?php echo _HOME?>/ajax.php',
                data        : {'act' : 'get_list_email', 'email_top' : email_top, 'email_location' : email_location, 'email_ver' : email_ver, 'email_nunber_send' : email_number, 'type' : type},
                method      : 'POST',
                beforeSend  : function () {
                    $('#send').html('Đang Gửi Email ....');
                    $('#images_loading').show();
                },
                success     : function (data) {
                    toastr.info('Lấy Xong Danh Sách Email', 'Get Danh Sách', {"closeButton": true, positionClass: 'toast-bottom-right', containerId: 'toast-bottom-right'});
                    $('#send').html('Gửi Email');
                    $('#result').html(data);
                    $('#result_parent').show();
                    $('#images_loading').hide();
                }
            });
            return false;
        });
        <?php }?>
    });
</script>
</body>
</html>