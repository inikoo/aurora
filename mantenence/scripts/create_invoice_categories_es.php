<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Category.php');
include_once('../../class.Node.php');

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

global $myconf;

$sql=sprintf("delete  from `Category Bridge` where `Subject`='Invoice';");
mysql_query($sql);

$sql=sprintf("delete  from `Category Dimension` where `Category Subject`='Invoice';");
mysql_query($sql);
$sql=sprintf("delete  from `Invoice Category Dimension` ");
mysql_query($sql);



$data=array('Category Store Key'=>0,'Category Code'=>'SR','Category Subject'=>'Invoice');
$parent_category=new Category('find create',$data);

$sql=sprintf("update `HQ Dimension` set `SR Category Key`=%d ",$parent_category->id);
mysql_query($sql);

$data=array('Category Store Key'=>1,'Category Code'=>'ES','Category Subject'=>'Invoice','Category Function'=>'if($data["Invoice Billing Country 2 Alpha Code"]=="ES" )');
$category=$parent_category->create_children($data);

$data=array('Category Store Key'=>1,'Category Code'=>'Exporta','Category Subject'=>'Invoice','Category Function'=>'if($data["Invoice Billing Country 2 Alpha Code"]!="ES"       )');
$category=$parent_category->create_children($data);




//$data=array('Category Store Key'=>1,'Category Code'=>'Staff','Category Subject'=>'Invoice','Category Function'=>'');
//$cat=new Category('find create',$data);
?>