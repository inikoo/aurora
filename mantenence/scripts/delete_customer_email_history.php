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
include_once('../../class.Order.php');

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


$sql="delete from `Email Read Dimension` where `Customer Communications`='Yes' ";
  mysql_query($sql);
  

$sql="select * from `Attachment Bridge` where `Subject` in ('Customer Communications') ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    
$attachment=new Attachment($row['Attachment Key']);
$attachment->delete();
    
 
    
        
}



$i=0;
$sql="select `History Key` from `Customer History Bridge` where `Type` in ('Emails','Email','') ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    


       print "$i\n";
       $sql=sprintf("delete from `History Dimension` where `History Key`=%d ",$row['History Key']);
       mysql_query($sql);
        $sql=sprintf("delete from `Customer History Bridge` where `History Key`=%d ",$row['History Key']);
       mysql_query($sql);
       $i++;
 
    
        
}
 
 



?>
