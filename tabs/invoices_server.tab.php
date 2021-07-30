<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  28 December 2015 at 09:58:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
/** @var User $user */
/** @var Smarty $smarty */
/** @var \Account $account */
/** @var array $state */

$tab     = 'invoices_server';
$ar_file = 'ar_accounting_tables.php';
$tipo    = 'invoices';


if ($account->get('Account Warehouses') == 0) {

    $html = '<div style="padding:20px">'.sprintf(_('Warehouse missing, set it up %s'), '<span class="marked_link" onClick="change_view(\'/warehouse/new\')" >'._('here').'</span>').'</div>';

    return;
}

if ($account->get('Account Stores') == 0) {

    $html = '<div style="padding:20px">'.sprintf(_('There are not stores, create one %s'), '<span class="marked_link" onClick="change_view(\'/store/new\')" >'._('here').'</span>').'</div>';

    return;
}


$default = $user->get_tab_defaults($tab);

$table_views = array();


$table_filters = array(
    'customer' => array(
        'label' => _('Customer'),
        'title' => _('Customer name')
    ),
    'number'   => array(
        'label' => _('Number'),
        'title' => _('Invoice number')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
    'version'    => 'v2'
);


$smarty->assign('title', _('Invoices').' ('._('All stores').')');
include 'utils/get_table_html.php';

