<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2018 at 20:26:16 GMT+8, Sanur, Bali, Indonesia 
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'client_order.suppliers';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'client_order.suppliers';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Name'),
        'title' => _('Name')
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



include 'utils/get_table_html.php';


?>
