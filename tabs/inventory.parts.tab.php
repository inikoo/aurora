<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 September 2015 18:22:51 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='inventory.parts';
$ar_file='ar_inventory_tables.php';
$tipo='parts';

$default=$user->get_tab_defaults($tab);



$table_views=array(

);

$table_filters=array(
	'reference'=>array('label'=>_('Reference'), 'title'=>_('Part reference')),

);


$parameters=array(
	'parent'=>$state['parent'],
	'parent_key'=>$state['parent_key'],

);

if ($state['parent']=='warehouse') {
	$table_buttons=array();
	$table_buttons[]=array('icon'=>'plus', 'title'=>_('New part'), 'reference'=>"inventory/".$state['parent_key']."/part/new");
	$smarty->assign('table_buttons', $table_buttons);
}
include 'utils/get_table_html.php';


?>
