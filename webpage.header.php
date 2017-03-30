<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 March 2017 at 17:50:36 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'common.php';
include_once 'class.Website.php';


if(!isset($_REQUEST['website_key']) or !is_numeric($_REQUEST['website_key'])){
    exit;
}


$website_key=$_REQUEST['website_key'];

$website=new Website($website_key);


$header_data = $website->get('header Data');


$header_data=array(

'logo_image_key'=>1

);

$header_key=$website->get('Website header Key');





$smarty->assign('header_data', $header_data);
$smarty->assign('header_key', $header_key);



$smarty->assign('website', $website);

$smarty->display('webpage.header.tpl');
?>
