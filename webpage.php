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

$webpage->load_scope();

$website = new Public_Website($webpage->get('Webpage Website Key'));

$store = new Public_Store($webpage->get('Webpage Store Key'));


$content_data = $webpage->get('Content Data');


if ($webpage->get('Webpage Template Filename') == 'products_showcase') {
    include_once 'class.Public_Product.php';

    foreach ($content_data['products'] as $key => $value) {
        $product                                  = new Public_Product($value['product_id']);
        $content_data['products'][$key]['object'] = $product;
    }


} elseif ($webpage->get('Webpage Template Filename') == 'register') {

    require_once 'utils/get_addressing.php';
    list($address_format, $address_labels, $used_fields, $hidden_fields, $required_fields) = get_address_form_data($store->get('Store Home Country Code 2 Alpha'), $website->get('Website Locale'));

    require_once 'utils/get_countries.php';
    $countries = get_countries($website->get('Website Locale'));


    $smarty->assign('address_labels', $address_labels);
    $smarty->assign('used_address_fields', $used_fields);
    $smarty->assign('countries', $countries);
    $smarty->assign('selected_country', $store->get('Store Home Country Code 2 Alpha'));

} else {


    $template = $theme.'/'.$webpage->get('Webpage Template Filename').'.'.$theme.'.tpl';

    if (!file_exists('templates/'.$template)) {
        $template = $theme.'/webpage_blocks.'.$theme.'.tpl';

    }


}


$smarty->assign('content', $content_data);
$smarty->assign('labels', $website->get('Localised Labels'));
$smarty->assign('webpage', $webpage);
$smarty->assign('store', $store);
$smarty->assign('website', $website);
$smarty->assign('theme', $theme);
$smarty->assign('template', $webpage->get('Webpage Template Filename'));


if (file_exists('templates/'.$template)) {
    $smarty->display($template);
} else {
    printf("template %s not found", $template);
}

?>
