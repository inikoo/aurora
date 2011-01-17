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


$sql=sprintf("truncate `Category Dimension`;");
mysql_query($sql);


$data=array('Category Name'=>'hola','Category Subject'=>'Product');

$cat=new Category('find create',$data);
print_r($cat);
exit;
$nodes=new Nodes('`Category Dimension`');
$data=array('`Category Name`'=>'Use');
$nodes->add_new(0 , $data);



$data=array('`Category Name`'=>'Material');
$nodes->add_new(0 , $data);
$data=array('`Category Name`'=>'Season');
$nodes->add_new(0 , $data);
$data=array('`Category Name`'=>'Theme');
$nodes->add_new(0 , $data);


$data=array('`Category Name`'=>'Other','`Category Default`'=>'Yes');
$nodes->add_new(1 , $data);
$data=array('`Category Name`'=>'Candle');
$nodes->add_new(1 , $data);
$data=array('`Category Name`'=>'Soap');
$nodes->add_new(1 , $data);
$data=array('`Category Name`'=>'Incense');
$nodes->add_new(1 , $data);
$data=array('`Category Name`'=>'Holistic Therapies');
$nodes->add_new(1 , $data);
$data=array('`Category Name`'=>'Bathroom Products');
$nodes->add_new(1 , $data);
$data=array('`Category Name`'=>'Decoration');
$nodes->add_new(1 , $data);

$data=array('`Category Name`'=>'Other','`Category Default`'=>'Yes');
$nodes->add_new(2 , $data);
$data=array('`Category Name`'=>'Wood');
$nodes->add_new(2 , $data);
$data=array('`Category Name`'=>'Metal');
$nodes->add_new(2 , $data);
$data=array('`Category Name`'=>'Grass');
$nodes->add_new(2 , $data);
$data=array('`Category Name`'=>'Resin');
$nodes->add_new(2 , $data);
$data=array('`Category Name`'=>'Ceramic');
$nodes->add_new(2 , $data);
$data=array('`Category Name`'=>'Mineral');
$nodes->add_new(2 , $data);

$data=array('`Category Name`'=>'None','`Category Default`'=>'Yes');
$nodes->add_new(3 , $data);
$data=array('`Category Name`'=>'Christmas');
$nodes->add_new(3 , $data);
$data=array('`Category Name`'=>'Halloween');
$nodes->add_new(3 , $data);
$data=array('`Category Name`'=>"Mother's Day");
$nodes->add_new(3 , $data);
$data=array('`Category Name`'=>"Easter");
$nodes->add_new(3 , $data);

$data=array('`Category Name`'=>"Valentine's");
$nodes->add_new(3 , $data);




$data=array('`Category Name`'=>'Animals');
$nodes->add_new(4 , $data);
$data=array('`Category Name`'=>'Esoteric');
$nodes->add_new(4 , $data);
$data=array('`Category Name`'=>'Fantasy');
$nodes->add_new(4 , $data);
$data=array('`Category Name`'=>'Eastern');
$nodes->add_new(4 , $data);
$data=array('`Category Name`'=>'Contemporaneous');
$nodes->add_new(4 , $data);
?>