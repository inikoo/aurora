<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 April 2016 at 12:02:33 GMT+8, Lovina, Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='suppliers';
$ar_file='ar_suppliers_tables.php';
$tipo='suppliers';

$default=$user->get_tab_defaults($tab);


$table_views=array(
	'overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),
	'contact'=>array('label'=>_('Contact'), 'title'=>_('Contact details')),
	'parts'=>array('label'=>_("Parts's stock")),
	'sales'=>array('label'=>_("Parts's sales")),
	'orders'=>array('label'=>_('Orders'), 'title'=>_('Purchase orders, deliveries & invoices')),



);

$table_filters=array(
	'name'=>array('label'=>_('Name'), 'title'=>_('Supplier name')),
	'email'=>array('label'=>_('Email'), 'title'=>_('Supplier email')),
	'company_name'=>array('label'=>_('Company name'), 'title'=>_('Company name')),
	'contact_name'=>array('label'=>_('Contact name'), 'title'=>_('Contact name'))

);

$parameters=array(
	'parent'=>$state['object'],
	'parent_key'=>$state['key']

);

$table_buttons=array();

$table_buttons[]=array('icon'=>'plus', 'title'=>_('New supplier'), 'reference'=>"agent/".$state['key']."/supplier/new");

$table_buttons[]=array(
	'icon'=>'link',
	'title'=>_('Associate supplier'),
	'id'=>'new_record',
	'inline_new_object'=>
	array(
		'field_id'=>'Supplier_Code',
		'field_label'=>_('Associate supplier').':',
		'field_edit'=>'string',
		'object'=>'Agent_Supplier',
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
		'placeholder'=>_("Supplier's code")
	)

);


$smarty->assign('table_buttons', $table_buttons);

$smarty->assign('js_code', 'js/injections/agent.suppliers.'.(_DEVEL?'':'min.').'js');

include 'utils/get_table_html.php';


?>
