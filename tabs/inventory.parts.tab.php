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
$tipo='active_parts';

$default=$user->get_tab_defaults($tab);



$table_views=array(
	'overview'=>array('label'=>_('Overview')),
	'dispatched'=>array('label'=>_('Dispatched')),
	'revenue'=>array('label'=>_('Revenue')),
	'stock'=>array('label'=>_('Stock')),

);

$table_filters=array(
	'reference'=>array('label'=>_('Reference'), 'title'=>_('Part reference')),

);


$parameters=array(
	'parent'=>$state['parent'],
	'parent_key'=>$state['parent_key'],

);



$table_buttons=array();

/*
$table_buttons[]=array('icon'=>'plus', 'title'=>_('New part'), 'reference'=>"part/new");
$smarty->assign('table_buttons', $table_buttons);
$smarty->assign('upload_file', array(
'tipo'=>'upload_objects',
'parent'=>'account',
'parent_key'=>1,
'object'=>'part',
'label'=>_("Upload parts")

));
*/
include 'utils/get_table_html.php';


?>
