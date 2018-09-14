<?php
require_once 'includes/core.php';
if(!$user_id){
    header('location:'._URL_HOME.'/login.php');
}

$today          = date('Y/m/d', _CONFIG_TIME);
$week_start     = date('Y/m/d', strtotime('monday this week', _CONFIG_TIME));
$week_end       = date('Y/m/d 23:59:59', strtotime('sunday this week', _CONFIG_TIME));
$month_start    = date('Y/m/d', strtotime('first day of this month', _CONFIG_TIME));
$month_end      = date('Y/m/d 23:59:59', strtotime('last day of this month', _CONFIG_TIME));
$year_start     = date('Y/m/d', strtotime('first day of January', _CONFIG_TIME));
$year_end       = date('Y/m/d 23:59:59', strtotime('last day of December', _CONFIG_TIME));

$admin_title = 'Trang chủ - CITYPOST';
require_once 'header.php';
?>
    <!-- Thống kê tổng quan -->
    <section id="grouped-stats">
        <div class="row">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Thống kê tổng quan <button type="button" class="btn btn-outline-primary round btn-min-width mr-1 mb-1">Màu lấy thư</button><button type="button" class="btn btn-outline-warning round btn-min-width mr-1 mb-1">Màu lấy hàng</button></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 border-right-blue-grey border-right-lighten-5">
                                <div class="card-body text-center">
                                    <div class="card-header mb-2">
                                        <span class="success">Bấm lấy thư, hàng hôm nay</span>
                                        <h3 class="display-4 blue-grey darken-1">
                                            <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_day&btnID=1';?>" class="primary">
                                                <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => $today, 'data' => ' AND [btnID] = 1'));?>
                                            </a> |
                                            <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_day&btnID=2';?>" class="warning">
                                                <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => $today, 'data' => ' AND [btnID] = 2'));?>
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="card-content">
                                        <ul class="list-inline clearfix mt-2">
                                            <li class="border-right-blue-grey border-right-lighten-2 pr-2">
                                                <h1 class="blue-grey darken-1 text-bold-400">
                                                    <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_day_success&btnID=1';?>" class="primary">
                                                        <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => $today, 'data' => ' AND ([status_04] = 104 OR [status_04] = 204) AND [btnID] = 1'));?>
                                                    </a> |
                                                    <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_day_success&btnID=2';?>" class="warning">
                                                        <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => $today, 'data' => ' AND ([status_04] = 104 OR [status_04] = 204) AND [btnID] = 2'));?>
                                                    </a>
                                                </h1>
                                                <span class="success"><i class="ft-chevron-up"></i> Hoàn thành</span>
                                            </li>
                                            <li class="pl-2">
                                                <h1 class="blue-grey darken-1 text-bold-400">
                                                    <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_day_false&btnID=1';?>" class="primary">
                                                        <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => $today, 'data' => ' AND ([status_04] <> 104 AND [status_04] <> 204) AND [btnID] = 1'));?>
                                                    </a> |
                                                    <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_day_false&btnID=2';?>" class="warning">
                                                        <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => $today, 'data' => ' AND ([status_04] <> 104 AND [status_04] <> 204) AND [btnID] = 2'));?>
                                                    </a>
                                                </h1>
                                                <span class="danger darken-2"><i class="ft-chevron-down"></i> Chưa hoàn thành</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 border-right-blue-grey border-right-lighten-5">
                                <div class="card-body text-center">
                                    <div class="card-header mb-2">
                                        <span class="warning darken-2">Bấm lấy thư tuần này</span>
                                        <h3 class="display-4 blue-grey darken-1">
                                            <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_week&btnID=1';?>" class="primary">
                                                <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $week_start, 'time_end' => $week_end, 'data' => ' AND [btnID] = 1'))?>
                                            </a> |
                                            <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_week&btnID=2';?>" class="warning">
                                                <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $week_start, 'time_end' => $week_end, 'data' => ' AND [btnID] = 2'))?>
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="card-content">
                                        <ul class="list-inline clearfix mt-2">
                                            <li class="border-right-blue-grey border-right-lighten-2 pr-2">
                                                <h1 class="darken-1 text-bold-400">
                                                    <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_week_success&btnID=1';?>" class="primary">
                                                        <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $week_start, 'time_end' => $week_end, 'data' => 'AND ([status_04] = 104 OR [status_04] = 204) AND [btnID] = 1'))?>
                                                    </a> |
                                                    <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_week_success&btnID=2';?>" class="warning">
                                                        <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $week_start, 'time_end' => $week_end, 'data' => 'AND ([status_04] = 104 OR [status_04] = 204) AND [btnID] = 2'))?>
                                                    </a>
                                                </h1>
                                                <span class="success"><i class="ft-chevron-up"></i> Hoàn thành</span>
                                            </li>
                                            <li class="pl-2">
                                                <h1 class="blue-grey darken-1 text-bold-400">
                                                    <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_week_false&btnID=1';?>" class="primary">
                                                        <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $week_start, 'time_end' => $week_end, 'data' => 'AND [status_04] != 104 AND [btnID] = 1'))?></strong>
                                                    </a> |
                                                    <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_week_false&btnID=2';?>" class="warning">
                                                        <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $week_start, 'time_end' => $week_end, 'data' => 'AND [status_04] != 104 AND [btnID] = 2'))?></strong>
                                                    </a>
                                                </h1>
                                                <span class="danger darken-2"><i class="ft-chevron-down"></i> Chưa hoàn thành</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 border-right-blue-grey border-right-lighten-5">
                                <div class="card-body text-center">
                                    <div class="card-header mb-2">
                                        <span class="danger">Bấm lấy thư tháng này</span>
                                        <h3 class="display-4 blue-grey darken-1">
                                            <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_month&btnID=1';?>" class="primary">
                                                <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $month_start, 'time_end' => $month_end, 'data' => ' AND [btnID] = 1'))?>
                                            </a> |
                                            <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_month&btnID=2';?>" class="warning">
                                                <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $month_start, 'time_end' => $month_end, 'data' => ' AND [btnID] = 2'))?>
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="card-content">
                                        <ul class="list-inline clearfix mt-2">
                                            <li class="border-right-blue-grey border-right-lighten-2 pr-2">
                                                <h1 class="blue-grey darken-1 text-bold-400">
                                                    <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_month_success&btnID=1';?>" class="primary">
                                                        <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $month_start, 'time_end' => $month_end, 'data' => 'AND ([status_04] = 104 OR [status_04] = 204) AND [btnID] = 1'))?>
                                                    </a> |
                                                    <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_month_success&btnID=2';?>" class="warning">
                                                        <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $month_start, 'time_end' => $month_end, 'data' => 'AND ([status_04] = 104 OR [status_04] = 204) AND [btnID] = 2'))?>
                                                    </a>
                                                </h1>
                                                <span class="success"><i class="ft-chevron-up"></i> Hoàn thành</span>
                                            </li>
                                            <li class="pl-2">
                                                <h1 class="blue-grey darken-1 text-bold-400">
                                                    <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_month_false&btnID=1';?>" class="primary">
                                                        <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $month_start, 'time_end' => $month_end, 'data' => 'AND ([status_04] <> 104 AND [status_04] <> 204) AND [btnID] = 1'))?>
                                                    </a> |
                                                    <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_month_false&btnID=2';?>" class="warning">
                                                        <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $month_start, 'time_end' => $month_end, 'data' => 'AND ([status_04] <> 104 AND [status_04] <> 204) AND [btnID] = 2'))?>
                                                    </a>
                                                </h1>
                                                <span class="danger darken-2"><i class="ft-chevron-down"></i> Chưa hoàn thành</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Thống kê tổng quan -->
    <!-- Số lượt bấm -->
    <section id="minimal-statistics-bg">
        <div class="row">
            <div class="col-12 mt-3 mb-1"><h4 class="text-uppercase">Số lượt bấm hủy</h4></div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card bg-success">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="ft-activity text-white font-large-2 float-left"></i>
                                </div>
                                <div class="media-body text-white text-right">
                                    <h3 class="text-white">
                                        <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_day&btnID=1&status_04=301';?>" class="primary">
                                            <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => $today, 'data' => 'AND [status_04] = 301 AND [btnID] = 1'));?>
                                        </a> |
                                        <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_day&btnID=2&status_04=301';?>" class="warning">
                                            <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => $today, 'data' => 'AND [status_04] = 301 AND [btnID] = 2'));?>
                                        </a>
                                    </h3>
                                    <span>Hôm nay</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card bg-success">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="ft-activity text-white font-large-2 float-left"></i>
                                </div>
                                <div class="media-body text-white text-right">
                                    <h3 class="text-white">
                                        <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_week&btnID=1&status_04=301';?>" class="primary">
                                            <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $week_start, 'time_end' => $week_end, 'data' => 'AND ([status_04] = 301) AND [btnID] = 1'))?>
                                        </a> |
                                        <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_week&btnID=2&status_04=301';?>" class="warning">
                                            <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $week_start, 'time_end' => $week_end, 'data' => 'AND ([status_04] = 301) AND [btnID] = 2'))?>
                                        </a>
                                    </h3>
                                    <span>Tuần này</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card bg-success">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="ft-activity text-white font-large-2 float-left"></i>
                                </div>
                                <div class="media-body text-white text-right">
                                    <h3 class="text-white">
                                        <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_month&btnID=1&status_04=301';?>" class="primary">
                                            <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $month_start, 'time_end' => $month_end, 'data' => 'AND ([status_04] = 301) AND [btnID] = 1'))?>
                                        </a> |
                                        <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_month&btnID=2&status_04=301';?>" class="warning">
                                            <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $month_start, 'time_end' => $month_end, 'data' => 'AND ([status_04] = 301) AND [btnID] = 2'))?>
                                        </a>
                                    </h3>
                                    <span>Tháng này</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card bg-success">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="ft-activity text-white font-large-2 float-left"></i>
                                </div>
                                <div class="media-body text-white text-right">
                                    <h3 class="text-white">
                                        <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_year&btnID=1&status_04=301';?>" class="primary">
                                            <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $year_start, 'time_end' => $year_end, 'data' => 'AND ([status_04] = 301) AND [btnID] = 1'))?>
                                        </a> |
                                        <a href="<?php echo _URL_ADMIN.'/transactions.php?type=this_year&btnID=2&status_04=301';?>" class="warning">
                                            <?php echo getStaticDevice(array('type' => 'between_plus', 'time_start' => $year_start, 'time_end' => $year_end, 'data' => 'AND ([status_04] = 301) AND [btnID] = 2'))?>
                                        </a>
                                    </h3>
                                    <span>Năm nay</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Số lượt bấm -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header"><h4 class="card-title">Biểu đồ lượt khách hàng bấm trong 7 ngày gần nhất</h4> </div>
                <div class="card-body">
                    <!-- Chart -->
                    <script type="text/javascript">
                        $(window).on("load", function(){
                            Morris.Line({
                                element: 'smooth-line-chart',
                                data: [{
                                    "day"  : "<?php echo date('Y/m/d', strtotime("-6 day", _CONFIG_TIME));?>",
                                    "Thư"   : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => date('Y/m/d', strtotime("-6 day", _CONFIG_TIME)), 'data' => ' AND [btnID] = 1'));?>,
                                    "Hàng"  : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => date('Y/m/d', strtotime("-6 day", _CONFIG_TIME)), 'data' => ' AND [btnID] = 2'));?>
                                }, {
                                    "day"  : "<?php echo date('Y/m/d', strtotime("-5 day", _CONFIG_TIME));?>",
                                    "Thư"   : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => date('Y/m/d', strtotime("-5 day", _CONFIG_TIME)), 'data' => ' AND [btnID] = 1'));?>,
                                    "Hàng"  : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => date('Y/m/d', strtotime("-5 day", _CONFIG_TIME)), 'data' => ' AND [btnID] = 2'));?>
                                }, {
                                    "day"  : "<?php echo date('Y/m/d', strtotime("-4 day", _CONFIG_TIME));?>",
                                    "Thư"   : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => date('Y/m/d', strtotime("-4 day", _CONFIG_TIME)), 'data' => ' AND [btnID] = 1'));?>,
                                    "Hàng"  : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => date('Y/m/d', strtotime("-4 day", _CONFIG_TIME)), 'data' => ' AND [btnID] = 2'));?>
                                }, {
                                    "day"  : "<?php echo date('Y/m/d', strtotime("-3 day", _CONFIG_TIME));?>",
                                    "Thư"   : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => date('Y/m/d', strtotime("-3 day", _CONFIG_TIME)), 'data' => ' AND [btnID] = 1'));?>,
                                    "Hàng"  : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => date('Y/m/d', strtotime("-3 day", _CONFIG_TIME)), 'data' => ' AND [btnID] = 2'));?>
                                }, {
                                    "day"  : "<?php echo date('Y/m/d', strtotime("-2 day", _CONFIG_TIME));?>",
                                    "Thư"   : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => date('Y/m/d', strtotime("-2 day", _CONFIG_TIME)), 'data' => ' AND [btnID] = 1'));?>,
                                    "Hàng"  : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => date('Y/m/d', strtotime("-2 day", _CONFIG_TIME)), 'data' => ' AND [btnID] = 2'));?>
                                }, {
                                    "day"  : "<?php echo date('Y/m/d', strtotime("-1 day", _CONFIG_TIME));?>",
                                    "Thư"   : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => date('Y/m/d', strtotime("-1 day", _CONFIG_TIME)), 'data' => ' AND [btnID] = 1'));?>,
                                    "Hàng"  : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => date('Y/m/d', strtotime("-1 day", _CONFIG_TIME)), 'data' => ' AND [btnID] = 2'));?>
                                }, {
                                    "day"  : "<?php echo $today?>",
                                    "Thư"   : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => $today, 'data' => ' AND [btnID] = 1'));?>,
                                    "Hàng"  : <?php echo getStaticDevice(array('type' => 'click_day_plus', 'time' => $today, 'data' => ' AND [btnID] = 2'));?>
                                }],
                                xkey: 'day',
                                ykeys: ['Thư', 'Hàng'],
                                labels: ['Thư', 'Hàng'],
                                resize: true,
                                smooth: true,
                                pointSize: 2,
                                pointStrokeColors:['#00A5A8','#FF4558'],
                                gridLineColor: '#e3e3e3',
                                behaveLikeLine: true,
                                numLines: 7,
                                parseTime: false,
                                gridtextSize: 14,
                                lineWidth: 2,
                                hideHover: 'auto',
                                lineColors: ['#00A5A8','#FF4558']
                            });
                        });
                    </script>
                    <div id="smooth-line-chart" class="height-400"></div>
                    <!-- Chart -->
                </div>
            </div>
        </div>
    </div>
<?php
require_once 'footer.php';