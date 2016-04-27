<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 April 2016 at 12:58:45 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


if(!isset($_REQUEST['number'])){
    exit;

}
$number=$_REQUEST['number'];

if(!is_numeric($number)){
    exit;

}


if(isset($_REQUEST['scale']) and is_numeric($_REQUEST['scale'])){
    $scale=ceil($_REQUEST['scale']);
}else{
    $scale=null;
}

include_once('external_libs/barcodes/ean.php');



$ean = new EAN(substr($number,0,12),$scale);
	
$ean->display();


?>