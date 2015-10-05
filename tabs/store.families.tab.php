<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 5 October 2015 at 17:20:33 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='store.families';
$ar_file='ar_products_tables.php';
$tipo='families';

$default=$user->get_tab_defaults($tab);


$table_views=array(
	'overview'=>array('label'=>_('Overview'),'title'=>_('Overview')),
	'sales'=>array('label'=>_('Sales'),'title'=>_('Sales')),

);

$table_filters=array(
	'code'=>array('label'=>_('Code'),'title'=>_('Family code')),
	'name'=>array('label'=>_('Name'),'title'=>_('Family name')),

);

$parameters=array(
		'parent'=>'store',
		'parent_key'=>$state['key'],
		
);


include('utils/get_table_html.php');


?>
