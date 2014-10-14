<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../conf/dns.php');

include_once('../../class.Category.php');
error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           
setlocale(LC_MONETARY, 'en_GB.UTF-8');


$sql="select `Category Key` from `Category Dimension` where `Category Subject`='Invoice' ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){


 $category=new Category($row['Category Key']);
  $category->update_invoice_category_up_today_sales();


 }
mysql_free_result($result);


?>
