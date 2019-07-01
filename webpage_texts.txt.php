<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2017 at 15:20:42 GMT+8, Kuala Lumpur, Malaysis
 Copyright (c) 2017, Inikoo

 Version 3

*/


require_once 'common.php';
require_once 'utils/object_functions.php';

function br2nl($input) {
    return preg_replace('/<br\s?\/?>/ius', "\n", str_replace("\n", "", str_replace("\r", "", htmlspecialchars_decode($input))));
}


function get_product_text($product) {


    $product->get_webpage();
    $webpage      = $product->webpage;
    $content_data = $webpage->get('Content Data');


    $text = "**************************\n";

    $text .= $product->get('Code')." ("._('product').")\n";
    $text .= $product->get('Name')."\n";


    $text .= "\n";


    if (isset($content_data['blocks'])) {
        foreach ($content_data['blocks'] as $key => $data) {
            if ($data['type'] == 'product') {
                $text .= strip_tags(br2nl($data['text']))."\n";
            }
        }
    }


    $text .= "\n";


    $origin     = $product->get('Origin Country');
    $cpnp       = $product->get('CPNP Number');
    $materials  = $product->get('Materials');
    $weight     = $product->get('Unit Weight');
    $dimensions = $product->get('Unit Dimensions');
    $barcode    = $product->get('Barcode Number');

    if ($origin) {
        $text .= _('Origin').': '.$origin."\n";
    }
    if ($cpnp) {
        $text .= _('CPNP').': '.$cpnp."\n";
    }
    if ($materials) {
        $text .= _('Material').': '.strip_tags($materials)."\n";
    }
    if ($weight) {
        $text .= _('Weight').': '.$weight."\n";
    }

    if ($dimensions) {
        $text .= _('Dimensions').': '.$dimensions."\n";
    }
    if ($barcode) {
        $text .= _('Barcode').': '.$barcode."\n";
    }

    $text .= "\n";


    return $text;


}


function get_category_text($db,$category) {


    $category->get_webpage();
    $webpage      = $category->webpage;
    $content_data = $webpage->get('Content Data');


    $text = "**************************\n";

    $text .= $category->get('Code')." ("._('family').")\n";
    $text .= $category->get('Label')."\n";

    $text .= "\n";



   // print_r($content_data);

    foreach ($content_data['blocks'] as $_key => $block) {
        if ($block['type'] == 'blackboard' ) {


            foreach ($content_data['blocks'][$_key]['texts'] as $_key2 => $text_data) {
                if ($block['type'] == 'blackboard' ) {


                    $text .= strip_tags(br2nl($content_data['blocks'][$_key]['texts'][$_key2]['text']))."\n";
                }

            }
        }

    }






    if ($category->get('Category Subject') == 'Product') {

        $sql = sprintf(
            "SELECT P.`Product ID` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)  WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State`,   ifnull(`Product Category Index Stack`,99999999)",
            $category->id
        );


        if ($result = $db->query($sql)) {

            foreach ($result as $row) {

                $product = get_object('Product', $row['Product ID']);
                $text    .= get_product_text($product);


            }


        }
    }
    $text .= "\n";

    return $text;


}


if (empty($_REQUEST['parent']) or empty($_REQUEST['key'])) {
    exit;
}

$files = array();

$object = get_object($_REQUEST['parent'], $_REQUEST['key']);


if ($object->get_object_name() == 'Category') {
    $text = get_category_text($db,$object);
} elseif ($object->get_object_name() == 'Product') {
    $text = get_product_text($object);
}


$download_name = 'webpage_text_'.strtolower($object->get('Code'));

$text = preg_replace("/[\r\n]+/", "\n", $text);

$text = wordwrap($text, 120, "\n", true);
$text = preg_replace("/\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*/", "\n**************************", $text);

$text = html_entity_decode($text);

$text = preg_replace("/\&apos\;/", "'", $text);


//header('Content-disposition: attachment; filename='.$download_name.'.txt');
//header("Content-Type: text/plain");
print $text;


