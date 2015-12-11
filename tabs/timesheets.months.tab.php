<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 December 2015 at 17:50:52 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='timesheets.months';
$ar_file='ar_hr_tables.php';
$tipo='months';



$default=$user->get_tab_defaults($tab);


$table_views=array(
);

$table_filters=array();

$parameters=array(
	'parent'=>$state['parent'],
	'parent_key'=>$state['parent_key'],
	'group_by'=>'month',

);



include 'utils/get_table_html.php';

?>
