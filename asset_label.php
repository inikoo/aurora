<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 April 2016 at 15:28:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
require_once __DIR__.'/vendor/autoload.php';

include_once 'common.php';
include_once 'utils/object_functions.php';

if (!isset($_REQUEST['object']) or !isset($_REQUEST['key']) or !isset($_REQUEST['type'])) {
    exit;

}


$object_name = $_REQUEST['object'];
$key         = $_REQUEST['key'];
$type        = $_REQUEST['type'];


if (!in_array($object_name, array('part','supplier_part','product'))) {
    exit('error 1');
}

if (!in_array(
    $type, array(
             'package',
             'unit',
             'carton',
             'unit_barcode',
             'unit_ingredients'
         )
)
) {
    exit('error 2');
}

$object = get_object($object_name, $key);



if($type=='carton'){
    $w = 100;
    $h = 70;
}else{
    $w = 65;
    $h = 27;
}

$smarty->assign('account', $account);


if ($object_name == 'product') {
    $store=get_object('Store',$object->get('Store Key'));


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

    $filename=$object->get('Code');

    if($type=='ingredients'){
        $filename.='_'.$type;
    }


    $template='labels/product_'.$type.'.tpl';



}elseif ($object_name == 'part') {
    $smarty->assign('part', $object);
    $template='labels/part_'.$type.'.tpl';

    $filename=$object->get('Reference');
    if($type=='package'){
        $filename.='_'.$type;
    }


}elseif ($object_name == 'supplier_part') {
    $account=get_object('Account',1);
    $smarty->assign('account', $account);
    $smarty->assign('supplier_part', $object);


    $filename=$object->get('Reference').'_carton';

    $template='labels/supplier_part_'.$type.'.tpl';
}

//============




$mpdf = new \Mpdf\Mpdf(
    [
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
    ]
);

$mpdf->repackageTTF = false;

$mpdf->SetTitle('Label '.$object->get_name().' '.$object->id);
$mpdf->SetAuthor('Aurora Systems');
$smarty->assign('account', $account);


$html = $smarty->fetch($template);


$mpdf->WriteHTML($html);

$mpdf->Output($filename.'.pdf', 'I');

