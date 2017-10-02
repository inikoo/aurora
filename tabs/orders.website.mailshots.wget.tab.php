<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 September 2017 at 15:45:18 GMT+8, , Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'orders.website.mailshots';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'orders_in_website_mailshots';


$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'id' => array('label' => _('Id')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);



$smarty->assign('table_top_template', 'orders_website_tabs.tpl');

$table_buttons   = array();

if( $state['parent']=='store') {

    $table_buttons[] = array(
        'icon'      => 'plus',
        'title'     => _('New mailshot'),
        'id'=>'new_orders_in_website_mailshot',
        'attr'=>array(
            'parent'=>$state['parent'],
            'parent_key'=>$state['parent_key'],

        )

    );

}
$smarty->assign('table_buttons', $table_buttons);


$smarty->assign(
    'js_code', 'js/injections/orders.website.mailshots.'.(_DEVEL ? '' : 'min.').'js'
);



include('utils/get_table_html.php');


?>
