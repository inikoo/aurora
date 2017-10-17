<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 October 2017 at 17:30:11 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';

$tab     = 'inventory.parts_barcode_errors.wget';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'parts_barcode_errors';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array('label' => _('Reference')),

);


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);

$validation_messages=get_invalid_message('barcode_ean');

$smarty->assign('validation_messages', json_encode($validation_messages));

$smarty->assign('table_top_template', 'js/inventory.parts_barcode_errors.js.tpl');


include 'utils/get_table_html.php';


?>
