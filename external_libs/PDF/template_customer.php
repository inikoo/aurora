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
<table width="100%" border="0"  bgcolor="#FFFFFF" style="font:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:32px;">
	 <tr>
    <td colspan="3" align="center" style="font-size:48px;font-weight:bold;">CUSTOMER ADDRESS<hr></td>
  </tr>
  <tr height="35%">
    <td align="left" style="font-size:40px;font-weight:bold;"><?php echo $row['Customer Name']; ?> &nbsp;<span style="color:SteelBlue"><?php echo $customer_id;?></span></td>
  </tr>
  <tr height="35%">
    <td align="left" style="font-size:40px;font-weight:bold;"><?php echo $row['Customer Tax Number']; ?><br></td>
  </tr>
  <tr>
    <td width="45%">
<?php echo $row['Customer Main XHTML Address']; ?></td>
    <td width="5%">&nbsp;</td>
    <td width="50%"><table border="0">
                        <tr> 
				<td width="18%">Name</td><td align="right" width="70%"><?php echo $row['Customer Main Contact Name'];?></td><td width="12%"></td>
			</tr>
			<tr>
				<td width="18%">Email</td><td align="right" width="70%"><?php echo $row['Customer Main XHTML Email']; ?></td><td width="12%"></td>
			</tr>
			<tr>
				<td width="18%">Telephone</td><td align="right" width="70%"><?php echo $row['Customer Main XHTML Telephone']; ?></td><td width="12%"></td>
			</tr>
			
		</table>

     </td>
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
