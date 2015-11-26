<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 24 November 2015 at 11:25:31 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='employee.timesheets';
$ar_file='ar_hr_tables.php';
$tipo='timesheets';

$default=$user->get_tab_defaults($tab);

$table_views=array(
);

$table_filters=array(
);

$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
		
);



include('utils/get_table_html.php');

?>
