<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';

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





$sql="select * from `List Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$number_items=0;
	switch ($row['List Scope']) {
	case 'Customer':

		if ($row['List Type']=='Dynamic') {
			$awhere=$row['List Metadata'];
$tmp=preg_replace('/\\\"/','"',$awhere);
				$tmp=preg_replace('/\\\\\"/','"',$tmp);
				$tmp=preg_replace('/\'/',"\'",$tmp);

				$raw_data=json_decode($tmp, true);
			include_once '../../list_functions_customer.php';
			list($where,$table)=customers_awhere($raw_data);



			$sql="select count(Distinct C.`Customer Key`) as total from $table  $where";
			$res2=mysql_query($sql);

			if ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
				$number_items=$row2['total'];

			}
		}else {
			$sql=sprintf("select count(Distinct `Customer Key`) as total from `List Customer Bridge`  where `List Key`=%d ",
			$row['List Key']
			);
			$res2=mysql_query($sql);

			if ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
				$number_items=$row2['total'];

			}

		}


		$sql=sprintf("update `List Dimension` set `List Number Items`=%d  where `List Key`=%d",
		$number_items,
		$row['List Key']
		);	
		mysql_query($sql);
		break;
	}




}





?>
