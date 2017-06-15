<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2017 at 19:20:06 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'common.php';
include_once 'class.Public_Webpage.php';
include_once 'class.Public_Store.php';
include_once 'class.Public_Website.php';


if (!isset($_REQUEST['webpage_key']) or !is_numeric($_REQUEST['webpage_key'])) {
    exit;
}

if (!isset($_REQUEST['theme']) or !preg_match('/^theme\_\d+$/', $_REQUEST['theme'])) {

    print 'no theme set up';

    return;

}



$webpage_key = $_REQUEST['webpage_key'];
$theme       = $_REQUEST['theme'];

$webpage = new Public_Webpage($webpage_key);
$website = new Public_Website($webpage_key);

$store   = new Public_Store($webpage->get('Webpage Store Key'));



$content_data = $webpage->get('Content Data');


$smarty->assign('content', $content_data);

//print_r($content_data);

$smarty->assign('webpage', $webpage);
$smarty->assign('store', $store);
$smarty->assign('website', $website);

$smarty->assign('theme', $theme);
$smarty->assign('template', $webpage->get('Webpage Template Filename'));


$template = $theme.'/'.$webpage->get('Webpage Template Filename').'.'.$theme.'.tpl';

if (file_exists('templates/'.$template)) {
    $smarty->display($template);
} else {
    printf("template %s not found",$template);
}

?>
