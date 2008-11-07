<?
$link = mysql_connect('localhost', 'root', 'ajolote11')
  or die('Could not connect: ' . mysql_error());
mysql_select_db('aw') or die('Could not select database');

$res='';
 $sql="select * from liveuser_rights;";
 $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
 while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $res.="DEFINE('".$row['right_define_name']."',".$row['right_id'].");\n";
  }
$sql="select * from liveuser_groups;";
 $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
 while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $res.="\$_group['".$row['group_id']."']=_('".$row['name']."');\n";
  }
$sql="select id,name from country;";
$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
   $res.="\$_country['".$row['id']."']=_('".$row['name']."');\n";
 }
$sql="select id,name from lang;";
$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
   $res.="\$_lang['".$row['id']."']=_('".$row['name']."');\n";
 }
// Units tipo

//$res.="\$_units_tipo['0']='';\n";
//$res.="\$_units_tipop['0']='';\n";
//$res.="\$_units_tipo['1']=_('item');\n";
//$res.="\$_units_tipop['1']=_('items');\n";

$res.="\$_prefix['0']=_('None');\n";
$res.="\$_prefix['1']=_('Mr');\n";
$res.="\$_prefix['2']=_('Mrs');\n";
$res.="\$_prefix['3']=_('Miss');\n";

$res.="\$_tipo_tel['0']=_('Telephone');\n";
$res.="\$_tipo_tel['1']=_('Work Telephone');\n";
$res.="\$_tipo_tel['2']=_('Mobile');\n";
$res.="\$_tipo_tel['3']=_('Home Phone');\n";
$res.="\$_tipo_tel['4']=_('Fax');\n";

$res.="\$_tipo_contact['0']=_('Company');\n";
$res.="\$_tipo_contact['1']=_('Sir');\n";
$res.="\$_tipo_contact['2']=_('Madam');\n";


$res.="\$_tipo_customer['0']=_('Company');\n";
$res.="\$_tipo_customer['1']=_('Personal Customer');\n";
$res.="\$_tipo_customer['2']=_('Personal Customer');\n";



$res.="\$_tipo_address['0']=_('Home Address');\n";
$res.="\$_tipo_address['1']=_('Work Address');\n";
$res.="\$_tipo_address['2']=_('Deliver Address');\n";


$res.="\$_tipo_email['0']=_('Work Email');\n";
$res.="\$_tipo_email['1']=_('Personal Email');\n";
$res.="\$_tipo_email['2']=_('Company Email');\n";

$res.="\$_units_tipo['1']=_('item');\n";
$res.="\$_units_tipo['2']=_('gram');\n";
$res.="\$_units_tipo['3']=_('miliLiter');\n";
$res.="\$_units_tipo['4']=_('Kilogram');\n";
$res.="\$_units_tipo['5']=_('Liter');\n";

$res.="\$_units_tipo_abr['1']='';\n";
$res.="\$_units_tipo_abr['2']=_('g');\n";
$res.="\$_units_tipo_abr['3']=_('mL');\n";
$res.="\$_units_tipo_abr['4']=_('Kg');\n";
$res.="\$_units_tipo_abr['5']=_('L');\n"; 


$res.="\$_pm_tipo['0']=_('Unknown');\n";
$res.="\$_pm_tipo['1']=_('Cash');\n";
$res.="\$_pm_tipo['2']=_('Credit Card');\n";
$res.="\$_pm_tipo['3']=_('Bank Transfer');\n";
$res.="\$_pm_tipo['4']=_('Cheque');\n";
$res.="\$_pm_tipo['5']=_('Account');\n";
$res.="\$_pm_tipo['6']=_('Pay Pal');\n";
$res.="\$_pm_tipo['7']=_('Bank Draft');\n";
$res.="\$_pm_tipo['8']=_('Postal Order');\n";
$res.="\$_pm_tipo['9']=_('Money Bookers');\n";



$res.="\$_shape['0']='Choose one';\n";
$res.="\$_shape['1']='Box';\n";
$res.="\$_shape['2']='Sphere';\n";
$res.="\$_shape['3']='Cilinder';\n";
$res.="\$_shape['4']='Stick';\n";
$res.="\$_shape['5']='Sheet';\n";
$res.="\$_shape_example['0']='';\n";
$res.="\$_shape_example['1']='width x deep x height (in cm)';\n";
$res.="\$_shape_example['2']='diameter (in cm)';\n";
$res.="\$_shape_example['3']='diameter x height (in cm)';\n";
$res.="\$_shape_example['4']='lenght (in cm)';\n";
$res.="\$_shape_example['5']='width x height (in cm)';\n";


//$_tipo_order=array('Unknown','Pro-invoice','Invoice','Canceled','Sample','Donation,''Replacements','Shortages','To Follow','Refund','Credit Note');

$res.="\$_order_tipo['0']=_('Unknown');\n";
$res.="\$_order_tipo['1']=_('Proforma Invoice');\n";
$res.="\$_order_tipo['2']=_('Invoice');\n";
$res.="\$_order_tipo['3']=_('Order Canceled');\n";
$res.="\$_order_tipo['4']=_('Samples');\n";
$res.="\$_order_tipo['5']=_('Donation');\n";
$res.="\$_order_tipo['6']=_('Replacement');\n";
$res.="\$_order_tipo['7']=_('Shortage');\n";
$res.="\$_order_tipo['8']=_('To Follow Order');\n";
$res.="\$_order_tipo['9']=_('Refund');\n";
$res.="\$_order_tipo['10']=_('Credit Note');\n";

//$res.="\$_order_tipo['10']=_('Unknown').' 4';\n";
//$res.="\$_order_tipo['11']=_('Unknown').' 5';\n";
//$res.="\$_order_tipo['12']=_('Unknown').' 6';\n";
//$res.="\$_order_tipo['13']=_('Unknown').' 7';\n";
//$res.="\$_order_tipo['14']=_('Unknown').' 8';\n";

$res.="\$_porder_tipo['0']=_('To do');\n";
$res.="\$_porder_tipo['1']=_('Submited');\n";
$res.="\$_porder_tipo['2']=_('Received');\n";
$res.="\$_porder_tipo['3']=_('Cancelled');\n";



$res.="\$_product_tipo['0']=_('Active');\n";
$res.="\$_product_tipo['1']=_('Discontinued');\n";
$res.="\$_product_tipo['2']=_('Unique');\n";

$res.="\$_company_area_tipo['0']='';\n";
$res.="\$_company_area_tipo['1']=_('Office');\n";
$res.="\$_company_area_tipo['2']=_('Warehouse');\n";
$res.="\$_company_area_tipo['3']=_('Production');\n";
$res.="\$_company_area_tipo['4']=_('Showroom');\n";
$res.="\$_company_area_tipo['5']=_('Shop');\n";

$res.="\$_company_department_tipo['0']='';\n";
$res.="\$_company_department_tipo['1']=_('Direction');\n";
$res.="\$_company_department_tipo['2']=_('Home Sales');\n";
$res.="\$_company_department_tipo['3']=_('Exports');\n";
$res.="\$_company_department_tipo['4']=_('P&P');\n";
$res.="\$_company_department_tipo['5']=_('Production');\n";
$res.="\$_company_department_tipo['6']=_('IT');\n";
$res.="\$_company_department_tipo['7']=_('Stock Control');\n";
$res.="\$_company_department_tipo['8']=_('Showroom');\n";
$res.="\$_company_department_tipo['9']=_('Marketing');\n";

$res.="\$_position_tipo['0']='';\n";
$res.="\$_position_tipo['1']=_('Picker');\n";
$res.="\$_position_tipo['2']=_('Packer');\n";
$res.="\$_position_tipo['3']=_('Production Operative');\n";
$res.="\$_position_tipo['13']=_('Production Admin');\n";
$res.="\$_position_tipo['4']=_('Customer Services');\n";
$res.="\$_position_tipo['5']=_('Web');\n";
$res.="\$_position_tipo['6']=_('Buyer');\n";
$res.="\$_position_tipo['7']=_('General Admin');\n";
$res.="\$_position_tipo['8']=_('Marketing');\n";
$res.="\$_position_tipo['9']=_('Direct Sales');\n";
$res.="\$_position_tipo['10']=_('Warehouse Operative');\n";
$res.="\$_position_tipo['11']=_('Warehouese Admin');\n";
$res.="\$_position_tipo['12']=_('Admin');\n";



$res.="\$_stockop_tipo['0']=_('Inventory');\n";
$res.="\$_stockop_tipo['1']=_('Purchase');\n";
$res.="\$_stockop_tipo['2']=_('Adjust');\n";
$res.="\$_stockop_tipo['3']=_('Sale');\n";
$res.="\$_stockop_tipo['4']=_('Potencial Sale');\n";


$res.="\$_location_tipo['picking']=_('Picking Area');\n";
$res.="\$_location_tipo['storing']=_('Storing Area');\n";
$res.="\$_location_tipo['loading']=_('Goods In');\n";
$res.="\$_location_tipo['unknown']=_('Unknown');\n";
$res.="\$_location_tipo['white_hole']=_('Error');\n";
$res.="\$_location_tipo['display']=_('On display');\n";


$res="<?
$res?>";

$myFile = "../locale.php";
$fh = fopen($myFile, 'w');
fwrite($fh, $res);
fclose($fh);
?>