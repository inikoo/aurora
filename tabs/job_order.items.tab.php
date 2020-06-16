<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  15 June 2020  17:32::55  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'job_order.items';

switch ($state['_object']->get('Purchase Order State')) {
    case 'InProcess':
        $tab = 'job_order.items_in_process';

        $table_views = array(
            'ordering' => array('label' => _('Ordering cartons'),),
        );
        break;
    default:
        $tab = 'job_order.items';

        $table_views = array(
            'overview' => array('label' => _('Overview')),
            'properties' => array('label' => _('Properties')),
        );
        break;
}


$smarty->assign('job_order', $state['_object']);


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


include 'utils/get_table_html.php';

