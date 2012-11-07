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
include_once '../../class.PartLocation.php';

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
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;


// FOr carlos delete next exit;

	$sql=sprintf("select `Page Key` from `User Request Dimension` group by `Page Key`");
		$result1=mysql_query($sql);
		while ($row1=mysql_fetch_array($result1, MYSQL_ASSOC)   ) {
		$page=new Page($row1['Page Key']);
		
		if($page->id and $page->type=='Store' ){
			$sql=sprintf("update `User Request Dimension` set `Site Key`=%s where `Page Key`=%d ",$page->data['Page Site Key'],$row1['Page Key']);
			mysql_query($sql);
			// print"$sql\n";


		}

}

	$sql=sprintf("update `User Request Dimension` set `Is User`='Yes' where `User Key`>0 ");
			mysql_query($sql);
			$sql=sprintf("update `User Request Dimension` set `Is User`='No' where `User Key`=0 ");
			mysql_query($sql);
			


exit;
//exit, maybe carlos NEEDs the bottom part!!!!!!!!!!!!!!!!

$_date=gmdate("Y-d-m H:i:s",strtotime('now -32 hour'));
$sql=sprintf("select * from `User Log Dimension` where `Site Key`>0 and   `Remember Cookie`!='Yes' and `Last Visit Date`<%s ",prepare_mysql($_date));

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$sql=sprintf("update `User Log Dimension` set `Status`='Close' where `User Log Key`=%d ",$row['User Log Key']);
	mysql_query($sql);
//	print "$sql\n";

}




$sql="select * from `User Log Dimension`   ";


$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	if ($row['Logout Date']!='') {
		$sql=sprintf("update `User Log Dimension` set `Status`='Close' where `User Log Key`=%d ",$row['User Log Key']);
		mysql_query($sql);
	}


	


		$sql=sprintf("select MAX(`Date`) as last_request from `User Request Dimension` where `User Log Key`=%d ",$row['User Log Key']);
		$result1=mysql_query($sql);
		if ($row1=mysql_fetch_array($result1, MYSQL_ASSOC)   ) {
		
		if($row1['last_request']!=''){
			$sql=sprintf("update `User Log Dimension` set `Last Visit Date`=%s where `User Log Key`=%d ",prepare_mysql($row1['last_request']),$row['User Log Key']);
			mysql_query($sql);
			// print"$sql\n";


		}
}



	$sql=sprintf("select `User Site Key` from  `User Dimension` where `User Key`=%d ",$row['User Key']);
	$result1=mysql_query($sql);
	if ($row1=mysql_fetch_array($result1, MYSQL_ASSOC)   ) {
		$sql=sprintf("update `User Log Dimension` set `Site Key`=%d where `User Key`=%d ",$row1['User Site Key'],$row['User Key']);
		mysql_query($sql);
		// print"$sql\n";


	}



}

$sql="select * from `User Log Dimension`   ";


$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	if ($row['Last Visit Date']=='') {
		$sql=sprintf("update `User Log Dimension` set `Last Visit Date`=%s where `User Log Key`=%d ",prepare_mysql($row['Start Date']),$row['User Log Key']);
		mysql_query($sql);
	}

}



?>
