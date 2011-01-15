<?php
require_once('common.php');
//require_once 'class.Order.php';
//require_once 'class.Invoice.php';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$sql = "select * from `Invoice Dimension` ID left join `Order Transaction Fact` O on (O.`Invoice Key`=ID.`Invoice Key`) where ID.`Invoice Key` = '".$id."'";


$result = mysql_query($sql);
$row = mysql_fetch_array($result);
if(mysql_num_rows($result) > 0)
{
?>

<center>
<table width="100%" border="0"  bgcolor="#FFFFFF" style="font:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:24px;">
	 <tr>
    <td colspan="3" align="center" style="font-size:48px;font-weight:bold;">INVOICE<hr></td>
  </tr>
  <tr height="35%">
    <td width="45%" style="font-size:30px;font-weight:bold;"><?php echo $row['Invoice Customer Name']; ?></td>
<td width="20%"></td>
    <td width="35%" style="font-size:30px;font-weight:bold;"><table><tr><td>Invoice Number</td><td><?php echo $row['Invoice Public ID']; ?></td></tr></table></td>
  </tr>
  <tr>
    <td>
<?php echo $row['Invoice XHTML Address']; ?><br>
Order - <?php echo $row['Invoice Public ID']; ?><br>
Delivery Notes - <?php echo $row['Invoice Public ID']; ?></td>
    <td>&nbsp;</td>
    <td><table border="0">
                        <tr> 
				<td>Invoice Date</td><td align="right"><?php echo strftime("%a %e %b %Y", strtotime($row['Invoice Date'].' UTC'));?></td><td></td>
			</tr>
			<tr>
				<td>Items Gross</td><td align="right"><?php echo money($row['Invoice Items Gross Amount'],$row['Invoice Currency Code']); ?></td><td></td>
			</tr>
			<tr>
				<td>Discounts</td><td align="right"><?php echo money($row['Invoice Items Discount Amount'],$row['Invoice Currency Code']); ?></td>
			</tr>
			<tr>
				<td>Items Net</td><td align="right"><?php echo money($row['Invoice Items Net Amount'],$row['Invoice Currency Code']); ?></td>
			</tr>
			<tr>
				<td>Adjust Net</td><td align="right"><?php echo money($row['Invoice Total Net Adjust Amount'],$row['Invoice Currency Code']); ?></td>
			</tr>
				
		</table>
<?php
                                   $shipping=money($row['Invoice Shipping Net Amount'],$row['Invoice Currency Code']);
                                   $total_net=money($row['Invoice Total Net Amount'],$row['Invoice Currency Code']);
                                   $tax=money($row['Invoice Total Tax Amount'],$row['Invoice Currency Code']);
				   $total_amt=money($row['Invoice Total Amount'],$row['Invoice Currency Code']); ?>
     </td>
  </tr>
  
		</table>
   
</center>
<br />
<br />
<table border="1">
    <tr>
     <td width="12%" align="center"><b>Code</b></td>
     <td width="50%" align="center"><b>Description</b></td> 
      <td width="7%" align="center"><b>Qty</b></td>  
      <td width="10%" align="center"><b>Gross</b></td>
        <td width="8%" align="center"><b>Discounts</b></td>
        <td width="13%" align="center"><b>Charge</b></td>
   </tr>
<?php
 $sql="select * from `Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`) where `Invoice Quantity`!=0 and  `Invoice Key`=".$id." order by `Product Code`";
     //print $sql; die();
    $result=mysql_query($sql);
    $total_gross=0;
    $total_discount=0;
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
 
        $total_discount+=$row['Invoice Transaction Total Discount Amount'];
        $total_gross+=$row['Invoice Transaction Gross Amount'];
        $code=$row['Product Code'];

        if ($row['Invoice Transaction Total Discount Amount']==0)
            $discount='';
        else
            $discount=money($row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code']);
?>
    <tr>
        <td align="center"><?php echo $code; ?></td>
       <td align="center"><?php echo $row['Product XHTML Short Description']; ?></td>
        <td align="center"><?php echo number($row['Invoice Quantity']); ?></td>
         <td align="center"><?php echo money($row['Invoice Transaction Gross Amount'],$row['Invoice Currency Code']); ?></td>
        <td align="center"><?php echo $discount; ?></td>
	<td align="center"><?php echo money($row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code']); ?></td>
    </tr>
</center>
<?php
 }
?>
</table>

<br><br>

<table width="100%" border="0"  bgcolor="#FFFFFF" style="font:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:24px;">
	
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><table border="0">
                       
			<tr>
				<td>Shipping</td><td align="right"><?php echo $shipping; ?></td><td></td>
			</tr>
			<tr>
				<td>Total Net</td><td align="right"><?php echo $total_net; ?></td>
			</tr>
			<tr>
				<td>Tax</td><td align="right"><?php echo $tax;?></td>
			</tr>
	<tr style="font-weight:bold;font-size:26px;"><td>Total</td><td align="right"><?php echo $total_amt;?></td></tr>	
		</table>
     </td>
  </tr>
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
