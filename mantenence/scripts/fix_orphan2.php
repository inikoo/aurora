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

//$sql="select * from kbase.`Country Dimension`";
//$result=mysql_query($sql);
//while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//print "cp ../../examples/_countries/".strtolower(preg_replace('/\s/','_',$row['Country Name']))."/ammap_data.xml ".$row['Country Code'].".xml\n";
//}
//exit;

$sql=sprintf("select `Order Key` from  `Order Dimension`    ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

    $sql=sprintf("select `History Key` , `Direct Object Key` from `History Dimension`  where  `Direct Object` in ('Order')  and `Direct Object Key`=%d ",$row['Order Key']);
    $res=mysql_query($sql);
    if ($ro2w=mysql_fetch_array($res, MYSQL_ASSOC)) {

    } else {
        $id=preg_replace('/[^\d]/i','',$row['Order Original Metadata']);
        $sql=sprintf("update orders_data.orders set last_transcribed=NULL where id=%d",$id);
        mysql_query($sql);
    }

}



$sql=sprintf("select `Delivery Note Key` from  `Delivery Note Dimension`    ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

    $sql=sprintf("select `History Key` , `Direct Object Key` from `History Dimension`  where  `Direct Object` in ('Delivery Note','After Sale')  and  and `Direct Object Key`=%d ",$row['Delivery Note Key']);
    $res=mysql_query($sql);
    if ($row2=mysql_fetch_array($res, MYSQL_ASSOC)) {

    } else {
       $id=preg_replace('/[^\d]/i','',$row['Delivery Note Metadata']);

        $sql=sprintf("update orders_data.orders set last_transcribed=NULL where id=%d",$id);
        mysql_query($sql);
    }

}










?>