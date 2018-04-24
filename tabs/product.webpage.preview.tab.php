<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 December 2016 at 16:00:56 CET, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once('class.Public_Product.php');
include_once('class.Public_Webpage.php');

$logged = true;


$product = $state['_object'];
$webpage = $product->get_webpage();
$website=get_object('Website',$webpage->get('Webpage Website Key'));


$smarty->assign('webpage', $webpage);
$smarty->assign('website', $website);

$smarty->assign('labels', $website->get('Localised Labels'));

if (!$webpage->id) {
    $html = '<div style="padding:40px">'._("This product don't have webpage").'</div>';

    return;
}

// todo migrate to new webpage & webpage version classes


$public_product = new Product($product->id);
//$public_product->load_webpage();

$public_webpage=new Public_Webpage('scope', 'Product', $product->id);

$content_data = $product->webpage->get('Content Data');

//print_r($website);


if($website->get('Website Theme')=='theme_1'){

$html='xx';

}else{
    //todo delete this after migration

    switch ($webpage->get('Page Store Content Template Filename')) {
        case 'product':

            $content_data = $webpage->get('Content Data');


            if (($webpage->id and $webpage->get('Content Data') == '')) {

                $content_data = array(
                    'description_block' => array(
                        'class' => '',

                        'content' => sprintf('<div class="description">%s</div>', $public_product->get('Description'))


                    ),
                    'tabs'              => array()

                );

                $webpage->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


            }


            $smarty->assign('content_data', $content_data);


            $smarty->assign('public_product', $public_product);
            $smarty->assign('public_webpage', $public_webpage);

            $origin       = $public_product->get('Origin');
            $cpnp       = $public_product->get('CPNP Number');
            $materials  = $public_product->get('Materials');
            $weight     = $public_product->get('Unit Weight');
            $dimensions = $public_product->get('Unit Dimensions');
            $product_attachments = $public_product->get_attachments();

            $smarty->assign('CPNP', $cpnp);
            $smarty->assign('Materials', $materials);
            $smarty->assign('Weight', $weight);
            $smarty->assign('Dimensions', $dimensions);
            $smarty->assign('Origin', $origin);
            $smarty->assign('product_attachments', $product_attachments);




            if ($weight != '' or $dimensions != ''or  $origin!='' or $cpnp != '' or $materials != '' or count($product_attachments)>0 ) {
                $has_properties_tab = true;
            } else {
                $has_properties_tab = false;

            }

            $smarty->assign('has_properties_tab', $has_properties_tab);


            $html = $smarty->fetch('webpage.preview.product.tpl');
            break;
    }

}


?>
