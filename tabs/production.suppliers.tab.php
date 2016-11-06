<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2016 at 16:29:03 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'production.suppliers';
$ar_file = 'ar_production_tables.php';
$tipo    = 'suppliers';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Supplier code')
    ),
    'name' => array(
        'label' => _('Name'),
        'title' => _('Supplier name')
    ),

);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => '1',

);

$table_buttons = array();

//$table_buttons[]=array('icon'=>'plus', 'title'=>_('New supplier'), 'reference'=>"suppliers/new");
$smarty->assign('table_buttons', $table_buttons);

include 'utils/get_table_html.php';


?>
