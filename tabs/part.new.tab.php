<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 February 2016 at 19:04:00 GMT+8 Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';


include_once 'conf/object_fields.php';
include_once 'class.SupplierPart.php';
include_once 'class.Part.php';

$object_fields = get_object_fields(
    $state['_object'], $db, $user, $smarty, array(
        'new' => true,
        'part_scope' => true
    )
);


$smarty->assign('state', $state);
$smarty->assign('object', $state['_object']);


$smarty->assign('object_name', $state['_object']->get_object_name());


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
