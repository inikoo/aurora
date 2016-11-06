<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 January 2016 at 13:03:43 GMT+8, Kuala Lumpur, Malaysis
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'timeserie.records';
$ar_file = 'ar_account_tables.php';
$tipo    = 'timeserie_records';

$default = $user->get_tab_defaults($tab);


/*
include 'conf/export_fields.php';
$default['export_fields']=$export_fields['timeserie_records_'.$state['_object']->get('Type')];
if ($state['_object']->get('Type')=='StoreSales') {
	$default['export_fields'][1]['label'].=' '.$state['_object']->parent->get('Currency Code');
	$default['export_fields'][2]['label'].=' '.$account->get('Currency');
	if ($state['_object']->parent->get('Currency Code')==$account->get('Currency')) {
		unset($default['export_fields'][2]);
	}
}
*/
$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);

if ($state['_object']->get('Type') == 'StoreSales') {
    $columns_parameters = array(
        'a'     => array(
            'render' => 'true',
            'label'  => _('Sales Net')
        ),
        'b'     => array(
            'render' => ($state['_object']->parent->get(
                'Currency Code'
            ) != $account->get('Currency') ? 'true' : 'false'),
            'label'  => _(
                    'Sales Net'
                ).' '.$account->get('Currency')
        ),
        'c'     => array(
            'render' => 'false',
            'label'  => ''
        ),
        'd'     => array(
            'render' => 'false',
            'label'  => ''
        ),
        'int_a' => array(
            'render' => 'true',
            'label'  => _('Invoices')
        ),
        'int_b' => array(
            'render' => 'true',
            'label'  => _('Refunds')
        ),

    );
} elseif ($state['_object']->get('Type') == 'PartCategorySales') {
    $columns_parameters = array(
        'a'     => array(
            'render' => 'true',
            'label'  => _('Sold Amount')
        ),
        'b'     => array(
            'render' => 'false',
            'label'  => ''
        ),
        'c'     => array(
            'render' => 'false',
            'label'  => ''
        ),
        'd'     => array(
            'render' => 'false',
            'label'  => ''
        ),
        'int_a' => array(
            'render' => 'true',
            'label'  => _('Deliveries')
        ),
        'int_b' => array(
            'render' => 'true',
            'label'  => _('SKOs')
        ),

    );
} else {
    $columns_parameters = array(
        'a'     => array(
            'render' => 'true',
            'label'  => 'a'
        ),
        'b'     => array(
            'render' => 'true',
            'label'  => 'b'
        ),
        'c'     => array(
            'render' => 'true',
            'label'  => 'c'
        ),
        'd'     => array(
            'render' => 'true',
            'label'  => 'd'
        ),
        'int_a' => array(
            'render' => 'true',
            'label'  => 'a'
        ),
        'int_b' => array(
            'render' => 'true',
            'label'  => 'b'
        ),

    );
}

include 'utils/get_table_html.php';


?>
