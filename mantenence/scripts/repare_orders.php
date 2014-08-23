<?php
include_once('../../conf/dns.php');
include_once('../../class.Customer.php');
include_once('../../class.Order.php');

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::singleton($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  
require_once '../../myconf/conf.php';           
mysql_query("SET time_zone ='+0:00'");
date_default_timezone_set('UTC');
$sql="select * from orden order by date_index,public_id";
$res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $id=$row['id'];
  $orden=new Order('orden',$id);
  $country_id=$orden->get('del_country_id');
  $customer_id=$orden->get('customer_id');
  $customer=new Customer($customer_id);
  // print "$country_id\n";
  if(in_array($country_id,$myconf['tax_obligatory'])){
    $orden->update(array(array('key'=>'vateable','value'=>1)),'save');
  }elseif(in_array($country_id,$myconf['tax_conditional0'])){
    $vat_number=$customer->get('tax_number');
    $vat_number_valid=$customer->get('tax_number_valid');
    if($vat_number_valid and $vat_number!=''){
      $orden->update(array(array('key'=>'vateable','value'=>0)),'save');
    }else{
      //try to get the vat number
      $tax_number=_trim($orden->get('note2'));
      if(preg_match('/^call before delivery please|please despatch today|Call 00353 86 1072367 before delivery.|0857 253566 delivery after 10am|Ouvert Mardi au Vendredi 9.12, 16.19|pleasee give courier contact tel no 003532851522|Despatch on the 12.04.07|if out please call 087 991 1436|call cust on 0879911436 if not in to take delivery|vat|SSC|if out of stock contact customer|Bonus 100 with 2nd invoice|if missing items please contact customer|Missing item|dispatch asap|Please contact customer for out of stock items.IE|not VAT registered.|No VAT Number|Not VAT registered|No VAT No|vat\s*\:?\s*|no vat|CALL CUSTOMER ON MOBILE PRIOR TO DELIVERY|Amtrak|not registered|if order under 300 please contact cust.|Today pm|European Cargo|Received BACS 229.97 pounds, on 24.11|USING Fowarder - DockSpeed . CT21 4LR|if out of stock items please contact customer|Frans Mass|call before delivery 087 123 5845|Euro.48|add catalogue|Post . Sign For|01-01-04|Parcel Force|Post|Post - International|Post Airmail|send to UK Address|SEND TO FRANS MASS|to follow|Sign For$/i',$tax_number) or $tax_number==''){
	$orden->update(array(array('key'=>'vateable','value'=>1)),'save');
      }else if(preg_match('/not Valid|no vat valid/i',$tax_number)){
		$_tax_number=$tax_number;
	$customer->update(array(array('key'=>'tax_number','value'=>_trim($_tax_number))),'save');
	$customer->update(array(array('key'=>'tax_number_valid','value'=>0)),'save');
	$orden->update(array(array('key'=>'vateable','value'=>1)),'save');
      }else if(preg_match('/commercial ID . 246708395|CY-01148250O|00725641X|BE 0891 202 148|MT18036226|\d{8}E|6640177 O|90002013E|EL-067757562|IE 957 427 5A|10173724E|MT-1506 6618|IE 957 427 5A|EL-067757562|90002013E|NL-81734 6879B01|DE 134 380 144|90002013E|EL-377 187 83|202259730B03|FI105 5991-6|BE . 473 238 749|BE 473 238 749|MT1611 6023|SE556670-257601|161174887B01|x5686842-t|SE556670-257601|CVR 24981088|IE 8z68580b|SE 556697283101|CIF. E57310179|IE 9675677J|IE 637 385 4T|IE\s*\:?\s*\d{7}|v\.a\.t\.|valid|vat|tax|checked|IE\s*\-*\d{7}|PT\s?\-?\s?\d{9}|DK\s?\d{8}|FR\s*\d{7}|CY\-?\d{8-9}|^\d{7}[a-z]$/i',$tax_number)){
	$_tax_number=$tax_number;
	$customer->update(array(array('key'=>'tax_number','value'=>_trim($_tax_number))),'save');
	$customer->update(array(array('key'=>'tax_number_valid','value'=>1)),'save');
	$orden->update(array(array('key'=>'vateable','value'=>0)),'save');
      }else{
	print "$tax_number\n";
	$orden->update(array(array('key'=>'vateable','value'=>1)),'save');
      }
    }  

    
  }else{
  
    $orden->update(array(array('key'=>'vateable','value'=>0)),'save');
  }
  
 }

?>