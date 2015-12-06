<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 December 2015 at 13:51:44 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='overtimes';
$ar_file='ar_hr_tables.php';
$tipo='overtimes';

$default=$user->get_tab_defaults($tab);

$table_views=array(
);

$table_filters=array(
	'reference'=>array('label'=>_('Reference'),'title'=>_('Overtime reference')),

);

$parameters=array(
		'parent'=>$state['parent'],
		'parent_key'=>$state['parent_key'],
		
);

$table_buttons=array();
$table_buttons[]=array('icon'=>'plus', 'title'=>_('New overtime'), 'reference'=>'overtime/new');
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');

?>
