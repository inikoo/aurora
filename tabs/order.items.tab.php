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
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
		
);

$table_buttons=array();
$table_buttons[]=array(
	'icon'=>'plus',
	'title'=>_('New barcode'),
	'id'=>'new_record',
	'inline_new_object'=>
	array(
		'field_id'=>'Barcode_Range',
		'field_label'=>_('Add barcodes').':',
		'field_edit'=>'barcode_range',
		'object'=>'Barcode',
		'parent'=>$state['parent'],
		'parent_key'=>$state['parent_key'],

	)

);
$smarty->assign('table_buttons', $table_buttons);



include('utils/get_table_html.php');


?>
