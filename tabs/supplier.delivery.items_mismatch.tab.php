<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 October 2018 at 22:25:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$ar_file = 'ar_suppliers_tables.php';


$tab  = 'supplier.delivery.items_mismatch';
$tipo = 'delivery.items_mismatch';

$table_views = array(
    'overview' => array('label' => _('Description')),

);


$default = $user->get_tab_defaults($tab);


$table_filters = array(
    'code' => array('label' => _('Code')),
    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons = array();

$smarty->assign('table_buttons', $table_buttons);

$smarty->assign(
    'table_metadata', base64_encode(
                        json_encode(
                            array(
                                'parent'     => $state['object'],
                                'parent_key' => $state['key']
                            )
                        )
                    )
);

$smarty->assign('dn', $state['_object']);


include 'utils/get_table_html.php';


?>
