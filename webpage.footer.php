<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2017 at 17:37:07 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'common.php';
include_once 'class.Public_Website.php';
include_once 'class.Public_Webpage.php';
include_once 'class.Public_Store.php';


if(!isset($_REQUEST['website_key']) or !is_numeric($_REQUEST['website_key'])){
    exit;
}

if(!isset($_REQUEST['theme']) or !in_array($_REQUEST['theme'],array('theme_1'))   ){
    exit;
}

$website_key=$_REQUEST['website_key'];
$theme=$_REQUEST['theme'];

$website=new Public_Website($website_key);
$store=new Public_Store($website->get('Website Store Key'));




$footer_data = $website->get('Footer Data');



$footer_key=$website->get('Website Footer Key');


$smarty->assign('footer_data', $footer_data);
$smarty->assign('footer_key', $footer_key);


$webpage_key=$website->get_system_webpage('home.sys');

$webpage=new Public_Webpage($webpage_key);

$smarty->assign('webpage', $webpage);


$smarty->assign('website', $website);
$smarty->assign('store', $store);



$smarty->display($theme.'/webpage.footer.tpl');

?>
