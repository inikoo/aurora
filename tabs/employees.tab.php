<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 14:18:07 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='employees';
$ar_file='ar_hr_tables.php';
$tipo='employees';

$default=$user->get_tab_defaults($tab);

$table_views=array(

);

$table_filters=array(
	'name'=>array('label'=>_('Name'),'title'=>_('User name')),

);

$parameters=array(
		'parent'=>'company',
		'parent_key'=>'',
	
);

include('utils/get_table_html.php');


?>
