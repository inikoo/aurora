<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 November 2015 at 13:30:55 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='employee.timesheets.records';
$ar_file='ar_hr_tables.php';
$tipo='timesheet_records';

$default=$user->get_tab_defaults($tab);

$table_views=array(
);

$table_filters=array(
);

$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
		
);

$table_buttons=array();
$table_buttons[]=array('icon'=>'plus', 'title'=>_('New timesheet record'), 'reference'=>"employee/".$state['object']."/".$state['key']."/new/timesheet_record");
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');

?>
