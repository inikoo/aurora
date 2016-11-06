<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2016 at 12:09:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'part.sales.history';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'sales_history';

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


include 'utils/get_table_html.php';


?>
