<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2013 Inikoo Ltd
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.Category.php';
include_once '../../class.Account.php';


error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}

date_default_timezone_set('UTC');
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
$inikoo_account=new Account();
date_default_timezone_set($inikoo_account->data['Account Timezone']) ;
define("TIMEZONE",$inikoo_account->data['Account Timezone']);

include_once '../../set_locales.php';

require_once '../../conf/conf.php';
require '../../locale.php';

global $myconf;



$sql="select * from `Store Dimension` ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$store=new Store($row['Store Key']);
	if($store->data['Store Collection Address Key']){
	$collection_address=new Address($store->data['Store Collection Address Key']);
	$store->update(array('Store Collection XHTML Address'=>$collection_address->display('xhtml')));
	}
	$store->update_deals_data();
	$store->update_campaings_data();
	

}



?>
