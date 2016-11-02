<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 October 2015 at 12:43:25 CEST, Malaga Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';

$part = $state['_object'];

$object_fields = get_object_fields(
    $part, $db, $user, $smarty, array('show_full_label' => false)
);


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


$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);
$smarty->assign(
    'preferred_countries', '"'.join(
        '", "', preferred_countries(
            ($part->get('Part Origin Country Code') == '' ? $account->get(
                'Account Country 2 Alpha Code'
            ) : $part->get('Part Origin Country Code'))
        )
    ).'"'
);

$smarty->assign(
    'js_code', 'js/injections/part_details.'.(_DEVEL ? '' : 'min.').'js'
);


$html = $smarty->fetch('edit_object.tpl');

?>
