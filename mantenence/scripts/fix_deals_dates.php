<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
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


$sql="select `Deal Key`,`Deal Begin Date` from `Deal Dimension`";
$result=mysql_query($sql);
while ($row2=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$sql=sprintf("select min(`Order Created Date`) as date from `Order Deal Bridge` B left  join `Order Dimension` O on (O.`Order Key`=B.`Order Key`) where B.`Deal Key`=%d  ",
		$row2['Deal Key']

	);
	$res=mysql_query($sql);

	if ($row=mysql_fetch_assoc($res)) {
		if (strtotime($row['date'])<strtotime($row2['Deal Begin Date']) or $row2['Deal Begin Date']=='') {
			$deal=new Deal($row2['Deal Key']);
			$deal->update(array('Deal Begin Date'=>$row['date']),'no_history');
		}

	}

}

$sql="select `Deal Campaign Key`,`Deal Campaign Valid From` from `Deal Campaign Dimension`";
$result=mysql_query($sql);
while ($row2=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$sql=sprintf("select min(`Order Created Date`) as date from `Order Deal Bridge` B left  join `Order Dimension` O on (O.`Order Key`=B.`Order Key`) where B.`Deal Campaign Key`=%d  ",
		$row2['Deal Campaign Key']

	);
	$res=mysql_query($sql);

	if ($row=mysql_fetch_assoc($res)) {
		if (strtotime($row['date'])<strtotime($row2['Deal Campaign Valid From']) or $row2['Deal Campaign Valid From']=='') {
			$campaign=new DealCampaign($row2['Deal Campaign Key']);
			$campaign->update(array('Deal Campaign Valid From'=>$row['date']),'no_history');
		}

	}elseif($row2['Deal Campaign Valid From']==''){
	print("x");
		$campaign=new DealCampaign($row2['Deal Campaign Key']);
			$campaign->update(array('Deal Campaign Valid From'=>gmdate('Y-m-s H:i:s')),'no_history');
	}

}


?>
