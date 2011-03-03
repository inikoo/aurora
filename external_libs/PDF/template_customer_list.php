<?php
require_once('common.php');

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
//$sql = "select * from `Customer Dimension` where `Customer Key` = '".$id."'";
$sql="select C.`Customer Key`,C.`Customer Store Key`,C.`Customer Main XHTML Email`,C.`Customer Main Location`,C.`Customer Name`,C.`Customer Type`,C.`Customer Main XHTML Telephone`,C.`Customer Main Contact Name`,C.`Customer Main XHTML Address` from `Customer Dimension` C right join `Customer List Customer Bridge` CLCB on (C.`Customer Key`=CLCB.`Customer Key`) left join `Customer List Dimension` CLD on (CLD.`Customer List Key`=CLCB.`Customer List Key`) where CLD.`Customer List Key`=$id order by CLD.`Customer List Key`";
$result = mysql_query($sql);
$sql_static_name=mysql_fetch_array(mysql_query("select `Customer List Name` from `Customer List Dimension` where `Customer List Key`=$id"));
$static_list_name=$sql_static_name[0];


if(mysql_num_rows($result) > 1000)
{
echo "<span style='color:#F00; font-size:22px;'>More than 1000 records so can not print in PDF format</span>";
}
else
{
?>
<center>
<table width="100%" border="0"  bgcolor="#FFFFFF" style="font:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:20px;">
	 <tr>
    <td colspan="3" align="center" style="font-size:34px;font-weight:bold;">Customer List : <?php echo $static_list_name;?><hr></td>
  </tr>
<tr height="15px">
    <td width="48%" align="right" style="font-weight:bold;">Customer Name</td>
<td width="2%"></td>
    <td width="50%" style="font-weight:bold;">Postal Address</td>
</tr>
<?php
while($row = mysql_fetch_array($result)){
?>

<tr>
    <td width="48%" align="right"><?php echo $row['Customer Name']; ?></td>
<td width="2%"></td>
    <td width="50%"><?php $addr=strip_tags($row['Customer Main XHTML Address'],'<br/>');echo $addr."<br>"; ?></td>
</tr>
<?php
}
?>
</table></center>
<?php 
}
?>
