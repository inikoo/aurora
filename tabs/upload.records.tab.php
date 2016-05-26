<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2016 at 13:54:31 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab='upload.records';
$ar_file='ar_account_tables.php';
$tipo='upload_records';

$default=$user->get_tab_defaults($tab);


$table_views=array();

$table_filters=array(

);

$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
);


include('utils/get_table_html.php');


?>
