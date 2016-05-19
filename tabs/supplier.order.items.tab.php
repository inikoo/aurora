<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 May 2016 at 14:12:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab='supplier.order.items';
$ar_file='ar_suppliers_tables.php';
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
	'title'=>_('New item'),
	'id'=>'new_record',
	'add_item'=>
	array(
		'field_id'=>'Barcode_Range',
		'field_label'=>_('Part Code').':',
		'field_edit'=>'barcode_range',
		'object'=>'Barcode',
		'parent'=>$state['parent'],
		'parent_key'=>$state['parent_key'],

	)

);
$smarty->assign('table_buttons', $table_buttons);



include('utils/get_table_html.php');


?>
