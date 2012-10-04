<?php
error_reporting(E_ALL);
date_default_timezone_set('UTC');

require_once '../../app_files/db/dns.php';
require_once '../../class.TimeSeries.php';
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/timezone.php'; 
date_default_timezone_set(TIMEZONE) ;

include_once('../../set_locales.php');

require_once '../../conf/conf.php';   


$_SESSION['lang']=1;


//$stores=array(1);
$forecast=true;

$sql="select * from `Store Dimension` limit 1";
$res=mysql_query($sql);

while( $row=mysql_fetch_array($res)){
  
  
  
 print 'customer population ('.$row['Store Key'].') '."\n";
$tm=new TimeSeries(array('d',"customer population (".$row['Store Key'].")"));
 $tm->get_values('save');

  print 'contact population ('.$row['Store Key'].') '."\n";
$tm=new TimeSeries(array('d',"contact population (".$row['Store Key'].")"));
 $tm->get_values('save');

//  $tm->save_values();
 // if($forecast)
  //  $tm->forecast();


}

?>