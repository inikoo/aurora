<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
date_default_timezone_set('UTC');

error_reporting(E_ALL);
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


print date("H:i \n");
$table='fr_orders_data.orders';
$sql=sprintf("select * from ".$table);
$res_code=mysql_query($sql);
while ($row=mysql_fetch_array($res_code)) {
    if ($row['date']!='')
        $date=date("Y-m-d H:i:s",strtotime($row['date']." Europe/London"));
    else
        $date='';


    if ($row['last_checked']!='')
        $last=date("Y-m-d H:i:s",strtotime($row['last_checked']." Europe/London"));
    else
        $last='';

    if ($row['last_read']!='')
        $read=date("Y-m-d H:i:s",strtotime($row['last_read']." Europe/London"));
    else
        $read='';

    if ($row['last_transcribed']!='')
        $transcribed=date("Y-m-d H:i:s",strtotime($row['last_transcribed']." Europe/London"));
    else
        $transcribed='';


    $sql=sprintf("update %s set date=%s,last_checked=%s,last_read=%s,last_transcribed=%s where id=%d"
                 ,$table
                 ,prepare_mysql($date)
                 ,prepare_mysql($last)
                 ,prepare_mysql($read)
                 ,prepare_mysql($transcribed)
                 ,$row['id']
                );
   // exit("$sql\n");
    mysql_query($sql);
     
}

?>