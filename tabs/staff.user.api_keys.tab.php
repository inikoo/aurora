<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 5 November 2015 at 17:18:46 CET, Tessera Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='staff.user.api_keys';
$ar_file='ar_history_tables.php';
$tipo='object_history';

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
