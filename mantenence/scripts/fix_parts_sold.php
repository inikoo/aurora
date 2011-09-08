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

//$sql="select * from kbase.`Country Dimension`";
//$result=mysql_query($sql);
//while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//print "cp ../../examples/_countries/".strtolower(preg_replace('/\s/','_',$row['Country Name']))."/ammap_data.xml ".$row['Country Code'].".xml\n";
//}
//exit;

 $sql="update `Order Transaction Fact` set `Invoice Transaction Net Refund Items`=`Invoice Transaction Net Refund Amount`,`Invoice Transaction Tax Refund Items`=`Invoice Transaction Tax Refund Amount` ";
mysql_query($sql);


$sql="select `Order Transaction Fact Key`,`Inventory Transaction Key`,`Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Net Refund Items` from `Inventory Transaction Fact`   left join `Order Transaction Fact` on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`)  ";
$result=mysql_query($sql);
//print $sql;
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	
	if($row['Order Transaction Fact Key']){
	    
	      $sql=sprintf ( "update  `Inventory Transaction Fact`  set `Amount In`=%f where `Map To Order Transaction Fact Key`=%d "
   ,$row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount']-$row['Invoice Transaction Net Refund Items']
   ,$row['Order Transaction Fact Key']);
// print "$sql\n";
   mysql_query ( $sql );
	    
	
	}
}
?>
