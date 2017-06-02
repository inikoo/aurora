<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2017 at 19:20:06 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'common.php';
include_once 'class.Page.php';
include_once 'class.Store.php';
include_once 'class.Website.php';


if(!isset($_REQUEST['webpage_key']) or !is_numeric($_REQUEST['webpage_key'])){
    exit;
}

if(!isset($_REQUEST['theme']) or !in_array($_REQUEST['theme'],array('theme_1'))   ){
    exit;
}


$webpage_key=$_REQUEST['webpage_key'];
$theme=$_REQUEST['theme'];

$webpage=new Page($webpage_key);
$store=new Store($webpage->get('Webpage Store Key'));


$content_data = $webpage->get('Content Data');


$smarty->assign('content', $content_data);



$smarty->assign('webpage', $webpage);
$smarty->assign('store', $store);


$smarty->display($theme.'/'.$webpage->get('Webpage Template Filename').'.'.$theme.'.tpl');

?>
