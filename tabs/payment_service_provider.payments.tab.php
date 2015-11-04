<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 3 November 2015 at 16:55:00 CET, Tessera, Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='payment_service_provider.payments';
$ar_file='ar_payments_tables.php';
$tipo='payments';

$default=$user->get_tab_defaults($tab);


$table_views=array(
	'overview'=>array('label'=>_('Overview'),'title'=>_('Overview')),

);

$table_filters=array(
	'reference'=>array('label'=>_('Reference'),'title'=>_('Reference')),

);

$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key'],
		
);


include('utils/get_table_html.php');


?>
