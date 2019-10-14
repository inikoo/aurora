<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 August 2018 at 13:43:04 GMT+8, Legian, Bali, Kuala Lumpur
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'agent_supplier_order.items';
$ar_file = 'ar_agents_tables.php';
$tipo    = 'agent_supplier_order.items';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Ordered quantity').'/'._('Operations'),
    ),
    'properties' => array(
        'label' => _('Barcodes').'/'._('Materials'),
    ),

);

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





$smarty->assign('table_top_template', 'agent.order.edit.tpl');


include 'utils/get_table_html.php';


