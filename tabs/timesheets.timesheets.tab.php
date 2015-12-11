<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 December 2015 at 13:50:43 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='timesheets.timesheets';
$ar_file='ar_hr_tables.php';
$tipo='timesheets';



$default=$user->get_tab_defaults($tab);


$table_views=array(
);

$table_filters=array(
	'alias'=>array('label'=>_('Alias'),'title'=>_('Employee alias')),
	'name'=>array('label'=>_('Name'),'title'=>_('Employee name')),

);

$parameters=array(
	'parent'=>$state['parent'],
	'parent_key'=>$state['parent_key'],
	

);



include 'utils/get_table_html.php';

?>
