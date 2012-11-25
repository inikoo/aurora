<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2011 LW
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
$data=array('`Category Code`'=>'Uso');
$nodes->add_new(0 , $data);



$data=array('`Category Code`'=>'Material');
$nodes->add_new(0 , $data);
$data=array('`Category Code`'=>'Tema');
$nodes->add_new(0 , $data);

$data=array('`Category Code`'=>'Otro','`Category Default`'=>'Yes');
$nodes->add_new(1 , $data);
$data=array('`Category Code`'=>'Velas');
$nodes->add_new(1 , $data);
$data=array('`Category Code`'=>'Jabón');
$nodes->add_new(1 , $data);
$data=array('`Category Code`'=>'Incenso');
$nodes->add_new(1 , $data);
$data=array('`Category Code`'=>'Terapias Holisticas');
$nodes->add_new(1 , $data);
$data=array('`Category Code`'=>'Productos de baño');
$nodes->add_new(1 , $data);
$data=array('`Category Code`'=>'Decoración');
$nodes->add_new(1 , $data);

$data=array('`Category Code`'=>'Otro','`Category Default`'=>'Yes');
$nodes->add_new(2 , $data);
$data=array('`Category Code`'=>'Madera');
$nodes->add_new(2 , $data);
$data=array('`Category Code`'=>'Metal');
$nodes->add_new(2 , $data);
$data=array('`Category Code`'=>'Vidrio');
$nodes->add_new(2 , $data);
$data=array('`Category Code`'=>'Resina');
$nodes->add_new(2 , $data);
$data=array('`Category Code`'=>'Ceramica');
$nodes->add_new(2 , $data);
$data=array('`Category Code`'=>'Mineral');
$nodes->add_new(2 , $data);

$data=array('`Category Code`'=>'Ninguna','`Category Default`'=>'Yes');
$nodes->add_new(3 , $data);
$data=array('`Category Code`'=>'Navidad');
$nodes->add_new(3 , $data);
$data=array('`Category Code`'=>'Halloween');
$nodes->add_new(3 , $data);
$data=array('`Category Code`'=>'Amor');
$nodes->add_new(3 , $data);
$data=array('`Category Code`'=>'Animales');
$nodes->add_new(3 , $data);
$data=array('`Category Code`'=>'Esoterico');
$nodes->add_new(3 , $data);
$data=array('`Category Code`'=>'Fantasia');
$nodes->add_new(3 , $data);
?>