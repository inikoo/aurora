<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 August 2018 at 11:40:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$tab     = 'invoices.categories';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'invoice_categories';

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
    'subject'    => 'invoice',
);

$table_buttons   = array();

/*
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New category'),
    'reference' => "suppliers/category/new"
);
*/
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


?>
