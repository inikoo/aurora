<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
include_once('../../class.Customer.php');

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           


$sql="delete from `Dashboard Widget Bridge` ";
mysql_query($sql);

$sql="delete from `Dashboard Dimension` ";
mysql_query($sql);

$sql="select * from `User Dimension`  where `User Type`='Staff' or  `User Type`='Administrator' or `User Type`='Supplier'  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

$sql=sprintf("select * from `Dashboard Dimension` where `User Key`=%d order by `Dashboard Order` DESC", $row['User Key']);
$res=mysql_query($sql);



if($row['User Type']=='Administrator' or $row['User Type']=='Staff'){
	$sql=sprintf("insert into `Dashboard Dimension` (`User Key`, `Dashboard Order`) values (%d, 1)", $row['User Key']);
	mysql_query($sql);
	$dashboard1_id=mysql_insert_id();


	$sql=sprintf("update `User Dimension` set `User Dashboard Key`=%d where `User Key`=%d", $dashboard1_id, $row['User Key']);
	mysql_query($sql);

	$sql=sprintf("insert into `Dashboard Dimension` (`User Key`, `Dashboard Order`) values (%d, 2)", $row['User Key']);
	mysql_query($sql);
        $dashboard2_id=mysql_insert_id();



	$sql=sprintf("insert into `Dashboard Widget Bridge` (`Dashboard Key`, `Widget Key`, `Dashboard Widget Order`) values ($dashboard1_id, 1, 1)");

	mysql_query($sql);       
	$sql=sprintf("insert into `Dashboard Widget Bridge` (`Dashboard Key`, `Widget Key`, `Dashboard Widget Order`) values ($dashboard1_id, 4, 2)");
	mysql_query($sql);
	$sql=sprintf("insert into `Dashboard Widget Bridge` (`Dashboard Key`, `Widget Key`, `Dashboard Widget Order`, `Dashboard Widget Height`) values ($dashboard2_id, 2, 1, 405)");
	mysql_query($sql);
	$sql=sprintf("insert into `Dashboard Widget Bridge` (`Dashboard Key`, `Widget Key`, `Dashboard Widget Order`, `Dashboard Widget Height`) values ($dashboard2_id, 3, 2, 560)");
	mysql_query($sql);
	
}
else{
	
	$sql=sprintf("insert into `Dashboard Dimension` (`User Key`, `Dashboard Order`) values (%d, 1)", $row['User Key']);
	mysql_query($sql);
	$dashboard1_id=mysql_insert_id();


	$sql=sprintf("insert into `Dashboard Widget Bridge` (`Dashboard Key`, `Widget Key`, `Dashboard Widget Order`, `Dashboard Widget Height`) values ($dashboard1_id, 2, 1, 405)");
	mysql_query($sql);
	$sql=sprintf("insert into `Dashboard Widget Bridge` (`Dashboard Key`, `Widget Key`, `Dashboard Widget Order`, `Dashboard Widget Height`) values ($dashboard1_id, 3, 2, 560)");
	mysql_query($sql);

}


}

?>
