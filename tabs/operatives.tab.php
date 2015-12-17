<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 16 December 2015 at 23:38:40 CET, Barcelona Airport, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='operatives';
$ar_file='ar_production_tables.php';
$tipo='operatives';

$default=$user->get_tab_defaults($tab);

$table_views=array(

);

$table_filters=array(
	'name'=>array('label'=>_('Name'),'title'=>_('Employee name')),

);

$parameters=array(
		'parent'=>'account',
		'parent_key'=>1,
	
);


$table_buttons=array();
$table_buttons[]=array('icon'=>'chain', 'title'=>_('Add employee'), 'reference'=>"operative/add");
$smarty->assign('table_buttons', $table_buttons);

$smarty->assign('tipo', $tipo);


$smarty->assign('title',_('Operatives'));

include('utils/get_table_html.php');


?>
