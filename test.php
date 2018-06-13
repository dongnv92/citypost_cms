<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 04/06/2018
 * Time: 10:29
 */

require_once 'includes/core.php';
require_once 'header.php';
?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Thử Code</h4> </div>
            <div class="card-body">
                <?php
                $receives = array('mot', 'hai', 'ba');
                $receives[] = 'bon';
                foreach ($receives AS $receive){
                    echo $receive.'<br />';
                }
                ?>
            </div>
        </div>
    </div>
</div>
</div>
<?php
require_once 'footer.php';
