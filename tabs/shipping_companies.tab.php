<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2018 at 11:04:33 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'shippers';
$ar_file = 'ar_delivery_notes_tables.php';
$tipo    = 'shippers';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code' => array('label' => _('Code'))

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);



$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New shipping company'),
    'reference' => "shippers/new"
);
$smarty->assign('table_buttons', $table_buttons);

include('utils/get_table_html.php');

