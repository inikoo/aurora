<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../conf/dns.php');
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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           

$sql="select * from `Customer Dimension`  order by `Customer Key` desc ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

 $customer = new Customer ($row['Customer Key']);
 if( $customer->id){
 $customer->update_correlations();
 print "XXXXXXXXXXXXXXXX ".$row['Customer Key']." \r";

}






  
 
}



?>