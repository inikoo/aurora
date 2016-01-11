<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 11 January 2016 at 11:34:05 GMT+8, Kuala Lumpur, Malaysis
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab='data_sets.attachments';
$ar_file='ar_account_tables.php';
$tipo='attachments';

$default=$user->get_tab_defaults($tab);


$table_views=array();

$table_filters=array(

);

$parameters=array(
		'parent'=>$state['parent'],
		'parent_key'=>$state['parent_key'],
);


include('utils/get_table_html.php');


?>
