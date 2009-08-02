<?php
/*
file: map_url.php
returns the url of the map
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
require_once 'map_url.php';

$tipo='';
if(isset($_REQUEST['tipo']))
  $tipo=$_REQUEST['tipo'];
$title='';

$options='';
$url='';
$url=get_map_url($tipo);
$response= array('state'=>200,'url'=>$url);

echo json_encode($response);  


?>