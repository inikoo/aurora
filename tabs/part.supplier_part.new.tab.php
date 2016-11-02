<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 31 August 2016 at 12:52:16 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/invalid_messages.php';


include_once 'conf/object_fields.php';
include_once 'class.SupplierPart.php';


$supplier_part = new SupplierPart(0);

$object_fields = get_object_fields(
    $supplier_part, $db, $user, $smarty, array(
        'parent' => 'part',
        'parent_object' => $state['_parent']
    )
);

$smarty->assign('state', $state);
$smarty->assign('object', $supplier_part);

$smarty->assign('object_name', $supplier_part->get_object_name());
$smarty->assign('object_fields', $object_fields);


$available_barcodes = 0;
$sql                = sprintf(
    "SELECT count(*) AS num FROM `Barcode Dimension` WHERE `Barcode Status`='Available'"
);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $available_barcodes = $row['num'];
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

$smarty->assign('available_barcodes', $available_barcodes);


$html = $smarty->fetch('new_object.tpl');

?>
