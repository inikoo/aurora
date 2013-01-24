<?php
error_reporting(E_ALL);

include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Order.php';
include_once '../../class.Invoice.php';
include_once '../../class.DeliveryNote.php';
include_once '../../class.Email.php';
include_once '../../class.TimeSeries.php';
include_once '../../class.CurrencyExchange.php';
include_once '../../class.TaxCategory.php';
include_once '../../class.PartLocation.php';
include_once '../../class.Deal.php';




function microtime_float() {
	list($utime, $time) = explode(" ", microtime());
	return (float)$utime + (float)$time;
}


$myFile = "orders_time.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
$time_data=array();
$orders_done=0;
$store_code='U';
$__currency_code='GBP';




$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
	print "Error can not connect with database server\n";
	print "->End.(GO UK) ".date("r")."\n";
	exit;
}

//$dns_db='dw_avant2';


$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	print "->End.(GO UK) ".date("r")."\n";
	exit;
}
date_default_timezone_set('UTC');
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;

include_once '../../set_locales.php';

require_once '../../conf/conf.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();

$currency='GBP';
$_SESSION['lang']=1;





$sql="select * from `Address Bridge` CB where   `Address Key`=0  and `Subject Type`='Customer'  ";
$result3=mysql_query($sql);
while ($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ) {
	$customer= new Customer($row3['Subject Key']);

	print "Customer: $customer->id\n";

$sql="delete from `Address Bridge`  where   `Address Key`=0  and `Subject Type`='Customer' and `Subject Key`=".$customer->id;

mysql_query($sql);
	

	$address=new Address('create',array('Address Country Key'=>'','Address Country 2 Alpha Code'=>'','Address Country Name'=>'','Military Address'=>'No','Address Building'=>'','Address Town First Division'=>'','Address Town Second Division'=>'','Address Street Name'=>'','Address Country Second Division'=>'','Address Country First Division'=>'','Address Postal Code'=>'','Address Town First Division'=>'','Address Street Type'=>'','Address Street Number'=>'','Address Town'=>'',
			'Address Internal'=>'','Address Country Code'=>'UNK','Address Fuzzy'=>'Yes'));
		
	
	
	
	$address->get_data('id',$address->id);
	$customer->new=true;

	if ($customer->data['Customer Type']=='Company') {




		$address=new Address($company->data['Company Main Address Key']);

		$address->new=true;


		$customer->create_contact_address_bridge($address->id);


		$address->update_parents_principal_telecom_keys('Telephone',($customer->new?false:true));
		$address->update_parents_principal_telecom_keys('FAX',($customer->new?false:true));






	}
	else {
		$customer->create_contact_address_bridge($address->id);
		$address->update_parents_principal_telecom_keys('Telephone',($customer->new?false:true));
		$address->update_parents_principal_telecom_keys('FAX',($customer->new?false:true));


		$address->update_parents(false,($customer->new?false:true));

	}







	$customer->get_data('id',$customer->id);


	$customer->data['Customer Billing Address Link']=='Contact';
	$customer->data['Customer Delivery Address Link']=='Contact';


	$customer->associate_billing_address($address->id);
	$customer->associate_delivery_address($address->id);



}
?>
