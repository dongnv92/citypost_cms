<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 09/07/2018
 * Time: 10:44
 */
require_once 'includes/core.php';
if(!$user_id){
    header('location:'._URL_HOME.'/login.php');
}
$admin_active   = 'buuta';

switch ($act){
    default:
        $admin_title = 'Danh sách bưu tá';
        require_once 'header.php';
        // Paginave
        $para = array('postManName');
        foreach ($para AS $paras){
            if(isset($_REQUEST[$paras]) && !empty($_REQUEST[$paras])){
                $parameters[$paras] = $_REQUEST[$paras];
            }
        }
        if($parameters){
            foreach ($parameters as $key => $value) {
                if($key == 'postManName'){
                    $colums[] = '['.$key .'] LIKE '. "N'%". $value ."%'";
                }else{
                    $colums[] = '['.$key .'] = '. "N'". $value ."'";
                }
            }
            $parameters_list = ' WHERE '.implode(' AND ', $colums);
        }
        // Tạo Url Parameter động
        foreach ($parameters as $key => $value) {
            $para_url[] = $key .'='. $value;
        }
        $para_list                      = implode('&', $para_url);

        $query_pagenum                  = 'SELECT [id] FROM '._DB_TABLE_POSTMAN.' '.$parameters_list;
        $query_pagenum                  = sqlsrv_query($connection, $query_pagenum, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        $page_num                       = sqlsrv_num_rows($query_pagenum);
        $config_pagenavi['page_row']    = _CONFIG_PAGINATION;
        $config_pagenavi['page_num']    = ceil($page_num/$config_pagenavi['page_row']);
        $config_pagenavi['url']         = _URL_ADMIN.'/buuta.php?'.$para_list.'&';
        $page_start                     = ($page-1) * $config_pagenavi['page_row'];
        $page_start                     = $page_start == 0 ? 1 : $page_start+1;
        $query                          = 'SELECT * FROM (SELECT ROW_NUMBER() OVER(ORDER BY postManID DESC) AS RowNumber,id,deparmentID,deparmentName,email,postManID,postManName,POName,POPhone,POID,timeReq,status FROM '. _DB_TABLE_POSTMAN .' '. $parameters_list .') AS Temp WHERE RowNumber BETWEEN '. $page_start .' AND '.($page_start + ($config_pagenavi['page_row'] - 1));
        $data                           = getGlobalAll(_DB_TABLE_POSTMAN, '', array('query' => $query));
        // Paginave
        ?>
        <form action="" method="get">
            <div class="row">
                <div class="col text-lg-left">
                    <div class="search-input open">
                        <input class="input form-control round" type="text" value="<?php echo $_REQUEST['postManName'] ? $_REQUEST['postManName'] : '';?>" name="postManName" placeholder="Tên bưu tá">
                    </div>
                </div>
                <div class="col  text-lg-right">
                    <input type="submit" value="Tìm kiếm" class="btn btn-outline-primary round btn-min-width mr-1 mb-1">
                </div>
            </div>
        </form>
        <hr class="danger" />
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-content">
                        <?php echo '<div class="text-right"><nav aria-label="Page navigation">'.pagination($config_pagenavi).'</nav></div>';?>
                        <!-- Pagination -->
                        <?php
                        echo '<div class="table-responsive">';
                        echo '<table class="table">';
                        echo '<thread>';
                        echo '<tr>';
                        echo '<th width="40%">Tên bưu tá</th>';
                        echo '<th width="30%">Bưu cục</th>';
                        echo '<th width="30%">Số bưu tá</th>';
                        echo '</tr>';
                        echo '</thread>';
                        echo '<tbody>';
                        foreach ($data AS $buuta){
                            echo '<tr>';
                            echo '<td width="40%">'. $buuta['postManName'] .'</td>';
                            echo '<td width="30%">'. $buuta['POName'] .'</td>';
                            echo '<td width="30%">'. $buuta['POPhone'] .'</td>';
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
        <?php
        break;
}
require_once 'footer.php';