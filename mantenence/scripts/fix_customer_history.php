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



$sql="select `History Key` ,`Indirect Object Key` from `History Dimension`  where `Direct Object`='Note' and `Indirect Object`='Customer'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

  $sql=sprintf('insert into  `Customer History Bridge`  values (%d,%d,"No","Notes") ',
                                  $row['History Key'],
                                
                                 $row['Indirect Object Key']
                                );
       //print "$sql\n";
        mysql_query($sql);
                                
}







$sql="select H.`History Key`, B.`History Key` as h from `Customer History Bridge` B left join  `History Dimension` H on (B.`History Key`=H.`History Key`)";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
if($row['History Key']==''){
  $sql=sprintf('delete  from `Customer History Bridge`  where `History Key`=%d ',
                                 
                                
                                 $row['h']
                                );
        mysql_query($sql);
                                
}
}




$sql="select * from `Customer History Bridge` B left join  `History Dimension` H on (B.`History Key`=H.`History Key`)";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

  //print $row['Customer Key']."\n";
  //$customer=new Customer($row['Customer Key']);
  $type='Changes';  
  if($row['Subject']=='Customer' and $row['Direct Object']=='Order'){
    $type='Orders';
  }elseif($row['Direct Object']=='Note'){
    $type='Notes';
  }

    $sql=sprintf('update `Customer History Bridge` set `Type`=%s where `History Key`=%d ',
                                  prepare_mysql($type),
                                
                                 $row['History Key']
                                );
                    mysql_query($sql);
		    // print "$sql\n";

		    // print $customer->id."\r";
 }



?>