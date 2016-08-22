<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 July 2016 at 12:11:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab='deleted.staff.user.login_history';
$ar_file='ar_users_tables.php';
$tipo='login_history';

$default=$user->get_tab_defaults($tab);


$table_views=array();

$table_filters=array(
	'ip'=>array('label'=>_('Ip'),'title'=>_('IP address')),

);

$parameters=array(
		'parent'=>$state['object'],
		'parent_key'=>$state['key']
);


include('utils/get_table_html.php');


?>
