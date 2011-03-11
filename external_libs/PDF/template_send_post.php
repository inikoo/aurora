<?php
require_once('common.php');
$newarray=array();
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
//$newarray = split($id,",");
$newarray = explode(",", $id);
//echo "*******";print_r($id);

//$sql = "select * from `Customer Dimension` where `Customer Key` = '".$id."'";
$sql = "select * from `Customer Dimension` where `Customer Key` IN (".$id.")";
$result = mysql_query($sql);

while($row = mysql_fetch_array($result))
{

$customer_id=$myconf['customer_id_prefix'].sprintf("%05d",$row['Customer Key']);
if(mysql_num_rows($result) > 0)
{
?>

<center>

<div id="s" style="position:absolute; text-align:center;">
<table style="font-size:30px;" width="100%" height="400" align="center">
<tr height="100"><td>&nbsp;</td></tr>
<tr height="200"><td>
<span style="font-size:31px;font-weight:bold;"><?php echo $row['Customer Company Name']; ?></span><br> 
<span style="font-size:30px;font-weight:bold;"><?php echo $row['Customer Main Contact Name'];?></span><br><?php $addr=strip_tags($row['Customer Main XHTML Address'],'<br/>');echo $addr; ?>
</td></tr>
<tr height="100"><td>&nbsp;</td></tr>
</table>
</div>

</center>
<?php
}
else
{
?>
<span style="color:#F00; font-size:22px;">No Records Found</span>
<?php
}



}
$dt=date("Y-m-d H:i:s");

$sql=sprintf("update `Customers Send Post` set `Send Post Status`='Send' ,`Date Send`=%s where `Customer Key` IN (".$id.")",prepare_mysql($dt));
$query=mysql_query($sql);
?>
