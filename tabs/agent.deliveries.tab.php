<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 July 2016 at 22:09:23 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016 Inikoo

 Version 3

*/

$tab     = 'agent.deliveries';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'agent_deliveries';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array('label' => _('Number')),
);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => ''

);



$table_buttons[] = array(
    'icon'  => 'plus',
    'title' => _('New delivery'),
    'id'    => 'new_agent_delivery',
    'attr'  => array(
        'parent'     => 'agent',
        'parent_key' => $user->get('User Parent Key')

)


);


$smarty->assign('table_buttons', $table_buttons);
$smarty->assign('js_code', 'js/injections/agent.deliveries.'.(_DEVEL ? '' : 'min.').'js');

include 'utils/get_table_html.php';

?>
