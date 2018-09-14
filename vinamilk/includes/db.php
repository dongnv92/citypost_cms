<?php
/**
 * Created by PhpStorm.
 * User: nguye
 * Date: 19/06/2018
 * Time: 21:28
 */

// Define Config Database
define('_DB_SERVER', 'localhost:13306');
define('_DB_USER', 'root');
define('_DB_PASS', 'citypost@2018@*#');
define('_DB_NAME', 'dong_iotbutton');

$db_connect = mysqli_connect(_DB_SERVER, _DB_USER, _DB_PASS, _DB_NAME) or die('Cant Connect To Database');
mysqli_query($db_connect,"SET NAMES 'utf8'");

// Define URL
define('_URL_HOME', 'http://112.78.11.14/vinamilk');
