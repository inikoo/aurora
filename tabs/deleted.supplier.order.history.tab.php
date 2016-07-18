<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 July 2016 at 19:48:23 GMT+8, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab='supplier.order.history';
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
