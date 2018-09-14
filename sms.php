<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 02/07/2018
 * Time: 09:37
 */
require_once 'includes/core.php';
if(!$user_id){
    header('location:'._URL_HOME.'/login.php');
}
$admin_active   = 'input';
        $admin_title = 'Nhập liệu';
        require_once 'header.php';

    $bg[0] = "#F8F8F8";
    $bg[1] = "#E6F0F9";
    $page = '';
    $oRow = '';
    $oType = '0';
    $right = '';
    $tong = '';

    if (isset($_SESSION["ssright"])) {
        $right = $_SESSION["ssright"];
    }

    if (isset($_REQUEST["page"])) {
        $page = $_REQUEST["page"];
    }
    if (isset($_REQUEST["row"])) {
        $oRow = $_REQUEST["row"];
    }

    if (isset($_REQUEST["type"])) {
        $oType = $_REQUEST["type"];
    }else{
        $oType = 2;
    }


    if ($oRow == "") {
        $oRow = 20;
    }

    $whtype = "";
    if ($oType == -1){//chon trang thai
        $whtype = "(status_01_ok=0 or status_01_ok=1 
        or status_02_ok=0 or status_02_ok=1 
        or status_03_ok=0 or status_03_ok=1 
        or status_04_ok=0 or status_04_ok=1 or status_04=0) 
        and status_04!= 301 and status_04!= 106 and status_check=0";
    }
    elseif ($oType == 1) {//giao dich an
        $whtype = "(status_01_ok=0 or status_01_ok=1 
        or status_02_ok=0 or status_02_ok=1 
        or status_03_ok=0 or status_03_ok=1 
        or status_04_ok=0 or status_04_ok=1 or status_04=0) 
        and status_04!= 301 and status_04!= 106 and status_check=0";
    }
    elseif ($oType == 0){//hien thi giao dich
        $whtype = "(status_01_ok=0 or status_01_ok=1 
        or status_02_ok=0 or status_02_ok=1 
        or status_03_ok=0 or status_03_ok=1 
        or status_04_ok=0 or status_04_ok=1 or status_04=0) 
        and status_check=0 or status_check=1";
    }

    //echo "otype: ".$oType.'<br />';

    if ($oRow <> "All") {
        $row_per_page = $oRow;
    } else {
        $row_per_page = 200000;
    }

    $from = $row_per_page * ($page - 1);
    $to = $row_per_page * $page;


    $sql1 = "SELECT count(id) as 'tongso' FROM tblTransactions  where $whtype";
    $result1 = sqlsrv_query($connection, $sql1);
    $rws = sqlsrv_fetch_object($result1);
    $tong = $rws->tongso;
    //$tong=100;
    if ($page > $tong / $row_per_page)
        $page = ceil($tong / $row_per_page);
    if ($page == "")
        $page = 1;

    $from1 = $row_per_page * ($page - 1);
    $to1 = $row_per_page;

    if ($oType == 0) {
        $sql = "SELECT * FROM (SELECT *, ROW_NUMBER() OVER (ORDER BY id) as row FROM tblTransactions Where $whtype) a  WHERE  row > $from1 and row <= $to1  order by id DESC";
    }

    if ($oType <> 0) {
        $sql = "SELECT * FROM (SELECT *, ROW_NUMBER() OVER (ORDER BY id) as row FROM tblTransactions Where $whtype) a  WHERE  row > $from1 and row <= $to1  order by id DESC";
    }

    if ($oRow == "All") {
        $sql = "SELECT * FROM (SELECT *, ROW_NUMBER() OVER (ORDER BY id) as row FROM tblTransactions where $whtype) a WHERE  row > $from1 and row <= $to1  order by 'id' DESC";
        $row_per_page = 200000;
    }
    //echo '$sql: ' . $sql;

    $re = sqlsrv_query($connection, $sql);

?>
<link rel="stylesheet" type="text/css" href="css/style.css">
        <form action="" method="post" class="form form-horizontal" name="frmList">
            <table width="100%"  border="0"  align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100%" rowspan="3">
                        <div align="left">

                            <span class="Tahoma_10_black_B">
                        </div>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" id="Table11">
                            <tbody>

                            <tr>
                                <td width="12" height="30" background="images/CM1_08.gif"><img src="images/spacer.gif" height="10" width="12" /></td>
                                <td height="30" colspan="2" align="center" bgcolor="#E2E2E2"><span class="Tahoma_18_blue_B">Danh Sách Đơn Hàng </span></td>
                                <td valign="top" background="images/CM1_06.gif"><img src="images/spacer.gif" height="10" width="11" /></td>
                            </tr>
                            <tr>
                                <td width="12" height="100%" background="images/CM1_08.gif">&nbsp;</td>
                                <td height="100%" colspan="2" align="center">
                                    <table class="antiintro" cellspacing="0" cellpadding="0" width="100%" border="0">
                                        <form name="frmList" id="frmList">
                                            <tbody>
                                            <tr>
                                                <td><!--Start Table-->
                                                    <table class="smallgrey" width="100%" align="center">
                                                        <tbody>
                                                        <tr>
                                                            <td  align="left" class="Tahoma_12_black" valign="top">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td class="Tahoma_12_black">Tổng số:
                                                                            <b>
                                                                                <?php
                                                                                echo $tong
                                                                                ?> </b>bản ghi/ <b><?php
                                                                                echo ceil($tong / $row_per_page)
                                                                                ?></b> trang</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Trang:
                                                                            <?php
                                                                            $tongtrang = ceil($tong / $row_per_page);
                                                                            if ($page - 2 > 0) {
                                                                                ?>
                                                                                <a href="sms.php?row=<?php
                                                                                echo $oRow
                                                                                ?>&amp;type=<?php
                                                                                echo $oType
                                                                                ?>&amp;page=<?php
                                                                                echo ($page - 1) ? ($page - 1) : 1
                                                                                ?>" class="link_Tahoma_12_blue"><< </a>
                                                                                <?php
                                                                            }
                                                                            ?>

                                                                            <?php
                                                                            for ($i = 1; $i < $tongtrang + 1; $i++) {
                                                                                if ($i <> $page) {
                                                                                    ?>
                                                                                    <a href="sms.php?row=<?php
                                                                                    echo $oRow
                                                                                    ?>&amp;type=<?php
                                                                                    echo $oType
                                                                                    ?>&amp;page=<?php
                                                                                    echo $i
                                                                                    ?>" class="link_Tahoma_12_blue"><?php
                                                                                        if (($i <= $page + 2) && ($i >= $page - 2)) {
                                                                                            echo $i;
                                                                                        }
                                                                                        ?></a>
                                                                                    <?php
                                                                                } else {
                                                                                    echo "<b>[" . $i . "]</b>";
                                                                                }
                                                                            }
                                                                            if ((($tongtrang > $page + 2) && ($tong / $row_per_page <> $tongtrang)) || (($tongtrang > $page + 3) && ($tong % $row_per_page > 0))) {
                                                                                ?>
                                                                                <a href="sms.php?row=<?php
                                                                                echo $oRow
                                                                                ?>&amp;type=<?php
                                                                                echo $oType
                                                                                ?>&amp;page=<?php
                                                                                echo $page + 1
                                                                                ?>" class="link_Tahoma_12_blue">>></a>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                            </span>(<span class="Tahoma_12_black" style="BACKGROUND: none transparent scroll repeat 0% 0%">
                                                                        <select name="row" class="Tahoma_10_black" id="row" onChange="doChangeView('row')">
                                                                            <?php
                                                                            // build selection (months):
                                                                            $arr = array('20', '50', '100', '500', 'All');
                                                                            for ($i = 0; $i < count($arr); $i++) {
                                                                                echo '<option value="' . $arr[$i] . '"';
                                                                                if ($oRow == $arr[$i])
                                                                                    echo ' selected';
                                                                                echo '>' . $arr[$i] . "</option>\n";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </span> dòng/ trang) </td>
                                                                    </tr>
                                                                </table>
                                                                <span class="Tahoma_12_black" style="BACKGROUND: none transparent scroll repeat 0% 0%"></span>
                                                            </td>
                                                            <td align="right" class="Tahoma_12_black" >
                                                                <table width="150" border="0" align="right" cellpadding="0" cellspacing="0">
                                                                    <tr>
                                                                        <td width="75" class="Tahoma_12_black"><div align="center">
                                                                                <a onclick="reload();">
                                                                                    <img src="images/reload.png" alt="Làm mới" width="38" height="38" border="0" align="absmiddle" />
                                                                                </a>
                                                                            </div>
                                                                        </td>
                                                                        <td width="75" class="Tahoma_12_black"><div align="center">
                                                                                    <a href="input.php">
                                                                                        <img src="images/add_button.png" alt="Thêm mới" width="29" height="35" border="0" align="absmiddle" />
                                                                                    </a>
                                                                                </div>
                                                                            </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                width="80%" height="20" align="left" class="Tahoma_12_black"
                                                                style="BACKGROUND: none transparent scroll repeat 0% 0%">&nbsp;</td>
                                                            <td
                                                                width="20%" align="right" class="Tahoma_12_black"
                                                                style="BACKGROUND: none transparent scroll repeat 0% 0%"><span class="Tahoma_12_black" style="BACKGROUND: none transparent scroll repeat 0% 0%">
                                                            <select name="type" id="type" onchange="doChangeView('type')">
                                                                <option value="-1" <?php
                                                                if ($oType == -1) {
                                                                    ?> selected="selected" <?php
                                                                }
                                                                ?>>Chọn trạng thái</option>
                                                                <option value="1" <?php
                                                                if ($oType == 1) {
                                                                    ?> selected="selected" <?php
                                                                }
                                                                ?>>Hiện Giao Dịch Ẩn</option>
                                                                <option value="0" <?php
                                                                if ($oType == 0) {
                                                                    ?> selected="selected" <?php
                                                                }
                                                                ?>>Tất Cả Giao Dịch</option>
                                                            </select>
                                                        </span>
                                                                <div align="right"></div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <fieldset>
                                                        <table cellspacing="1" cellpadding="2" width="100%" border="0">
                                                            <tbody>
                                                            <tr>
                                                                <td class="Tahoma_12_black_B" width="140" bgcolor="#d6d6d6"><div align="center">Mã giao dịch</div></td>
                                                                <td class="Tahoma_12_black_B" width="140" bgcolor="#d6d6d6"><div align="center">Mã thiết bị</div></td>
                                                                <td class="Tahoma_12_black_B" width="140" bgcolor="#d6d6d6"><div align="center">Khách hàng</div></td>
                                                                <td class="Tahoma_12_black_B" width="140" bgcolor="#d6d6d6"><div align="center">Loại đơn hàng</div></td>
                                                                <td class="Tahoma_12_black_B" width="140" bgcolor="#d6d6d6"><div align="center">Thời gian gọi</div></td>
                                                                <td width="75" bgcolor="#d6d6d6" class="Tahoma_12_black_B"><div align="center">STT_102</div></td>
                                                                <td width="75" bgcolor="#d6d6d6" class="Tahoma_12_black_B"><div align="center">Send_102</div></td>
                                                                <td width="75" bgcolor="#d6d6d6" class="Tahoma_12_black_B"><div align="center">STT_103</div></td>
                                                                <td width="75" bgcolor="#d6d6d6" class="Tahoma_12_black_B"><div align="center">Send_103</div></td>
                                                                <td width="75" bgcolor="#d6d6d6" class="Tahoma_12_black_B"><div align="center">STT_104</div></td>
                                                                <td width="75" bgcolor="#d6d6d6" class="Tahoma_12_black_B"><div align="center">Send_104</div></td>
                                                                <td class="Tahoma_12_black_B" width="175" bgcolor="#d6d6d6"><div align="center">Cú pháp SMS</div></td>

                                                            </tr>

                                                            <?php
                                                            $i = 0;
                                                            $transID ="";
                                                            while ($rw = sqlsrv_fetch_object($re)) {
                                                            $i++;
                                                            $id = $rw->id;
                                                            $transID = $rw->transID;
                                                            $deviceID = $rw->deviceID;
                                                            $cusID = $rw->cusID;
                                                            $prodID = $rw->prodID;
                                                            $typeID = $rw->btnID;
                                                            $timeReq = $rw->timeReq;
                                                            $status_01 = $rw->status_01;
                                                            $status_01_ok = $rw->status_01_ok;
                                                            $status_02 = $rw->status_02;
                                                            $status_02_ok = $rw->status_02_ok;

                                                            $status_03 = $rw->status_03;
                                                            $status_03_ok = $rw->status_03_ok;
                                                            $status_04 = $rw->status_04;
                                                            $status_04_ok = $rw->status_04_ok;
                                                            $status = $rw->status;

                                                            ?>

                                                            <tr bgcolor="<?php
                                                            echo $bg[$i % 2]
                                                            ?>">
                                                                <td align="center" class="Tahoma_12_black"><?php
                                                                    echo $transID
                                                                    ?> </td>
                                                                <td align="center" class="Tahoma_12_black"><?php
                                                                    echo $deviceID
                                                                    ?> </td>
                                                                <td align="center" class="Tahoma_12_black"><?php
                                                                    echo GetDataByID("fullname","SELECT fullname FROM tblCustomer WHERE cusID=$cusID");
                                                                    ?> </td>
                                                                <td align="center" class="Tahoma_12_black"><?php
                                                                    if ($typeID == 1) {
                                                                        echo "Thư - Xe máy";
                                                                    } elseif ($typeID == 2) {
                                                                        echo "Hàng hóa - Ô tô";
                                                                    }
                                                                    ?> </td>
                                                                <td align="center" class="Tahoma_12_black"><?php
                                                                    echo  date_format($timeReq,"d/m/Y H:i:s");
                                                                    ?>
                                                                </td>

                                                                <td align="center" class="Tahoma_12_blue" nowrap="nowrap" align="center">
                                                                    <?php echo $status_02; ?>
                                                                </td>
                                                                <td align="center" class="Tahoma_12_blue">
                                                                    <?php  if($status_02_ok == 1){ ?>
                                                                        <input type="checkbox"  id="<?php echo 'ok_02_'.$id;?>" checked="checked" value="<?php echo $status_02_ok;?>" onclick="active('tblTransactions','status_02_ok',<?php echo $status_02_ok;?>,'transID','<?php echo $transID;?>','02_'+<?php echo $id;?>)">
                                                                    <?php }
                                                                    elseif($status_02_ok ==0){ ?>
                                                                        <input type="checkbox"  id="<?php echo 'ok_02_'.$id;?>" value="<?php echo $status_02_ok;?>"  onclick="active('tblTransactions','status_02_ok',<?php echo $status_02_ok;?>,'transID','<?php echo $transID;?>','02_'+<?php echo $id;?>)">
                                                                        <?php } ?>
                                                                </td>

                                                                <td align="center" class="Tahoma_12_blue" nowrap="nowrap" align="center">
                                                                    <?php echo $status_03; ?>
                                                                </td>
                                                                <td align="center" class="Tahoma_12_blue">
                                                                    <?php  if($status_03_ok == 1){ ?>
                                                                        <input type="checkbox" id="<?php echo 'ok_03_'.$id;?>" checked="checked" onclick="active('tblTransactions','status_03_ok',<?php echo $status_03_ok;?>,'transID','<?php echo $transID;?>','03_'+<?php echo $id;?>)">
                                                                    <?php } elseif ($status_03_ok == 0){ ?>
                                                                        <input type="checkbox" id="<?php echo 'ok_03_'.$id;?>"  onclick="active('tblTransactions','status_03_ok',<?php echo $status_03_ok;?>,'transID','<?php echo $transID;?>','03_'+<?php echo $id;?>)">
                                                                    <?php } ?>
                                                                </td>

                                                                <td align="center" class="Tahoma_12_blue" nowrap="nowrap" align="center">
                                                                    <?php echo $status_04; ?>
                                                                </td>
                                                                <td align="center" class="Tahoma_12_blue">
                                                                    <?php  if($status_04_ok == 1){ ?>
                                                                        <input type="checkbox" id="<?php echo 'ok_04_'.$id;?>" checked="checked" onclick="active('tblTransactions','status_04_ok',<?php echo $status_04_ok;?>,'transID','<?php echo $transID;?>','04_'+<?php echo $id;?>)">
                                                                    <?php } elseif ($status_04_ok == 0){ ?>
                                                                        <input type="checkbox" id="<?php echo 'ok_04_'.$id;?>"  onclick="active('tblTransactions','status_04_ok',<?php echo $status_04_ok;?>,'transID','<?php echo $transID;?>','04_'+<?php echo $id;?>)">
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="Tahoma_12_blue">
                                                                    <div align="center">
                                                                        <?php
                                                                            //check trang thai status de lay cu phap sms:
                                                                        if($status_01 > 0 && $status_02 == 0 && $status_03 ==0 && $status_04 ==0){
                                                                            if($typeID == 1){
                                                                                echo "buttonid=$typeID status=$status_01.";
                                                                            }
                                                                            elseif ($typeID == 2){
                                                                                echo "buttonid=$typeID status=$status_01.";
                                                                            }
                                                                        }elseif($status_01 > 0 && $status_02 > 0 && $status_03 ==0 && $status_04 ==0){
                                                                                if($typeID == 1){
                                                                                    echo "buttonid=$typeID status=$status_02.";
                                                                                }
                                                                                elseif ($typeID == 2){
                                                                                    echo "buttonid=$typeID status=$status_02.";
                                                                                }
                                                                            }elseif ($status_01 > 0 && $status_02 > 0 && $status_03 >0 && $status_04 ==0){
                                                                                if($typeID == 1){
                                                                                    echo "buttonid=$typeID status=$status_03.";
                                                                                }
                                                                                elseif ($typeID == 2){
                                                                                    echo "buttonid=$typeID status=$status_03.";
                                                                                }
                                                                            }elseif ($status_01 > 0 && $status_02 > 0 && $status_03 >0 && $status_04 > 0){
                                                                                if($typeID == 1){
                                                                                    echo "buttonid=$typeID status=$status_04.";
                                                                                }
                                                                                elseif ($typeID == 2){
                                                                                    echo "buttonid=$typeID status=$status_04.";
                                                                                }
                                                                            }


                                                                        ?>
                                                                    </div>
                                                                </td>

                                                                <?php }?>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </fieldset>
                                                    <!--End Table-->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="21" id="ai2"><div align="right"><span class="Tahoma_12_black" style="BACKGROUND: none transparent scroll repeat 0% 0%">Trang:
                                                            <?php
                                                            for ($i = 1; $i < ceil($tong / $row_per_page) + 1; $i++) {
                                                                if ($i <> $page) {
                                                                    ?>
                                                                    <a href="sms.php?row=<?php
                                                                    echo $oRow
                                                                    ?>&type=<?php
                                                                    echo $oType
                                                                    ?>&page=<?php
                                                                    echo $i
                                                                    ?>" class="link_Tahoma_12_blue"><?php
                                                                        echo $i
                                                                        ?></a>&nbsp;
                                                                    <?php
                                                                } else {
                                                                    echo "<b>[" . $i . "]</b>";
                                                                }
                                                            }
                                                            ?>

                                                            &nbsp; </span></div></td>
                                            </tr>
                                            </tbody>
                                        </form>
                                    </table>
                                </td>
                                <td valign="top" background="images/CM1_06.gif">&nbsp;</td>
                            </tr>


                            </tbody>
                        </table>
                    </td>

                </tr>
            </table>
<?php
    require_once 'footer.php';
?>

            <script language="JavaScript">
                function docheck(status,from_)
                {
                    var alen=document.frmList.checkid.length;
                    if (alen>1)
                    {
                        for(var i=0;i<alen;i++)
                            document.frmList.checkid[i].checked=status;
                    }
                    else
                        document.frmList.checkid.checked=status;
                    if(from_>0)
                        document.frmList.chkall.checked=status;

                    calculatechon();
                }
                function docheckone()
                {
                    var alen=document.frmList.checkid.length;
                    var isChecked=true;
                    if (alen>1)
                    {
                        for(var i=0;i<alen;i++)
                            if(document.frmList.checkid[i].checked==false)
                                isChecked=false;
                    }else
                    {
                        if(document.frmList.checkid.checked==false)
                            isChecked=false;
                    }
                    document.frmList.chkall.checked=isChecked;
                }
                function calculatechon()
                {
                    var strchon="";
                    var isChecked=true;
                    var alen=document.frmList.checkid.length;
                    if (alen>1)
                    {
                        for(var i=0;i<alen;i++)
                            if(document.frmList.checkid[i].checked==true)
                                strchon+=document.frmList.checkid[i].value+",";
                            else
                                isChecked=false;
                    }else
                    {
                        if(document.frmList.checkid.checked==true)
                            strchon=document.frmList.checkid.value;
                        else
                            isChecked=false;
                    }
                    document.frmList.chon.value=strchon;
                    document.frmList.chkall.checked=isChecked;

                }
                function checkInput(){
                    var alen=document.frmList.checkid.length;
                    var isChecked=false;
                    if (alen>1)
                    {
                        for(var i=0;i<alen;i++)
                            if(document.frmList.checkid[i].checked==true)
                                isChecked=true;
                    }else
                    {
                        if(document.frmList.checkid.checked==true)
                            isChecked=true;
                    }
                    calculatechon();

                    if (!isChecked)
                        alert("Bạn hãy chọn mục cần xóa");
                    else

                        return isChecked;
                }
                function check(){
                    if (checkInput()){
                        var conf=confirm("Bạn có chắc chắn muốn xóa các mục đã chọn không ?");
                        if (conf){
                            var strchon=document.frmList.chon.value;

                            document.location.href='member_del.php?re=member.php&tbl=tblusers&fldid=id&val='+strchon;
                            return false;
                        }
                    }
                }

                function doChangeView(obj){
                    if(obj=='row'){
                        window.location.href='sms.php?row=' + document.frmList.row.value +
                            '&page=<?php
                                echo $page
                                ?>&type=<?php
                                echo $oType
                                ?>';
                    }
                    if(obj=='type'){
                        window.location.href='sms.php?row=<?php echo $oRow ?>&page=<?php
                            echo $page
                            ?>&type=' + document.frmList.type.value;
                    }


                }

                function reload() {
                    window.location.reload(true);
                }


                function active(table,field,val,field_id,uid,ckname){
                    var url = "";
                    var ok = document.getElementById('ok_'+ckname);
                        if(ok.checked == true){
                            if(field == "status_04_ok"){
                                url = "_ajax_active.php?tbl="+ table + "&fld="+field +"&val=1&fldid="+ field_id + "&uid=" + uid+"&stt_check=status_check&stt_val=1";
                            }else if(field != "status_04_ok"){
                                url = "_ajax_active.php?tbl="+ table + "&fld="+field +"&val=1&fldid="+ field_id + "&uid=" + uid;
                            }
                            postRequest(url);
                            swal("Đã gửi SMS!", "", "success")
                                .then((value) => {
                                    reload();
                                });
                        }
                        if(ok.checked == false){
                            if(field == "status_04_ok"){
                                url = "_ajax_active.php?tbl="+ table + "&fld="+field +"&val=0&fldid="+ field_id + "&uid=" + uid+"&stt_check=status_check&stt_val=0";
                            }else if(field != "status_04_ok"){
                                url = "_ajax_active.php?tbl="+ table + "&fld="+field +"&val=0&fldid="+ field_id + "&uid=" + uid;
                            }
                            postRequest(url);
                            swal("Chưa Gửi SMS!", "", "success")
                                .then((value) => {
                                    reload();
                                });
                        }
                }

                function GetAjax(str){
                }

                //Thực thi chương trình
                function postRequest(strURL,status)
                {
                    var xmlHttp;

                    if (window.XMLHttpRequest) { // Mozilla, Safari, ...

                        var xmlHttp = new XMLHttpRequest();

                    } else if (window.ActiveXObject) { // IE

                        var xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");

                    }

                    xmlHttp.open('POST', strURL, true);

                    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    xmlHttp.onreadystatechange = function() {

                        if (xmlHttp.readyState == 4)
                        {
                            GetAjax(xmlHttp.responseText);
                        }

                    }

                    xmlHttp.send(strURL);

                }
            </script>

