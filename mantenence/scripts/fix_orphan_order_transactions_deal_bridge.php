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
include_once('../../class.DeliveryNote.php');
include_once('../../class.Order.php');

include_once('../../class.Customer.php');

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';


$sql=sprintf("select *  from  `Order Transaction Deal Bridge`    ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

    $sql=sprintf("select `Order Key` from `Order Transaction Fact` where `Order Transaction Fact Key`=%d ",
    $row['Order Transaction Fact Key']);
    //print "$sql\n";
    $res2=mysql_query($sql);
    if ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {

		if(preg_match('/[0-9\.]+\% Off$/i',$row['Deal Info'],$match)){
			$percentage=floatval($match[0])/100;
			//print $row['Deal Info']." ".$match[0]." $percentage\n";
			
				if($percentage>0){
				  $sql=sprintf("update   `Order Transaction Deal Bridge` set `Fraction Discount`=%f where `Order Transaction Fact Key`=%d",
				  $percentage,
        $row['Order Transaction Fact Key']);
    //    print "$sql\n";
       mysql_query($sql);
				
				}
		
		}

    } else {
       
        $sql=sprintf("delete  from  `Order Transaction Deal Bridge` where `Order Transaction Fact Key`=%d",
        $row['Order Transaction Fact Key']);
    //    print "$sql\n";
       mysql_query($sql);
    }

}







?>