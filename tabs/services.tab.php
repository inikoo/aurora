<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 September 2016 at 19:44:58 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'store.services';
$ar_file = 'ar_products_tables.php';
$tipo    = 'services';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'sales'    => array(
        'label' => _('Sales'),
        'title' => _('Sales')
    ),

);

$table_filters = array(
    'code' => array('label' => _('Code')),
    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);


$table_buttons = array();

//$table_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'),'id'=>'edit_table');

if ($state['parent'] == 'store') {
    $table_buttons[] = array(
        'icon'      => 'plus',
        'title'     => _('New service'),
        'reference' => "services/".$state['store']->id."/new"
    );
}
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


?>
