<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 January 2018 at 19:18:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$tab     = 'customers_poll.query.options';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'poll_query_options';

$default = $user->get_tab_defaults($tab);

$table_views   = array();
$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Code')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$table_buttons   = array();

$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New option'),
    'reference' => "customers/".$state['parent_key']."/poll_query/".$state['key'].'/option/new'
);
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


?>
