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


if (!in_array($object_name, array('part'))) {
    exit('error 1');
}

if (!in_array(
    $type, array(
             'package',
             'unit'
         )
)
) {
    exit('error 1');
}

$object = get_object($object_name, $key);


//============
$w = 50;
$h = 25;



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


$mpdf->SetTitle('Label '.$object->get_name().' '.$object->id);
$mpdf->SetAuthor('Aurora Systems');


if ($object_name == 'part') {

    $smarty->assign('account', $account);

    $smarty->assign('part', $object);
    $html = $smarty->fetch('labels/part_'.$type.'.tpl');
}
$mpdf->WriteHTML($html);

$mpdf->Output();













?>
