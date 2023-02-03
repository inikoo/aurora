<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 April 2016 at 15:28:24 GMT+8, Kuala Lumpur, Malaysia
 Refurbished:  01 May 2020  02:19::55  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2016-2020, Inikoo

 Version 3

*/

/** @var Smarty $smarty */
/** @var Account $account */

use Mpdf\Mpdf;
use Mpdf\MpdfException;

include_once 'utils/labels_data.php';

if (!isset($_REQUEST['object']) or !isset($_REQUEST['key']) or !isset($_REQUEST['type'])) {
    exit('missing basic args');
}

include_once 'common.php';
/** @var User $user */
if ($user->get('User View') != 'Staff') {
    exit;
}



$object_name = $_REQUEST['object'];
$key         = $_REQUEST['key'];

$type = '';
if (isset($_REQUEST['type'])) {
    $type = $_REQUEST['type'];
}
$size = '';
if (isset($_REQUEST['size'])) {
    $size = $_REQUEST['size'];
}

$set_up = '';
if (isset($_REQUEST['set_up'])) {
    $set_up = $_REQUEST['set_up'];
}



if (!in_array(
    $object_name, array(
                    'part',
                    'supplier_part',
                    'product',
                    'fulfilment_asset'
                )
)) {
    exit('error 1');
}


if (!in_array(
    $type, array(
             'sko',
             'unit',
             'carton',
             'pallet',
             'box',
             'unit_ingredients'


         )
)) {
    exit('error 2');
}



if (isset($_REQUEST['set_up']) and $_REQUEST['set_up'] == 'single') {
    $set_up = 'single';
} else {
    $set_up = 'sheet';
}

$object = get_object($object_name, $key);



if ( $size == '' ) {
    if ($object_name == 'fulfilment_asset') {

        /** @var Fulfilment_Asset  $object */

        if(isset($object->get_labels_data()[$type])){
            $label_data =$object->get_labels_data()[$type];
            $size=$label_data['size'];
            $set_up=$label_data['set_up'];
        }else{
            exit('error 4');
        }



    }


    if($set_up==''){
        $set_up = 'sheet';
    }


}

if (!in_array(
    $size, array(
             'EU30161',
             'EU30040',
             'EU30090',
             'EU30036',
             'EU30137',
             'EU30140',
             'EU30129',
             'A4',
             'SK06302900',
             'ES0027D'


         )
)) {
    exit('wrong size '.$size);
}
$smarty->assign('size', $size);

$label_data = get_label_data($size);




$smarty->assign('set_up', $set_up);

if (isset($_REQUEST['with_image']) and $_REQUEST['with_image'] == 'true') {
    $with_images = true;
} else {
    $with_images = false;
}
$smarty->assign('with_images', $with_images);

if (isset($_REQUEST['with_origin']) and $_REQUEST['with_origin'] == 'true') {
    $with_origin = true;
} else {
    $with_origin = false;
}
$smarty->assign('with_origin', $with_origin);

if (isset($_REQUEST['with_manufactured_by']) and $_REQUEST['with_manufactured_by'] == 'true') {
    $with_manufactured_by = true;
} else {
    $with_manufactured_by = false;
}
$smarty->assign('with_manufactured_by', $with_manufactured_by);

if (isset($_REQUEST['with_weight']) and $_REQUEST['with_weight'] == 'true') {
    $with_weight = true;
} else {
    $with_weight = false;
}
$smarty->assign('with_weight', $with_weight);


if (isset($_REQUEST['with_ingredients']) and $_REQUEST['with_ingredients'] == 'true') {
    $with_ingredients = true;
} else {
    $with_ingredients = false;
}
$smarty->assign('with_ingredients', $with_ingredients);


if (isset($_REQUEST['with_custom_text']) and $_REQUEST['with_custom_text'] == 'true') {
    $with_custom_text = true;
} else {
    $with_custom_text = false;
}
$smarty->assign('with_custom_text', $with_custom_text);

if (isset($_REQUEST['with_account_signature']) and $_REQUEST['with_account_signature'] == 'true') {
    $with_account_signature = true;
} else {
    $with_account_signature = false;
}
$smarty->assign('with_account_signature', $with_account_signature);


$custom_text = $_REQUEST['custom_text'] ?? '';
$smarty->assign('custom_text', $custom_text);


$smarty->assign('label_data', $label_data);



/*

if($set_up=='sheet'){
    $w = 210;
    $h = 297;

    switch ($size){
        case 'EU30161':
            $top_margin = 15.3;
            $left_margin = 7.75;
            break;
        case 'EU30040':
            $top_margin = 0;
            $left_margin = 0;
            break;
        case 'EU30036':
            $top_margin = 0;
            $left_margin = 0;
            break;
        case 'EU30090':
            $top_margin = 6;
            $left_margin = 6.5;
            break;
    }


}else{
    $top_margin=0;
    $left_margin=0;
    switch ($size){
        case 'EU30161':
            $w = 63.5;
            $h = 29.6;

            break;
        case 'EU30040':
            $w = 70;
            $h = 29.7;

            break;
        case 'EU30090':
            $w = 97;
            $h = 69;
            break;
        case 'EU30036':
            $w =105;
            $h = 74;
            break;
    }
}

*/


/*

if ($type == 'carton' or $type == 'carton_with_image') {
    $w = 100;
    $h = 70;
} elseif ($type == 'unit_barcode_with_image' or $type == 'unit_with_image' or $type == 'package_with_image') {
    $w = 120;
    $h = 60;
} else {
    //$w = 65;
    //$h = 27;
    $w = 63.5;
    $h = 29.6;
}
*/
$smarty->assign('account', $account);


$_locale = $account->get('Account Locale');
putenv('LC_ALL='.$_locale.'.UTF-8');
setlocale(LC_ALL, $_locale.'.UTF-8');
bindtextdomain("inikoo", "./locales");
textdomain("inikoo");

$title    = '';
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


} elseif ($object_name == 'part') {


    $smarty->assign('part', $object);


    $filename = $object->get('Reference').'_'.$type;
    $title    = sprintf(_('%s unit'), $object->get('Code'));


    if ($type == 'carton') {

        $smarty->assign('supplier_part', get_object('SupplierPart', $object->get('Part Main Supplier Part Key')));

    }


    /*

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


    }
*/

} elseif ($object_name == 'supplier_part') {
    $account = get_object('Account', 1);
    $smarty->assign('account', $account);
    $smarty->assign('supplier_part', $object);


    $filename = $object->get('Reference').'_carton';
    $title    = sprintf(_('%s carton'), $object->get('Code'));


} elseif ($object_name == 'fulfilment_asset') {

    $account = get_object('Account', 1);
    $smarty->assign('account', $account);
    $customer = get_object('Customer', $object->get('Customer Key'));
    $store    = get_object('Store', $customer->get('Store Key'));
    $smarty->assign('store', $store);
    $smarty->assign('customer', $customer);


    $smarty->assign('asset', $object);


    $filename = $object->id.'_asset';
    $title    = sprintf(_('Fulfilment asset %s'), $object->get('Formatted ID'));

    $type = $object_name;
    if ($size == 'A4') {
        $type .= '_A4';
    }

}
$smarty->assign('type', $type);


try {
    $mpdf               = new Mpdf(
        [
            'tempDir'       => __DIR__.'/server_files/pdf_tmp',
            'mode'          => 'utf-8',
            'format'        => [
                ($set_up == 'single' ? $label_data['width'] : $label_data['sheet_width']),
                ($set_up == 'single' ? $label_data['height'] : $label_data['sheet_height'])
            ],
            'margin_left'   => ($set_up == 'single' ? 0 : $label_data['margin_left']),
            'margin_right'  => ($set_up == 'single' ? 0 : $label_data['margin_right']),
            'margin_top'    => ($set_up == 'single' ? 0 : $label_data['margin_top']),
            'margin_bottom' => ($set_up == 'single' ? 0 : $label_data['margin_bottom']),
            'margin_header' => 0,
            'margin_footer' => 0
        ]
    );
    $mpdf->repackageTTF = false;
    $mpdf->SetTitle($title);
    $mpdf->SetAuthor('Aurora Systems');
    $smarty->assign('account', $account);



    $html = $smarty->fetch('labels/label.tpl');

    $mpdf->WriteHTML($html);
    $mpdf->Output($filename.'.pdf', 'I');
} catch (MpdfException | SmartyException $e) {
    print $e->getMessage();
}



