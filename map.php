<?php
/*
file: map.php
 */
$colors=array(
	      '0x62a74b',
	      '0xc665a7',
	      '0x4dbc9b',
	      '0xe2654f',
	      '0x4c77d1'
	      );

require_once 'common.php';
require_once 'class.Product.php';

$tipo='';
if(isset($_REQUEST['tipo']))
  $tipo=$_REQUEST['tipo'];
$title='';

$options='';


switch($tipo){
case('world_sales'):
  $url="http://chart.apis.google.com/chart?cht=p3&chd=t:60,40&chs=250x100&chl=Hello|World"
  break;

}