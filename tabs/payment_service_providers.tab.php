<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 17:41:37 CET, Pisa-Milan (train), Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab='payment_service_providers';
$ar_file='ar_payments_tables.php';
$tipo='payment_service_providers';

$default=$user->get_tab_defaults($tab);


$table_views=array();

$table_filters=array(
	'code'=>array('label'=>_('Code'),'title'=>_('Service provider code')),
	'name'=>array('label'=>_('Name'),'title'=>_('Service provider name')),

);

$parameters=array(
		'parent'=>'',
		'parent_key'=>'',
);


include('utils/get_table_html.php');


?>
