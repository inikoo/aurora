<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 April 2016 at 22:02:23 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';


$supplier_part = $state['_object'];


$object_fields_supplier_part = get_object_fields(
    $supplier_part, $db, $user, $smarty, array(
        'show_full_label' => true,
        'parent'          => 'supplier',
        'parent_object'   => $state['_parent']
    )
);


$available_barcodes = 0;
$sql                = sprintf("SELECT count(*) AS num FROM `Barcode Dimension` WHERE `Barcode Status`='Available'");
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $available_barcodes = $row['num'];
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

$smarty->assign('available_barcodes', $available_barcodes);


$part = $state['_object']->part;


$smarty->assign(
    'preferred_countries', '"'.join(
        '", "', preferred_countries(
            ($part->get('Part Origin Country Code') == '' ? $account->get(
                'Account Country 2 Alpha Code'
            ) : $part->get('Part Origin Country Code'))
        )
    ).'"'
);

$smarty->assign('object_fields', $object_fields_supplier_part);
$smarty->assign('state', $state);

$smarty->assign('js_code', 'js/injections/supplier_part_details.'.(_DEVEL ? '' : 'min.').'js');

$html = $smarty->fetch('edit_object.tpl');

?>
