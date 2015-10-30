<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:  20 October 2015 at 16:06:04 BST, London UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='order.invoices';
$ar_file='ar_orders_tables.php';
$tipo='invoices';

$default=$user->get_tab_defaults($tab);

$table_views=array();

$table_filters=array();

$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
	);


include('utils/get_table_html.php');



?>
