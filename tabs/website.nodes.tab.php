<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 October 2015 at 22:11:57 BST, Birmingham->Malaga (Plane)
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab='website.nodes';
$ar_file='ar_websites_tables.php';
$tipo='nodes';

$default=$user->get_tab_defaults($tab);


$table_views=array();

$table_filters=array(
	'code'=>array('label'=>_('Code')),
	'title'=>array('label'=>_('Name')),

);

$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
);


include('utils/get_table_html.php');


?>
