<?php

include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('Europe/London');





$software='Get_Products.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";


$start_date='2003-01-07';

$end_date='2012-01-04';


//./get_currency_exchange.py   20030101 20090701 GBPEUR=X >> currency_dat

$random=md5(mt_rand());
$tmp_file="currency_$random.txt";
$days=100;

//print "./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." GBPEUR=X > $tmp_file\n";exit;
exec("./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." GBPEUR=X > $tmp_file");
exec("./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." EURGBP=X >> $tmp_file");
exec("./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." GBPUSD=X >> $tmp_file");
exec("./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." USDGBP=X >> $tmp_file");
exec("./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." USDEUR=X >> $tmp_file");
exec("./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." EURUSD=X >> $tmp_file");


$row = 1;
$handle = fopen($tmp_file, "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    $pair=preg_replace('/=X/','',$data[0]);
    $date=date("Y-m-d",strtotime($data[1]));
    $exchange=$data[2];

    $sql=sprintf("insert into kbase.`History Currency Exchange Dimension` values (%s,%s,%f)  ",prepare_mysql($date) ,prepare_mysql($pair),$exchange);
    print "$sql\n";
    mysql_query($sql);
}
fclose($handle);
unset($tmp_file);


?>