<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 January 2017 at 17:16:28 GMT, Sheffield UK
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'inventory.parts_no_sko_barcode.wget';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'parts_no_sko_barcode';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array('label' => _('Reference')),

);


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);

$smarty->assign('table_top_template', 'js/inventory.parts_no_sko_barcode.tpl.js');


include 'utils/get_table_html.php';


?>
