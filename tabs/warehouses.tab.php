<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 12:16:14 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab='warehouses';
$ar_file='ar_warehouse_tables.php';
$tipo='warehouses';

$default=$user->get_tab_defaults($tab);


$table_views=array();

$table_filters=array(
	'code'=>array('label'=>_('Code'),'title'=>_('Warehouse code')),
	'name'=>array('label'=>_('Name'),'title'=>_('Warehouse name')),

);

$parameters=array(
		'parent'=>'account',
		'parent_key'=>1,
);

$table_buttons=array();
$table_buttons[]=array('icon'=>'plus', 'title'=>_('New warehouse'), 'reference'=>"warehouse/new");
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


?>
