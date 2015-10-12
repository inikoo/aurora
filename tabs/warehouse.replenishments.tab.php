<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 229 September 2015 16:47:44 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='warehouse.replenishments';
$ar_file='ar_warehouse_tables.php';
$tipo='replenishments';

$default=$user->get_tab_defaults($tab);



$table_views=array(

);

$table_filters=array(
	'location'=>array('label'=>_('Location'),'title'=>_('Location code')),

);

$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
	
);


include('utils/get_table_html.php');


?>
