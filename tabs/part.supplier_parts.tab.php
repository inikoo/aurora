<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 April 2016 at 21:37:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab='part.supplier_parts';
$ar_file='ar_inventory_tables.php';
$tipo='supplier_parts';

$default=$user->get_tab_defaults($tab);


$table_views=array(
	'overview'=>array('label'=>_('Overview'),'title'=>_('Overview')),
//	'sales'=>array('label'=>_('Sales'),'title'=>_('Sales')),

);

$table_filters=array(
//	'code'=>array('label'=>_('Code'),'title'=>_('Product code')),
	'reference'=>array('label'=>_('Reference'),'title'=>_("Supplier's part reference")),

);

$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
		
);

$table_buttons=array();
$table_buttons[]=array('icon'=>'plus', 'title'=>_('New product'), 'reference'=>"part/".$state['key'].'/supplier_part/new');
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


?>
