<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2015 23:49:27 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

$current_section=$data['section'];

$nav_menu=array();

//$nav_menu[] = array('<i class="fa fa-home fa-fw"></i> '._('Home'), 'home','');

if ($user->can_view('customers')) {


	if ($user->data['User Hooked Store Key']) {
		$nav_menu[] = array('<i class="fa fa-users fa-fw"></i> '._('Customers'), 'customers/'.$user->data['User Hooked Store Key'],'customers','module');

	}else {
		$nav_menu[] = array('<i class="fa fa-users fa-fw"></i> '._('Customers'), 'customers_server','customers','module');
	}



	$sections=get_sections('customers',$data['parent_key']);
	foreach ($sections as $key=>$section ) {
		$nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i> '.$section['label'],$section['reference'],$key,'section');
	}
}




if ($user->can_view('orders')) {

	if ($user->data['User Hooked Store Key']) {
		$nav_menu[] = array('<i class="fa fa-shopping-cart fa-fw"></i> '._('Orders'), 'orders','orders','module');
	}
	else {
		$nav_menu[] = array('<i class="fa fa-shopping-cart fa-fw"></i> '._('Orders'), 'orders_server','orders','module');
	}

	$sections=get_sections('orders',$data['parent_key']);
	foreach ($sections as $key=>$section ) {
		$nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i> '.$section['label'],$section['reference'],$key,'section');
	}

}

if ($user->can_view('sites')) {


	if ($user->data['User Hooked Site Key']) {
		$nav_menu[] = array('<i class="fa fa-globe fa-fw"></i> '._('Websites'), 'website/'.$user->data['User Hooked Site Key'],'websites','module');
	}
	else {
		$nav_menu[] = array('<i class="fa fa-globe fa-fw"></i> '._('Websites'), 'websites','websites','module');
	}

	$sections=get_sections('websites',$data['parent_key']);
	foreach ($sections as $key=>$section ) {
		$nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i> '.$section['label'],$section['reference'],$key,'section');
	}


}

if ($user->can_view('stores')) {

	if ($user->data['User Hooked Store Key']) {
		$nav_menu[] = array('<i class="fa fa-square fa-fw"></i> '._('Products'), 'store/'.$user->data['User Hooked Store Key'],'products','module');

	}else {
		$nav_menu[] = array('<i class="fa fa-square fa-fw"></i> '._('Products'), 'stores','products','module');
	}


	$sections=get_sections('products',$data['parent_key']);
	foreach ($sections as $key=>$section ) {
		$nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i> '.$section['label'],$section['reference'],$key,'section');
	}



}

if ($user->can_view('marketing')) {

if ($user->data['User Hooked Store Key']) {
		$nav_menu[] = array('<i class="fa fa-bullhorn fa-fw"></i> '._('Marketing'), 'marketing/'.$user->data['User Hooked Store Key'],'marketing','module');

	}else {
		$nav_menu[] = array('<i class="fa fa-bullhorn fa-fw"></i> '._('Marketing'), 'marketing/all','marketing','module');
	}


	$sections=get_sections('marketing',$data['parent_key']);
	foreach ($sections as $key=>$section ) {
		$nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i> '.$section['label'],$section['reference'],$key,'section');
	}





}

if ($user->can_view('warehouses')) {

/*
	if ($user->data['User Hooked Warehouse Key']) {
		$nav_menu[] = array('<i class="fa fa-th  fa-fw"></i> '._('Inventory'), 'inventory.php?block_view=parts&warehouse_id='.$user->warehouses[0],'parts','module');
	}else {
		$nav_menu[] = array('<i class="fa fa-th fa-fw"></i> '._('Inventory'), 'warehouses_server','parts','module');
	}
*/
	if ($user->data['User Hooked Warehouse Key']) {
		$nav_menu[] = array('<i class="fa fa-th-large fa-fw"></i> '._('Warehouse'), 'warehouse/'.$user->data['User Hooked Warehouse Key'],'warehouses','module');
	}else {
		$nav_menu[] = array('<i class="fa fa-th-large fa-fw"></i> '._('Warehouse'), 'warehouses_server','warehouses','module');
	}

}
if ($user->can_view('reports')) {
	$nav_menu[] = array('<i class="fa fa-line-chart fa-fw"></i> '._('Reports'), 'reports','reports','module');
}


if ($user->can_view('suppliers')) {
	$nav_menu[] = array('<i class="fa fa-industry fa-fw"></i> '._('Suppliers'), 'suppliers','suppliers','module');
}


if ($user->can_view('staff'))
	$nav_menu[] = array('<i class="fa fa-hand-rock-o fa-fw"></i> '._('Manpower'), 'hr','hr','module');



if ($user->can_view('users'))
	$nav_menu[] = array('<i class="fa fa-male fa-fw"></i> '._('Users'), 'users','users','module');

if ($user->can_view('account'))
	$nav_menu[] = array('<i class="fa fa-cog fa-fw"></i> '._('Settings'), 'account.php','account','module');



if ($user->data['User Type']=='Supplier') {


	//$nav_menu[] = array(_('Orders'), 'suppliers.php?orders'  ,'orders');
	$nav_menu[] = array(_('Products'), 'suppliers.php'  ,'suppliers','module');
	$nav_menu[] = array(_('Dashboard'), 'index.php','home','module');
}


if ($user->data['User Type']=='Warehouse') {

	$nav_menu[] = array(_('Pending Orders'), 'warehouse_orders.php?id='.$user->data['User Parent Key'],'orders','module');


}

$current_item=$data['module'];
if ($current_item=='customers_server')$current_item='customers';


$smarty->assign('current_item',$current_item);
$smarty->assign('current_section',$current_section);

$smarty->assign('nav_menu',$nav_menu);

$html=$smarty->fetch('menu.tpl');

?>
