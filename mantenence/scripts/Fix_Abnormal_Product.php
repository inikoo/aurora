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
error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once('../../set_locales.php');
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();

//Coca-cola avoid it!, what about today (i can use one of that face mask to avoid infection), but i couldnt make it until 19:45 odeon or 20:20 showroom, if not  sat or sun at around 5  

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           

global $myconf;

$codes_data=array('regex'=>"and `Product Code` REGEXP '-[0-9]+$'",'items'=>array('JBB- sample','JBB','JBB-xx','JBB-BN'));
$codes_data=array('regex'=>"and `Product Units Per Case`=10  and  `Product Code` REGEXP '-[A-Z][0-9]+$'",'items'=>array('FO-xx','FO-mx','FO-mix','FO-','FO-00','FO','FO-Any'));

$regex=$codes_data['regex'];
foreach($codes_data['items'] as $code){

$sql="select * from `Product Dimension` where `Product Code`='$code' order by `Product ID` desc ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product('pid',$row['Product ID']);
  $product->rename_historic(false,$regex);
 }
mysql_free_result($result);
$sql=sprintf("delete from `Product Same Code Dimension` where  `Product Code`='%s' ",$code);
     if(!mysql_query($sql))
       print "error $sql\n";
}



?>