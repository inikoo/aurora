<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 September 2016 at 21:52:18 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'locations.categories';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'categories';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'label' => array(
        'label' => _('Label'),
        'title' => _('Category label')
    ),
    'code'  => array(
        'label' => _('Code'),
        'title' => _('Category code')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
    'subject'    => 'location',
);

$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New category'),
    'reference' => "warehouse/".$state['parent_key']."/category/new"
);

$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


?>
