<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once ("includes/core.php");
//_ajax_active.php?tbl=tblTransactions&fld=status_ok&val=0&fldid=transID&uid=trans-1530809880
global  $connection;
$sql = "";
$stt_check = "";
$stt_val = "";

$tbl = $_REQUEST["tbl"];
$fld = $_REQUEST["fld"];
$val = $_REQUEST["val"];
$fldid = $_REQUEST["fldid"];
$uid = $_REQUEST["uid"];

if(isset($_REQUEST["stt_check"]) && isset($_REQUEST["stt_val"])) {
    $stt_check = $_REQUEST["stt_check"];
    $stt_val = $_REQUEST["stt_val"];
    $sql = "UPDATE $tbl SET $fld=$val,$stt_check=$stt_val WHERE $fldid='$uid'";
}else{
    $sql = "UPDATE $tbl SET $fld=$val WHERE $fldid='$uid'";
}
//echo $sql;
$re = sqlsrv_query($connection,$sql);
if($re > 0){
    //redirect("member_edit.php?uid=" . $uid, "Cập nhật dữ liệu thất bại.!", "1");
}else{

}
?>