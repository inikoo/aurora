<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2016 at 15:13:50 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


$website=$state['_object'];
$theme=$website->get('Website Theme');

$store=get_object('Public_Store',$website->get('Website Store Key'));


$webpage_key = $website->get_system_webpage_key('home.sys');

$webpage=get_object('Webpage',$webpage_key);
$smarty->assign('webpage', $webpage);
$smarty->assign('content', $webpage->get('Content Data'));


$smarty->assign('store', $store);
$smarty->assign('logged_in', 'false');
$smarty->assign('zero_money', money(0,$store->get('Store Currency Code')));
$smarty->assign('price', money(1.99,$store->get('Store Currency Code')));
$smarty->assign('rrp', money(3.99,$store->get('Store Currency Code')));



$smarty->assign('website',$website);
$smarty->assign('theme',$theme);

$header_data = $website->get('Header Data');
$header_key=$website->get('Website Header Key');


$smarty->assign('header_data', $header_data);
$smarty->assign('header_key', $header_key);


$html = $smarty->fetch('theme_1/control.menu.tpl');

?>
