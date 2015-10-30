<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 14:58:04 CET, Pisa-Milan (train), Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='delivery_note.history';
$ar_file='ar_history_tables.php';
$tipo='object_history';

$default=$user->get_tab_defaults($tab);

$table_views=array(
);

$table_filters=array(
	'note'=>array('label'=>_('Notes'),'title'=>_('Notes')),
);

$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
		
);

include('utils/get_table_html.php');

?>
