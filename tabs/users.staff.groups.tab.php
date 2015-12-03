<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 2 December 2015 at 17:45:35 GMT , Worksop (train station)
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab='users.staff.groups';
$ar_file='ar_users_tables.php';
$tipo='groups';

$default=$user->get_tab_defaults($tab);


$table_views=array();

$table_filters=array(

);

$parameters=array(
		'parent'=>'',
		'parent_key'=>'',
);


include('utils/get_table_html.php');


?>
