<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 November 2015 at 23:37:02 GMT, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab='exemployees';
$ar_file='ar_hr_tables.php';
$tipo='exemployees';

$default=$user->get_tab_defaults($tab);

$table_views=array(

);

$table_filters=array(
	'name'=>array('label'=>_('Name'), 'title'=>_('Employee name')),

);

$parameters=array(
	'parent'=>'account',
	'parent_key'=>1,

);


$smarty->assign('title', _('Ex employees'));
$smarty->assign('tipo', $tipo);


include 'utils/get_table_html.php';


?>
