<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:  21 December 2015 at 07:26:55 GMT+8 , Macau
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab='billingregion_taxcategory';
$ar_file='ar_reports_tables.php';
$tipo='billingregion_taxcategory';

$default=$user->get_tab_defaults($tab);

$table_views=array(

);

$table_filters=array(

);

$parameters=array(
		'parent'=>'account',
		'parent_key'=>1
	);



include('utils/get_table_html.php');



?>
