<?php
$a = array(array('location' => '1', 'stop' => '1'), array('location' => '2', 'stop' => '2'),array('location' => '3', 'stop' => '3'),array('location' => '4', 'stop' => '4'));
$b = array();
foreach ($a AS $waypoint){
    $b[] = '{location : "'. $waypoint['location'] .'", stopover: true}';
}
$bs = implode(',',$b);
echo $bs;
