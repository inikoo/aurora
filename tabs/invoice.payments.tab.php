<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 November 2017 at 20:26:01 GMT+88, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


if ($state['_object']->get('Invoice Type') == 'Refund') {

    $tab     = 'refund.payments';
    $ar_file = 'ar_accounting_tables.php';
    $tipo    = 'refund.payments';

    $smarty->assign('payback_refund', $state['_object']->id);




} else {
    $tab     = 'invoice.payments';
    $ar_file = 'ar_accounting_tables.php';
    $tipo    = 'invoice.payments';




}

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Reference')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$smarty->assign(
    'js_code', 'js/injections/edit_payments.'.(_DEVEL ? '' : 'min.').'js'
);

$smarty->assign('table_top_template', 'edit_payments_dialogs.tpl');

include('utils/get_table_html.php');


?>
