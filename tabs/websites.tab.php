<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 19:55:23 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab='websites';
$ar_file='ar_websites_tables.php';
$tipo='websites';

$default=$user->get_tab_defaults($tab);


$table_views=array();

$table_filters=array(
	'code'=>array('label'=>_('Code'),'title'=>_('Store code')),
	'name'=>array('label'=>_('Name'),'title'=>_('Store name')),

);

$parameters=array(
		'parent'=>'',
		'parent_key'=>'',
);


include('utils/get_table_html.php');


?>
