<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 October 2015 at 13:03:57 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='order.items';
$ar_file='ar_orders_tables.php';
$tipo='order.items';

$default=$user->get_tab_defaults($tab);


$table_views=array(
	'overview'=>array('label'=>_('Description'),'title'=>_('Description')),
	'tariff_codes'=>array('label'=>_('Tariff Codes'),'title'=>_('Tariff Codes')),

);

$table_filters=array(
	'code'=>array('label'=>_('Code'),'title'=>_('Product code')),
	'name'=>array('label'=>_('Name'),'title'=>_('Product name')),

);

$parameters=array(
		'parent'=>'order',
		'parent_key'=>$state['key'],
		
);


include('utils/get_table_html.php');


?>
