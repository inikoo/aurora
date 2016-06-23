<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 23 June 2016 at 19:27:26 BST, Heathrow Airport, UK
 Copyright (c) 2016, Inikoo

 Version 3

*/



$tab='account.users.agents';
$ar_file='ar_users_tables.php';
$tipo='agents';

$default=$user->get_tab_defaults($tab);


$table_views=array(
	'privilegies'=>array('label'=>_('Overview')),
	'groups'=>array('label'=>_('Permissions')),
	'weblog'=>array('label'=>_('Syslog')),

);

$table_filters=array(
	'handle'=>array('label'=>_('Email')),
	'name'=>array('label'=>_('Name')),

);

$parameters=array(
		'parent'=>$state['parent'],
		'parent_key'=>$state['parent_key'],
	
);


include('utils/get_table_html.php');


?>
