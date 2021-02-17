<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  28 December 2015 at 17:49:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

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
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


$export_omega='No';

if($state['_object']->get('Invoice Category Function Code')=='external_invoicer'){
    $external_invoicer=get_object('External_Invoicer',$state['_object']->get('Invoice Category Function Argument'));
    if($external_invoicer->metadata('country')=='SK'){
        $export_omega='Yes';

    }
}

$smarty->assign('export_omega_invoices',$export_omega);

include 'utils/get_table_html.php';



