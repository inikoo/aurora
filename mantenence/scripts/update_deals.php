<?php
/*

 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 30 December 2014 23:54:52 GMT, Huddersfield (Train) UK

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.Deal.php';
include_once '../../class.DealCampaign.php';
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




$sql="select * from `Deal Campaign Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$campaign=new DealCampaign($row['Deal Campaign Key']);

	$campaign->update_status_from_dates();

	unset($campaign);

}


$sql="select * from `Deal Dimension` ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$deal=new Deal($row['Deal Key']);

	$deal->update_term_allowances();

	unset($deal);

}



?>
