<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 March 2016 at 22:24:21 GMT+8, 
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='employees.timesheets';
$ar_file='ar_hr_tables.php';
$tipo='timesheets';

$default=$user->get_tab_defaults($tab);

$table_views=array(
);

$table_filters=array(
	'alias'=>array('label'=>_('Code'),'title'=>_('Employee code')),
	'name'=>array('label'=>_('Name'),'title'=>_('Employee name')),

);

$parameters=array(
		'parent'=>$state['parent'],
		'parent_key'=>$state['parent_key'],
		
);

include('utils/get_table_html.php');

?>
