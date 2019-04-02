<?php
//require_once 'includes/core.php';
$key_start      = 'DONG';
$key_end        = 'CHINH';
echo md5(md5($key_start.time().$key_end));