<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 January 2016 at 11:34:19 GMT+8, Kuala Lumpur, Malaysis
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab='timeseries';
$ar_file='ar_account_tables.php';
$tipo='timeseries';

$default=$user->get_tab_defaults($tab);


$table_views=array();

$table_filters=array(
	'code'=>array('label'=>_('Type'),'title'=>_('Timeseries Type')),

);

$parameters=array(
		'parent'=>'',
		'parent_key'=>'',
);


include('utils/get_table_html.php');


?>
