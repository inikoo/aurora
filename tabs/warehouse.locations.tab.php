<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 15:18:07 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

if ( !$user->can_view('locations') or   !in_array($state['key'], $user->warehouses)   ) {
	$html='';
}else {


	$tab='warehouse.locations';
	$ar_file='ar_warehouse_tables.php';
	$tipo='locations';

	$default=$user->get_tab_defaults($tab);



	$table_views=array(

	);

	$table_filters=array(
		'code'=>array('label'=>_('Code'), 'title'=>_('Location code')),

	);

	$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],

	);

$smarty->assign('js_code', 'js/injections/warehouse_locations.'.(_DEVEL?'':'min.').'js');


	include 'utils/get_table_html.php';
}

?>
