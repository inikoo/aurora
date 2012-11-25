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


$data=array('Category Code'=>'hola','Category Subject'=>'Product');

$cat=new Category('find create',$data);
print_r($cat);
exit;
$nodes=new Nodes('`Category Dimension`');
$data=array('`Category Code`'=>'Use');
$nodes->add_new(0 , $data);



$data=array('`Category Code`'=>'Material');
$nodes->add_new(0 , $data);
$data=array('`Category Code`'=>'Season');
$nodes->add_new(0 , $data);
$data=array('`Category Code`'=>'Theme');
$nodes->add_new(0 , $data);


$data=array('`Category Code`'=>'Other','`Category Default`'=>'Yes');
$nodes->add_new(1 , $data);
$data=array('`Category Code`'=>'Candle');
$nodes->add_new(1 , $data);
$data=array('`Category Code`'=>'Soap');
$nodes->add_new(1 , $data);
$data=array('`Category Code`'=>'Incense');
$nodes->add_new(1 , $data);
$data=array('`Category Code`'=>'Holistic Therapies');
$nodes->add_new(1 , $data);
$data=array('`Category Code`'=>'Bathroom Products');
$nodes->add_new(1 , $data);
$data=array('`Category Code`'=>'Decoration');
$nodes->add_new(1 , $data);

$data=array('`Category Code`'=>'Other','`Category Default`'=>'Yes');
$nodes->add_new(2 , $data);
$data=array('`Category Code`'=>'Wood');
$nodes->add_new(2 , $data);
$data=array('`Category Code`'=>'Metal');
$nodes->add_new(2 , $data);
$data=array('`Category Code`'=>'Grass');
$nodes->add_new(2 , $data);
$data=array('`Category Code`'=>'Resin');
$nodes->add_new(2 , $data);
$data=array('`Category Code`'=>'Ceramic');
$nodes->add_new(2 , $data);
$data=array('`Category Code`'=>'Mineral');
$nodes->add_new(2 , $data);

$data=array('`Category Code`'=>'None','`Category Default`'=>'Yes');
$nodes->add_new(3 , $data);
$data=array('`Category Code`'=>'Christmas');
$nodes->add_new(3 , $data);
$data=array('`Category Code`'=>'Halloween');
$nodes->add_new(3 , $data);
$data=array('`Category Code`'=>"Mother's Day");
$nodes->add_new(3 , $data);
$data=array('`Category Code`'=>"Easter");
$nodes->add_new(3 , $data);

$data=array('`Category Code`'=>"Valentine's");
$nodes->add_new(3 , $data);




$data=array('`Category Code`'=>'Animals');
$nodes->add_new(4 , $data);
$data=array('`Category Code`'=>'Esoteric');
$nodes->add_new(4 , $data);
$data=array('`Category Code`'=>'Fantasy');
$nodes->add_new(4 , $data);
$data=array('`Category Code`'=>'Eastern');
$nodes->add_new(4 , $data);
$data=array('`Category Code`'=>'Contemporaneous');
$nodes->add_new(4 , $data);
?>