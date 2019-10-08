<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2017 at 19:20:06 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'common.php';
include_once 'utils/object_functions.php';


if (!isset($_REQUEST['webpage_key']) or !is_numeric($_REQUEST['webpage_key'])) {
    exit;
}

if (!isset($_REQUEST['theme']) or !preg_match('/^theme\_\d+$/', $_REQUEST['theme'])) {
    print 'no theme set up x';

    return;
}




$webpage_key = $_REQUEST['webpage_key'];
$theme       = $_REQUEST['theme'];

$webpage = get_object('Public_Webpage', $webpage_key);

$webpage->load_scope();

$website = get_object('Public_Website', $webpage->get('Webpage Website Key'));

$store = get_object('Public_Store', $webpage->get('Webpage Store Key'));


$content_data = $webpage->get('Content Data');

//print_r($content_data);
//exit;

$header_data = $website->get('Header Data');
$header_key  = $website->get('Website Header Key');


$smarty->assign('header_data', $header_data);
$smarty->assign('header_key', $header_key);

switch ($webpage->get('Webpage Scope')) {
    case 'Product':
        $public_product = get_object('Public_Product', $webpage->get('Webpage Scope Key'));
        $smarty->assign('product', $public_product);


        break;
}


//print_r($webpage);


if ($webpage->get('Webpage Code') == 'register.sys') {
    $scope_metadata = $webpage->get('Scope Metadata');


    require_once 'utils/get_addressing.php';
    list($address_format, $address_labels, $used_fields, $hidden_fields, $required_fields) = get_address_form_data($store->get('Store Home Country Code 2 Alpha'), $website->get('Website Locale'));

    require_once 'utils/get_countries.php';
    $countries = get_countries($website->get('Website Locale'));


    $smarty->assign('address_labels', $address_labels);
    $smarty->assign('used_address_fields', $used_fields);
    $smarty->assign('countries', $countries);
    $smarty->assign('selected_country', $store->get('Store Home Country Code 2 Alpha'));

}


$template = $theme.'/webpage_blocks.'.$theme.'.tpl';


$smarty->assign('content', $content_data);
$smarty->assign('labels', $website->get('Localised Labels'));
$smarty->assign('webpage', $webpage);
$smarty->assign('store', $store);
$smarty->assign('website', $website);
$smarty->assign('theme', $theme);


$smarty->assign('navigation', $webpage->get('Navigation Data'));
$smarty->assign('discounts', $webpage->get('Discounts'));


$smarty->assign('poll_queries', $website->get_poll_queries($webpage));


if (file_exists('templates/'.$template)) {
    $smarty->display($template);
} else {
    printf("template %s not found", $template);
}


