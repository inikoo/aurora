<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 April 2016 at 22:02:23 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';
/** @var array $state */
/** @var \PDO $db */
/** @var \User $user */
/** @var \Smarty $smarty */
/** @var \Account $account */


$supplier_part = $state['_object'];


$object_fields_supplier_part = get_object_fields(
    $supplier_part,
    $db,
    $user,
    $smarty,
    array(
        'show_full_label' => true,
        'parent'          => 'supplier',
        'parent_object'   => $state['_parent']
    )
);


$available_barcodes = 0;
$sql                = "SELECT count(*) AS num FROM `Barcode Dimension` WHERE `Barcode Status`='Available'";
$stmt               = $db->prepare($sql);
$stmt->execute();
if ($row = $stmt->fetch()) {
    $available_barcodes = $row['num'];
}


$smarty->assign('available_barcodes', $available_barcodes);


$part = $state['_object']->part;

$smarty->assign(
    'preferred_countries',
    '"'.join(
        '", "',
        preferred_countries(
            ($part->get('Part Origin Country Code') == '' ? $account->get('Account Country 2 Alpha Code') : $part->get('Part Origin Country Code'))
        )
    ).'"'
);

$smarty->assign('object_fields', $object_fields_supplier_part);
$smarty->assign('state', $state);

$smarty->assign('js_code', 'js/injections/supplier_part_details.'.(_DEVEL ? '' : 'min.').'js');

try {
    $html = $smarty->fetch('edit_object.tpl');
} catch (Exception $e) {
}


