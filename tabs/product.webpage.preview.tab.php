<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 December 2016 at 16:00:56 CET, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once('class.Public_Product.php');

$logged = true;


$product = $state['_object'];
$webpage  = $product->get_webpage();


$smarty->assign('webpage', $webpage);


if (!$webpage->id) {
    $html = '<div style="padding:40px">'._("This product don't have webpage").'</div>';

    return;
}

// todo migrate to new webpage & webpage version classes


$public_product = new Public_Product($product->id);
$public_product->load_webpage();
$content_data = $product->webpage->get('Content Data');

switch ($webpage->get('Page Store Content Template Filename')) {
    case 'product':



        $smarty->assign('public_product', $public_product);


        $html = $smarty->fetch('webpage.preview.product.tpl');
    break;
    }
    


?>
