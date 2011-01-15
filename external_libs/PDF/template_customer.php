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

<table width="100%" border="0"  bgcolor="#FFFFFF" style="font:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:25px;" cellspacing="5" cellpadding="5">
	 <tr>
    <td colspan="3" align="center" style="font-size:48px;font-weight:bold;">CUSTOMER ADDRESS<hr></td>
  </tr>

  <tr>
    <td align="left" style="border: 1px solid #000000;margin:10px;padding:10px;">
<span style="font-size:35px;font-weight:bold;"><?php echo $row['Customer Company Name']; ?></span><br> 
<span style="font-weight:bold;"><?php echo $row['Customer Main Contact Name'];?></span><br><?php echo $row['Customer Main XHTML Address']; ?></td>
    <td width="5%">&nbsp;</td>
    <td width="50%"></td>
  </tr>
</table>
  
</center>
<br />
<br />

<?php
}
else
{
?>
<span style="color:#F00; font-size:22px;">No Records Found</span>
<?php
}
?>
