<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:11 June 2016 at 17:08:05 BST, Sheffield UK
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab='part_family.product_families';
$ar_file='ar_inventory_tables.php';
$tipo='product_families';

$default=$user->get_tab_defaults($tab);


$table_views=array(
	'overview'=>array('label'=>_('Overview'),'title'=>_('Overview')),

);

$table_filters=array(
	'code'=>array('label'=>_('Code')),
	'name'=>array('label'=>_('Label')),

);

$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
		
);

$smarty->assign('js_code', 'js/injections/part_family.product_families.'.(_DEVEL?'':'min.').'js');

include('utils/get_table_html.php');


?>
