<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 15:18:07 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='locations';
$ar_file='ar_warehouse_tables.php';
$tipo='locations';

$default=$user->get_tab_defaults($tab);



$table_views=array(

);

$table_filters=array(
	'code'=>array('label'=>_('Code'),'title'=>_('Location code')),

);

$parameters=array(
		'parent'=>'warehouse',
		'parent_key'=>$state['parent_key'],
	
);


include('utils/get_table_html.php');


?>
