<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  24 September 2015 00:35:11 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
/** @var User $user */
/** @var Smarty $smarty */
/** @var \Account $account */
/** @var array $state */

$tab     = 'invoices';
$ar_file = 'ar_accounting_tables.php';
$tipo    = 'invoices';

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
    'version'    => 'v2',
);

$export_omega = 'No';

if ($account->get('Account Country 2 Alpha Code') == 'SK') {
    $export_omega = 'Yes';
}

$smarty->assign('export_omega_invoices', $export_omega);

include 'utils/get_table_html.php';

