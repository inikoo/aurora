<?php
require_once('common.php');

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$sql = "select * from `Customer Dimension` where `Customer Key` = '".$id."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

$customer_id=$myconf['customer_id_prefix'].sprintf("%05d",$row['Customer Key']);
if(mysql_num_rows($result) > 0)
{
?>

<center>

<table style="font-size:30px;" width="100%" align="center"><tr><td>
<span style="font-size:31px;font-weight:bold;"><?php echo $row['Customer Company Name']; ?></span><br> 
<span style="font-size:30px;font-weight:bold;"><?php echo $row['Customer Main Contact Name'];?></span><br><?php $addr=strip_tags($row['Customer Main XHTML Address'],'<br/>');echo $addr; ?>
</td></tr></table>

<?php
}
else
{
?>
<span style="color:#F00; font-size:22px;">No Records Found</span>
<?php
}
?>
