<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 August 2016 at 13:08:15 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'agent.client_orders';
$ar_file = 'ar_agents_tables.php';
$tipo    = 'agent_client_orders';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array(
        'label' => _('Number'),
        'title' => _('Order number')
    ),
);

$parameters = array(
    'parent'     => 'Agent',
    'parent_key' => $user->get('User Parent Key')

);


$table_buttons = array();


$smarty->assign('table_buttons', $table_buttons);

include 'utils/get_table_html.php';

?>
