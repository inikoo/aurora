<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2017 at 17:37:07 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'common.php';
include_once 'class.Website.php';


if(!isset($_REQUEST['website_key']) or !is_numeric($_REQUEST['website_key'])){
    exit;
}


$website_key=$_REQUEST['website_key'];

$website=new Website($website_key);


$footer_data = $website->get('Footer Data');
$footer_key=$website->get('Website Footer Key');


$smarty->assign('footer_data', $footer_data);
$smarty->assign('footer_key', $footer_key);



$smarty->assign('website', $website);


$smarty->display('webpage.footer.tpl');

?>
