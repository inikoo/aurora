<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 5 November 2015 at 19:06:03 CET, Venice Airport, Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='timesheets';
$ar_file='ar_hr_tables.php';
$tipo='timesheets';

$default=$user->get_tab_defaults($tab);

$table_views=array(

);

$table_filters=array(
	'name'=>array('label'=>_('Name'), 'title'=>_('Employee name')),

);

$parameters=array(
	'parent'=>'company',
	'parent_key'=>'',

);

$table_buttons=array();
$table_buttons[]=array('icon'=>'plus', 'title'=>_('New timesheet record'), 'id'=>"new_timesheet_record");

$smarty->assign('table_buttons', $table_buttons);

include 'utils/get_table_html.php';


?>
