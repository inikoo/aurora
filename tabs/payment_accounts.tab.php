<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 3 November 2015 at 13:28:05 CET, Tessera, Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='payment_accounts';
$ar_file='ar_payments_tables.php';
$tipo='accounts';

$default=$user->get_tab_defaults($tab);


$table_views=array(
	'overview'=>array('label'=>_('Overview'),'title'=>_('Overview')),

);

$table_filters=array(
	'code'=>array('label'=>_('Code'),'title'=>_('Account code')),
	'name'=>array('label'=>_('Name'),'title'=>_('Account name')),

);

$parameters=array(
		'parent'=>$state['parent'],
		'parent_key'=>$state['parent_key'],
		
);


include('utils/get_table_html.php');


?>
