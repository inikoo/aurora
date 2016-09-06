<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 5 October 2015 at 17:21:31 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='store.products';
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
		'parent'=>$state['parent'],
		'parent_key'=>$state['parent_key'],
		
);


$table_buttons=array();

//$table_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'),'id'=>'edit_table');


$table_buttons[]=array('icon'=>'plus', 'title'=>_('New product'), 'reference'=>"products/".$state['store']->id."/new");

$smarty->assign('table_buttons', $table_buttons);



include('utils/get_table_html.php');


?>
