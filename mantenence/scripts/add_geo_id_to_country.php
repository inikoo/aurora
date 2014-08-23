<?php
include_once('../../conf/dns.php');
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/timezone.php'; 
date_default_timezone_set(TIMEZONE) ;
require('../../external_libs/Smarty/Smarty.class.php');
$smarty = new Smarty();

require_once '../../conf/conf.php';   
include_once('../../set_locales.php');

date_default_timezone_set('Europe/Madrid');
$_SESSION['lang']=1;

include_once('ci_local_map.php');
include_once('ci_map_order_functions.php');

$software='Get_Orders_DB.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";
srand(1744);


$sql=sprintf("select * from kbase.`Country Dimension`");
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  $sql=sprintf("select `Geography Key` from kbase.`Geography Dimension` where `Geography 2 Alpha Country Code`=%s and( `Geography Feature Code` in ('PCLI','PCLD','PCLS','PCLF','PCLIX') or (`Geography 2 Alpha Country Code` in ('SJ','EH','GG','JE','IM','AQ') and `Geography Feature Code` in ('TERR','PCL') ))"
	       ,prepare_mysql($row['Country 2 Alpha Code']));

  $res2=mysql_query($sql);
  print mysql_num_rows($res2)."\t ".$row['Country 2 Alpha Code']." ".$row['Country Name']."\n";
  if($row2=mysql_fetch_array($res2)){
    $sql="update kbase.`Country Dimension` set `Country Geography Key`="
      .$row2['Geography Key']." where `Country Key`=".$row['Country Key'];
    //print "$sql\n";
    mysql_query($sql);
  }

}
mysql_free_result($res);

?>