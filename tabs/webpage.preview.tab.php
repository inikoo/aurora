<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 May 2017 at 11:30:14 GMT-5, CdMx, Mexico
 Copyright (c) 2016, Inikoo

 Version 3

*/


if ($state['_object']->get_object_name() == 'Product') {
    $webpage = get_object('Webpage', $state['_object']->get('Product Webpage Key'));
} else {
    $webpage = $state['_object'];

}

$website = get_object('Website', $webpage->get('Webpage Website Key'));


if (!$webpage->id) {
    $html = '<div style="padding:40px">'.'Webpage not found'.'</div>';

    return;
}


$theme = $website->get('Website Theme');


$smarty->assign('theme', $theme);
$smarty->assign('webpage', $webpage);
$smarty->assign('website', $website);


$smarty->assign('content', $webpage->get('Content Data'));
$smarty->assign('metadata', $webpage->get('Scope MetaData'));


include_once 'conf/webpage_blocks.php';
$blocks = get_webpage_blocks();


if (!$state['store']->get('Reviews Settings')) {
    unset($blocks['reviews']);
}
$smarty->assign('blocks', $blocks);

$smarty->assign('control_template', $theme.'/control.webpage_blocks.'.$theme.'.tpl');


// print_r( $webpage->get('Content Data'));


$html = '';
if ($webpage->get('Webpage Scope') == 'Category Categories' or $webpage->get('Webpage Scope') == 'Category Products') {

    $scope = get_object('Category', $webpage->get('Webpage Scope Key'));


    if ($scope->get('Product Category Public') == 'No') {

        if ($scope->get('Product Category Public') == 'No') {
            $smarty->assign('offline', true);

            $html = '<div style="background-color: tomato;color:whitesmoke;padding:5px 20px"><h1>'._('Category not public, webpage offline').'</h1></div>';
        }
    }

}

$html .= $smarty->fetch('webpage_preview.tpl');





