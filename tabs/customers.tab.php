<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2015 18:30:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='customers';
$ar_file='ar_customers_tables.php';
$tipo='customers';

$default=$user->get_tab_defaults($tab);



$table_views=array(
	'overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),
	'contact'=>array('label'=>_('Contact'), 'title'=>_('Contact details')),
	'invoices'=>array('label'=>_('Invoices/Balance'), 'title'=>_('Invoices & Account balance')),
	'weblog'=>array('label'=>_('Weblog'), 'title'=>_('Weblog'))

);

$table_filters=array(
	'name'=>array('label'=>_('Name'), 'title'=>_('Customer name')),
	'email'=>array('label'=>_('Email'), 'title'=>_('Customer email')),
	'company_name'=>array('label'=>_('Company name'), 'title'=>_('Company name')),
	'contact_name'=>array('label'=>_('Contact name'), 'title'=>_('Contact name'))

);

$parameters=array(
	'parent'=>'store',
	'parent_key'=>$state['parent_key'],

);


$table_buttons=array();
$table_buttons[]=array('icon'=>'plus', 'title'=>_('New customer'), 'reference'=>"customers/".$state['parent_key']."/new");
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';


?>
