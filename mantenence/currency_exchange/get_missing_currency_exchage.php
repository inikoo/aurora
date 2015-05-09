<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.CurrencyExchange.php';

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


require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;


$currency_pair='GBPPLN';

$sql="select * from kbase.`Date Dimension` where `Date`<='".date('Y-m-d')."' order by `Date` desc   limit 5000";
$result=mysql_query($sql);
$contador=0;
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$exchange=new CurrencyExchange($currency_pair,$row['Date']);
	
		
	
	if ($exchange->source!='kbase') {
		$contador++;
		printf("%s %s %f %s\n",$row['Date'],$currency_pair,$exchange->exchange,$exchange->source);

		if ($contador>10) {
			exit;
		}
	}
}






?>
