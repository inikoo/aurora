<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 April 2016 at 15:28:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

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
$h = 23;


include "external_libs/mpdf/mpdf.php";

$mpdf = new mPDF(
    'utf-8', array(
    $w,
    $h
), '', '', 0, 0, 0, 0, 0, 0
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
exit;


$number = $_REQUEST['number'];

if (!is_numeric($number)) {
    exit;

}


if (isset($_REQUEST['scale']) and is_numeric($_REQUEST['scale'])) {
    $scale = ceil($_REQUEST['scale']);
} else {
    $scale = null;
}

include_once 'external_libs/barcodes/ean.php';

$ean = new EAN($number, $scale);

$ean->display();


?>
