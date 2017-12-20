<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 December 2017 at 15:52:08 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'common.php';
include_once 'utils/object_functions.php';


if (!isset($_REQUEST['key'])) {
    exit;

}


$key = $_REQUEST['key'];


$object = get_object('location', $key);

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
$mpdf->SetTitle(_('Location').': '.$object->get('Code'));
$mpdf->SetAuthor('Aurora Systems');


$smarty->assign('account', $account);

$smarty->assign('location', $object);
$html = $smarty->fetch('labels/location.tpl');


$mpdf->WriteHTML($html);

$mpdf->Output();


?>
