<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.Country.php';

include_once '../../edit_customers_functions.php';
include_once 'dropshipping_common_functions.php';

error_reporting(E_ALL);

date_default_timezone_set('UTC');




$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db("dw", $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);
$store=new Store('code','DS');

$sql= "SELECT * FROM ancient_dropshipnew.`customer_entity` where entity_id=1042 ";
$sql= "SELECT * FROM ancient_dropshipnew.`customer_entity`  ";

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {


	$email=$row['email'];
	$name='';
	$tel='';
	$fax='';
	$company='';





	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity` WHERE  `parent_id` =%d",$row['entity_id']);
	$res2=mysql_query($sql);
	if ($row2=mysql_fetch_assoc($res2)) {



		$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_varchar` WHERE `attribute_id` = 17 AND `entity_id` =%d",$row2['entity_id']);
		//print "$sql\n";
		$res3=mysql_query($sql);
		while ($row3=mysql_fetch_assoc($res3)) {
			if ($row3['value']!='')
				$name.=' '.$row3['value'];
		}
		$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_varchar` WHERE `attribute_id` = 18 AND `entity_id` =%d",$row2['entity_id']);
		$res3=mysql_query($sql);
		while ($row3=mysql_fetch_assoc($res3)) {
			if ($row3['value']!='')
				$name.=' '.$row3['value'];
		}
		$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_varchar` WHERE `attribute_id` = 19 AND `entity_id` =%d",$row2['entity_id']);
		$res3=mysql_query($sql);
		while ($row3=mysql_fetch_assoc($res3)) {
			if ($row3['value']!='')
				$name.=' '.$row3['value'];
		}
		$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_varchar` WHERE `attribute_id` = 20 AND `entity_id` =%d",$row2['entity_id']);
		$res3=mysql_query($sql);
		while ($row3=mysql_fetch_assoc($res3)) {
			if ($row3['value']!='')
				$name.=' '.$row3['value'];
		}
		$name=_trim($name);


		$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_varchar` WHERE `attribute_id` = 22 AND `entity_id` =%d",$row2['entity_id']);
		$res3=mysql_query($sql);
		while ($row3=mysql_fetch_assoc($res3)) {
			if ($row3['value']!='')
				$company=$row3['value'];
		}
		$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_varchar` WHERE `attribute_id` = 29 AND `entity_id` =%d",$row2['entity_id']);
		$res3=mysql_query($sql);
		while ($row3=mysql_fetch_assoc($res3)) {
			if ($row3['value']!='')
				$tel=$row3['value'];
		}
		$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_varchar` WHERE `attribute_id` = 30 AND `entity_id` =%d",$row2['entity_id']);
		$res3=mysql_query($sql);
		while ($row3=mysql_fetch_assoc($res3)) {
			if ($row3['value']!='')
				$fax=$row3['value'];
		}


list($address1,$address2,$town,$postcode,$country_div,$country)=get_address($row2['entity_id']);


		$country=new Country('2alpha',$country);
//print_r($country);
//exit;

		list($tipo_customer,$company_name,$contact_name)=parse_company_person($company,$name);

		$customer=new Customer('old_id',$row['entity_id'],$store->id);
		if ($customer->id) {

		}else {

			$customer_data=array(
				"Customer Type"=>$tipo_customer,
				'Customer First Contacted Date'=>$row['created_at'],
				"Customer Store Key"=>$store->id,
				"Customer Old ID"=>$row['entity_id'],
				"Customer Name"=>$company_name
				,"Customer Main Contact Name"=>$contact_name
				//    ,"Customer Tax Number"=>''
				//   ,"Customer Registration Number"=>''
				,"Customer Main Plain Email"=>$email
				,"Customer Main Plain Telephone"=>$tel
				,"Customer Main Plain FAX"=>$fax
				//    ,"Customer Main Plain Mobile"=>''
				,"Customer Address Line 1"=>$address1
				,"Customer Address Line 2"=>$address2
				,"Customer Address Line 3"=>''
				,"Customer Address Town"=>$town
				,"Customer Address Postal Code"=>$postcode
				,"Customer Address Country Name"=>$country->data['Country Name']
				,"Customer Address Country Code"=>$country->data['Country Code']
				,"Customer Address Town Second Division"=>''
				,"Customer Address Town First Division"=>$country_div
				,"Customer Address Country First Division"=>''
				,"Customer Address Country Second Division"=>''
				,"Customer Address Country Third Division"=>''
				,"Customer Address Country Forth Division"=>''
				,"Customer Address Country Fifth Division"=>''

			);

			$editor['Date']=$row['created_at'];
			if ($customer_data['Customer Address Country Code']=='')
				$customer_data['Customer Address Country Code']='UNK';

			$customer_data['editor']=$editor;
			//print_r($customer_data);
			$response=add_customer($customer_data) ;
			//exit;

		}


	}

}




?>
