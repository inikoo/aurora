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

$smarty->assign('js_code', file_get_contents('js/billingregion_taxcategory.js'));

$smarty->assign('aux_templates', array('table.aux/billingregion_taxcategory.tpl'));

$stores_data=array();
$sql=sprintf('Select `Store Key`,`Store Code`,`Store Name` from `Store Dimension`');


$excluded_stores=$default['excluded_stores'];
if (isset($_SESSION['table_state'][$tab]['excluded_stores'])) {
	$excluded_stores=$_SESSION['table_state'][$tab]['excluded_stores'];
}else {
	$_SESSION['table_state'][$tab]['excluded_stores']=$excluded_stores;
}



if ($result=$db->query($sql)) {

	foreach ($result as $data) {

		$stores_data[$data['Store Key']]=array('label'=>$data['Store Name'], 'checked'=>(in_array($data['Store Key'], $excluded_stores)?false:true  ));
	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}
$smarty->assign('stores_data', $stores_data);

include 'utils/get_table_html.php';




?>
