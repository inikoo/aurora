<?php
include_once('../../conf/dns.php');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once 'timezone.php'; 
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
  $sql=sprintf("select * from kbase.`Geography Alias Dimension` where `Geography Key`=%d ",$row['Country Geography Key']);
  $res2=mysql_query($sql);
  while($row2=mysql_fetch_array($res2)){
    
    $tipo='Alias';

    if(!($row2['Language Code']=='' or  $row2['Language Code']=='en' ))
      $tipo='Other Language';
    if($row2['Is Short Name']=='Yes')
       $tipo='Short Name';
    $sql=sprintf("insert into  kbase.`Country Alias Dimension` values (%s,%s,%s)  "
		 ,prepare_mysql($row['Country Code'])
		 ,prepare_mysql($row2['Alias'])
		 ,prepare_mysql($tipo)
		 );
    print "$sql\n";
    mysql_query($sql);
  }

}
mysql_free_result($res);

?>