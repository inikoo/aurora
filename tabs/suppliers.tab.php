<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2015 17:37:16 GMT+7, Bangkok, Thailand
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
	'sales_q'=>array('label'=>_("Parts's sales (Qs)")),
	'sales_y'=>array('label'=>_("Parts's sales (Yrs)")),
	'orders'=>array('label'=>_('Orders'), 'title'=>_('Purchase orders, deliveries & invoices')),
);

$table_filters=array(
	'name'=>array('label'=>_('Name'), 'title'=>_('Supplier name')),
	'email'=>array('label'=>_('Email'), 'title'=>_('Supplier email')),
	'company_name'=>array('label'=>_('Company name'), 'title'=>_('Company name')),
	'contact_name'=>array('label'=>_('Contact name'), 'title'=>_('Contact name'))

);

$parameters=array(
	'parent'=>'account',
	'parent_key'=>'1',

);

$table_buttons=array();

//$table_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'),'id'=>'edit_table');

$table_buttons[]=array('icon'=>'plus', 'title'=>_('New supplier'), 'reference'=>"suppliers/new");

$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';


?>
