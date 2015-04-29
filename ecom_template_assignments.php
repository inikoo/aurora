<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 December 2014 21:12:19 GMT, Nottingham, UK

 Copyright (c) 2014, Inikoo

 Version 2.0
*/

$_site=array(
	'telephone'=>$site->data['Site Contact Telephone'],
	'address'=>$site->data['Site Contact Address'],
	'email'=>$site->data['Site Contact Email'],
	'company_name'=>$store->data['Store Company Name'],
	'company_tax_number'=>$store->data['Store VAT Number'],
	'company_number'=>$store->data['Store Company Number'],
	'id'=>$site->id,
	'store_id'=>$site->data['Site Store Key'],
	'locale'=>$site->data['Site Locale']
);

$_page=array(
	'found_in'=>$page->display_found_in(),
	'title'=>$page->display_title(),
	'search'=>$page->display_search(),
	'id'=>$page->id,
	'menu'=>$page->display_menu()
);


if ($page->data['Page Store Section Type']=='Family') {
	$smarty->assign('_products',$page->get_products_data());
}elseif ($page->data['Page Store Section Type']=='Department') {
	$smarty->assign('_families',$page->get_families_data());
}elseif ($page->data['Page Store Section Type']=='Product') {
	$smarty->assign('product',$page->get_product_data());
}elseif ($page->data['Page Store Section']=='Front Page Store') {
	$smarty->assign('_departments',$page->get_departments_data());
}




$smarty->assign('_page',$_page);
$smarty->assign('_site',$_site);





?>
