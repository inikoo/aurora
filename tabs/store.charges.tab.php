<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 September 2017 at 16:01:11 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'store.charges';
$ar_file = 'ar_products_tables.php';
$tipo    = 'charges';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
  

);

$table_filters = array(
    'name'         => array(
        'label' => _('Name'),
        'title' => _('Name')
    )

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New charge'),
    'reference' => "store/".$state['key']."/charge/new"
);
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';


?>
