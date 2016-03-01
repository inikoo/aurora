<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2016 at 15:25:33 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab='part.products';
$ar_file='ar_products_tables.php';
$tipo='products';

$default=$user->get_tab_defaults($tab);


$table_views=array(
	'overview'=>array('label'=>_('Overview'),'title'=>_('Overview')),
	'sales'=>array('label'=>_('Sales'),'title'=>_('Sales')),

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
$table_buttons[]=array('icon'=>'plus', 'title'=>_('New product'), 'reference'=>"inventory/".$state['parent_key']."/part/".$state['key'].'/product/new');
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


?>
