<?php
require_once('common.php');
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$sql = "select * from `Invoice Dimension` where `Invoice Key` = '".$id."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
if(mysql_num_rows($result) > 0)
{
?>
<center>
<table width="362" border="0"  bgcolor="#FFFFFF" style="font:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:24px;">
	 <tr>
    <td colspan="2" align="center">INVOICE DETAILS <br><br></td>
  </tr>
  <tr>
    <td width="196">Invoice Number - <?php echo $row['Invoice Public ID']; ?> </td>
    <td width="156">Invoice Date - <?php echo $row['Invoice Date']; ?></td>
  </tr>
  <tr>
    <td><?php echo $row['Invoice XHTML Address']; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Order - <?php echo $row['Invoice Public ID']; ?></td>
    <td rowspan=2>
    	<table border="0">
			<tr>
				<td>Items Gross</td><td><?php echo $row['Invoice Items Gross Amount']; ?></td>
			</tr>
			<tr>
				<td>Discounts</td><td><?php echo $row['Invoice Items Discount Amount']; ?></td>
			</tr>
			<tr>
				<td>Items Net</td><td><?php echo $row['Invoice Items Net Amount']; ?></td>
			</tr>
			<tr>
				<td>Adjust Net</td><td><?php echo $row['Invoice Total Net Adjust Amount']; ?></td>
			</tr>
			<tr>
				<td>Shipping</td><td><?php echo $row['Invoice Shipping Net Amount']; ?></td>
			</tr>
			<tr>
				<td>Total Net</td><td><?php echo $row['Invoice Total Net Amount']; ?></td>
			</tr>
			<tr>
				<td>Tax</td><td><?php echo $row['Invoice Total Tax Amount'];?></td>
			</tr>
			
		</table>
    </td>
  </tr>
  <tr>
    <td valign="top">Delivery Notes - <?php echo $row['Invoice Public ID']; ?></td>
  </tr>
  <tr>
	<td></td><td>Total = <span style="font-weight:bold; color:#030;"><?php echo $row['Invoice Total Amount'];?></span></td>
 </tr>
</table>
</center>
<?php
}
else
{
?>
<span style="color:#F00; font-size:22px;">No Records Found</span>
<?php
}
?>
