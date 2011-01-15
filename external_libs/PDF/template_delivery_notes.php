<?php
require_once('common.php');
//require_once 'class.Order.php';
//require_once 'class.Invoice.php';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$sql = "select * from `Delivery Note Dimension` ID left join `Order Transaction Fact` O on (O.`Delivery Note Key`=ID.`Delivery Note Key`) where ID.`Delivery Note Key` = '".$id."'";
$weight_sql=mysql_fetch_array(mysql_query("select sum(`Estimated Dispatched Weight`) as weight from `Order Transaction Fact` where `Order Quantity`!=0 and `Delivery Note Key`='".$id."'"));
$weight=$weight_sql[0];                   

$result = mysql_query($sql);
$row = mysql_fetch_array($result);
if(mysql_num_rows($result) > 0)
{
?>

<center>
<table width="100%" border="0"  bgcolor="#FFFFFF" style="font:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:24px;">
	 <tr>
    <td colspan="3" align="center" style="font-size:48px;font-weight:bold;">DELIVERY NOTE<hr></td>
  </tr>
  <tr height="35%">
    <td width="45%" style="font-size:30px;font-weight:bold;"><?php echo $row['Delivery Note Customer Name']; ?> </td>
<td width="20%"></td>
    <td width="35%" style="font-size:30px;font-weight:bold;"><table><tr><td>Delivery Note Number</td><td><?php echo $row['Delivery Note ID']; ?></td></tr></table></td>
  </tr>
  <tr>
    <td>
<?php echo $row['Delivery Note XHTML Ship To']; ?><br>
Order - <?php echo $row['Delivery Note XHTML Orders']; ?><br>
Invoice - <?php echo $row['Delivery Note XHTML Invoices']; ?></td>
    <td>&nbsp;</td>
    <td><table border="0">
                        <tr> 
				<td>Creation Date</td><td align="right"><?php echo strftime("%a  %e, %b, %Y", strtotime($row['Delivery Note Date Created'].' UTC'));?></td><td></td>
			</tr>
			<tr>
				<td>Weight</td><td align="right"><?php echo round($weight,1); ?> Kg</td><td></td>
			</tr>
			<tr>
				<td>Parcels</td><td align="right"><?php echo $row['Delivery Note Number Parcels']; ?></td>
			</tr>
			<tr>
				<td>Picker</td><td align="right"><?php echo $row['Delivery Note Assigned Picker Alias']; ?></td>
			</tr>
			<tr>
				<td>Packer</td><td align="right"><?php echo $row['Delivery Note Assigned Packer Alias']; ?></td>
			</tr>
				
		</table>

     </td>
  </tr>
  
		</table>
   
</center>
<br />
<br />
<table border="1">
    <tr>
    
     <td width="20%" align="center"><b>Code</b></td> 
      <td width="70%" align="center"><b>Dscription</b></td>  
      <td width="10%" align="center"><b>Qty</b></td>
       
   </tr>
<?php
 $sql="select `Delivery Note Quantity`,`Product Tariff Code`,`Product Code`, PH.`Product ID` ,`Product XHTML Short Description` from `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) where `Delivery Note Key`='".$id."'";
     //print $sql; die();
    $result=mysql_query($sql);
    $total_gross=0;
    $total_discount=0;
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
 
     
?>
    <tr>
        
       <td align="center"><?php echo $row['Product Code']; ?></td>
        <td align="center"><?php echo $row['Product XHTML Short Description']; ?></td>
        <td align="center"><?php echo $row['Delivery Note Quantity']; ?></td>
    </tr>
</center>
<?php
 }
?>
</table>


<?php
}
else
{
?>
<span style="color:#F00; font-size:22px;">No Records Found</span>
<?php
}
?>
