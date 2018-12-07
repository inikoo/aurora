<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 October 2018 at 21:38:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$ar_file = 'ar_suppliers_tables.php';


if ($state['_object']->get('State Index') ==110) {
    $tab  = 'supplier.delivery.items_done';
    $tipo = 'delivery.items_done';

    $table_views = array(
        'overview' => array('label' => _("Item's descriptions")),

    );

} else {

    $html='delivery not done yet';
    return;

  

}


$default = $user->get_tab_defaults($tab);


$table_filters = array(
    'code' => array('label' => _('Code')),
    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();

$smarty->assign('table_buttons', $table_buttons);

$smarty->assign(
    'table_metadata',
        json_encode(
            array('parent'     => $state['object'],
                  'parent_key' => $state['key']
            )

    )
);

$smarty->assign('delivery', $state['_object']);

$smarty->assign('currency', $state['_object']->get('Supplier Delivery Currency Code'));
$smarty->assign('currency_account', $account->get('Currency Code'));

$smarty->assign('currency_symbol', currency_symbol($account->get('Currency Code')));








include 'utils/get_table_html.php';


?>
