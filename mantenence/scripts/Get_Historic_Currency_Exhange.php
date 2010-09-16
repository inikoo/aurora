<?php

include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
include_once('../../class.CurrencyExchange.php');

error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once('../../set_locales.php');
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');


$software='Get_Products.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";


$start_date='2003-01-01';

$end_date=date('Y-m-d');
//chdir('../../');
//$ce=new CurrencyExchange('GBPEUR',$start_date,$end_date);
//exit;


//./get_currency_exchange.py   20030101 20090701 GBPEUR=X >> currency_dat

$random=md5(mt_rand());
$tmp_file="currency_$random.txt";
$days=8000;

$currencies=array('GBP','EUR','PLN','USD','JPY','JPY','AUD','CHF','NZD','CAD','CNY','INR','MXN','BRL','KRW','HKD','ISK','ILS','NOK','DKK','SKK','ZAR','SEK');

exec("echo '' > $tmp_file");

foreach($currencies as $cur1){
foreach($currencies as $cur2){
if($cur1!=$cur2){
print "./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." $cur1$cur2=X >> $tmp_file\n";
exec("./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." $cur1$cur2=X >> $tmp_file");
exec("./get_currency_exchange.py   ".date("Ymd",strtotime("today -$days day"))." ".date("Ymd")." $cur2$cur1=X >> $tmp_file");
}

}
}




$row = 1;
$handle = fopen($tmp_file, "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    $pair=preg_replace('/=X/','',$data[0]);
    $date=date("Y-m-d",strtotime($data[1]));
    $exchange=$data[2];
    if($exchange>0){
    $sql=sprintf("insert into kbase.`History Currency Exchange Dimension` values (%s,%s,%f)  ON DUPLICATE KEY UPDATE `Exchange`=%f  ",
    prepare_mysql($date) ,prepare_mysql($pair),$exchange,$exchange);
   // print "$sql\n";
    mysql_query($sql);
}
}
fclose($handle);
unset($tmp_file);


?>