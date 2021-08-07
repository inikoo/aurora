<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 April 2016 at 15:28:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

use Mpdf\Mpdf;

/** @var Smarty $smarty */
/** @var Account $account */


include_once __DIR__.'/common.php';

if (!isset($_REQUEST['object']) or !isset($_REQUEST['key']) or !isset($_REQUEST['type'])) {
    exit;
}


$object_name = $_REQUEST['object'];
$key         = $_REQUEST['key'];
$type        = $_REQUEST['type'];


if (!in_array($object_name, array('part', 'supplier_part', 'product', 'fulfilment_asset'))) {
    exit('error 1');
}

if (!in_array($type, array(
                       'package',
                       'package_with_image',
                       'unit',
                       'unit_with_image',
                       'carton',
                       'carton_with_image',
                       'unit_barcode',
                       'unit_barcode_with_image',
                       'unit_ingredients',
                       'unit_EL30',
                       'unit_30UP',
                       'unit_EP40sp',
                       'unit_5x15',
                       'unit_6x18',


                   ))) {
    exit('error 2');
}

$object = get_object($object_name, $key);


if ($type == 'carton' or $type == 'carton_with_image') {
    $w = 100;
    $h = 70;
} elseif ($type == 'unit_barcode_with_image' or $type == 'unit_with_image' or $type == 'package_with_image') {
    $w = 120;
    $h = 60;
} else {
    $w = 65;
    $h = 27;
}

$smarty->assign('account', $account);

$title    = '';
$template = '';
$filename = '';
if ($object_name == 'product') {
    $store = get_object('Store', $object->get('Store Key'));


    if (!empty($_REQUEST['locale'])) {
        $_locale = $_REQUEST['locale'];
    } else {
        $_locale = $store->get('Store Locale');
    }


    putenv('LC_ALL='.$_locale.'.UTF-8');
    setlocale(LC_ALL, $_locale.'.UTF-8');
    bindtextdomain("inikoo", "./locales");
    textdomain("inikoo");


    $smarty->assign('store', $store);

    $smarty->assign('product', $object);

    $filename = $object->get('Code');

    if ($type == 'unit_ingredients') {
        $filename .= '_'.$type;

        $title = sprintf(_('%s ingredients'), $object->get('Code'));
    } else {
        $title = sprintf(_('%s barcode'), $object->get('Code'));
    }


    $template = 'labels/product_'.$type.'.tpl';
} elseif ($object_name == 'part') {
    $smarty->assign('part', $object);
    $template = 'labels/part_'.$type.'.tpl';

    $filename = $object->get('Reference');
    if ($type == 'package') {
        $filename .= '_'.$type;
        $title    = sprintf(_('%s SKO'), $object->get('Code'));
    } elseif ($type == 'unit_EL30' or $type == 'unit_30UP') {
        $w = 210;
        $h = 297;

        $title = sprintf(_('%s unit'), $object->get('Code'));
    } elseif ($type == 'unit_EP40sp') {
        $h = 210;
        $w = 297;

        $title = sprintf(_('%s unit'), $object->get('Code'));
    } elseif ($type == 'unit_5x15' or $type == 'unit_6x18') {
        $w = 210;
        $h = 297;

        $title = sprintf(_('%s unit'), $object->get('Code'));
    } else {
        $title = sprintf(_('%s unit'), $object->get('Code'));
    }
} elseif ($object_name == 'supplier_part') {
    $account = get_object('Account', 1);
    $smarty->assign('account', $account);
    $smarty->assign('supplier_part', $object);


    $filename = $object->get('Reference').'_carton';
    $title    = sprintf(_('%s carton'), $object->get('Code'));


    $template = 'labels/supplier_part_'.$type.'.tpl';
}


try {
    $mpdf               = new Mpdf([
                                       'tempDir'       => __DIR__.'/server_files/pdf_tmp',
                                       'mode'          => 'utf-8',
                                       'format'        => [
                                           $w,
                                           $h
                                       ],
                                       'margin_left'   => 0,
                                       'margin_right'  => 0,
                                       'margin_top'    => 0,
                                       'margin_bottom' => 0,
                                       'margin_header' => 0,
                                       'margin_footer' => 0
                                   ]);
    $mpdf->repackageTTF = false;
    $mpdf->SetTitle($title);
    $mpdf->SetAuthor('Aurora Systems');
    $smarty->assign('account', $account);


    $html = $smarty->fetch($template);


    $mpdf->WriteHTML($html);
    $mpdf->Output($filename.'.pdf', 'I');
} catch (Exception $e) {
}


