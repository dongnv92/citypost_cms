<?php
/**
 * Created by PhpStorm.
 * User: Dong
 * Date: 09/08/2018
 * Time: 09:02
 */
$text   = $_GET['text'];
$url    = file_get_contents('http://sandbox.api.simsimi.com/request.p?key=cfbe801b-3564-409d-b708-92ff213cf60f&lc=vi&ft=1.0&text='.$text);
$text   = json_decode($url, true);
echo json_encode(array('messages' => array(array('text' => $text['response']))));
