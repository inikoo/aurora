<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

include_once 'common.php';
include_once 'class.Store.php';
include_once 'class.DealCampaign.php';

if (!$user->can_view('stores') or count($user->stores)==0 ) {

	header('Location: index.php');
	exit;
}

switch ($_REQUEST['parent']) {
case 'campaign':
	$campaign=new DealCampaign($_REQUEST['parent_key']);
	$store=new Store($campaign->data['Deal Campaign Store Key']);

	$smarty->assign('scope', $campaign);
	$smarty->assign('campaign', $campaign);

	$smarty->assign('store', $store);
	$smarty->assign('scope_subject', 'Campaign');
	$smarty->assign('trigger', 'Order');
	$smarty->assign('trigger_key', $store->id);
	$smarty->assign('target', 'Order');
	$smarty->assign('target_key', '');

	$smarty->assign('parent', 'marketing');
	$smarty->assign('search_label', _('Offers'));
	$smarty->assign('search_scope', 'products');
	$smarty->assign('link_back', 'campaign.php?id='.$campaign->id);


	break;
case 'customer':
	$customer=new Customer($_REQUEST['parent_key']);


	$store=new Store($customer->data['Customer Store Key']);

	$smarty->assign('scope', $customer);
	$smarty->assign('customer', $customer);

	$smarty->assign('store', $store);

	$smarty->assign('scope_subject', 'Customer');
	$smarty->assign('trigger', 'Customer');
	$smarty->assign('trigger_key', $customer->id);
	$smarty->assign('target', 'Order');
	$smarty->assign('target_key', '');

	$smarty->assign('parent', 'customers');
	$smarty->assign('search_label', _('Customers'));
	$smarty->assign('search_scope', 'customers');
	$smarty->assign('link_back', 'customer.php?id='.$customer->id);



	break;
case 'store':
	$store=new Store($_REQUEST['parent_key']);

	$smarty->assign('scope', $store);

	$smarty->assign('store', $store);

	$smarty->assign('scope_subject', 'Store');
	$smarty->assign('trigger', 'Order');
	$smarty->assign('trigger_key', $store->id);
	$smarty->assign('target', 'Order');
	$smarty->assign('target_key', '');

	$smarty->assign('parent', 'products');
	$smarty->assign('search_label', _('Products'));
	$smarty->assign('search_scope', 'products');
	$smarty->assign('link_back', 'store.php?id='.$store->id);

	break;
case 'department':
	$department=new Department($_REQUEST['parent_key']);

	$store=new Store($department->data['Product Department Store Key']);

	$smarty->assign('scope', $department);
	$smarty->assign('department', $department);

	$smarty->assign('store', $store);


	$smarty->assign('department', $department);

	$smarty->assign('scope_subject', 'Department');
	$smarty->assign('trigger', 'Department');
	$smarty->assign('trigger_key', $department->id);
	$smarty->assign('target', 'Department');
	$smarty->assign('target_key', $department->id);

	$smarty->assign('parent', 'products');
	$smarty->assign('search_label', _('Products'));
	$smarty->assign('search_scope', 'products');
	$smarty->assign('link_back', 'department.php?id='.$department->id);


	break;
case 'family':
	$family=new Family($_REQUEST['parent_key']);
	$department=new Department($family->get('Product Family Main Department Key'));

	$store=new Store($family->data['Product Family Store Key']);

	$smarty->assign('scope', $family);
	$smarty->assign('family', $family);

	$smarty->assign('store', $store);
	$smarty->assign('department', $department);

	$smarty->assign('scope_subject', 'Family');
	$smarty->assign('trigger', 'Family');
	$smarty->assign('trigger_key', $family->id);
	$smarty->assign('target', 'Family');
	$smarty->assign('target_key', $family->id);


	$smarty->assign('parent', 'products');
	$smarty->assign('search_label', _('Products'));
	$smarty->assign('search_scope', 'products');
	$smarty->assign('link_back', 'family.php?id='.$family->id);


	break;
case 'product':
	$product=new Product('pid', $_REQUEST['parent_key']);
	$department=new Department($product->get('Product Main Department Key'));
	$family=new Family($product->get('Product Family Key'));

	$store=new Store($product->data['Product Store Key']);

	$smarty->assign('scope', $product);
	$smarty->assign('product', $product);

	$smarty->assign('family', $family);
	$smarty->assign('department', $department);
	$smarty->assign('store', $store);

	$smarty->assign('scope_subject', 'Product');
	$smarty->assign('trigger', 'Product');
	$smarty->assign('trigger_key', $product->pid);
	$smarty->assign('target', 'Product');
	$smarty->assign('target_key', $product->pid);

	$smarty->assign('parent', 'products');

	$smarty->assign('search_label', _('Products'));
	$smarty->assign('search_scope', 'products');
	$smarty->assign('link_back', 'product.php?pid='.$product->pid);


	break;
}

$smarty->assign('store_key', $store->id);

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/button.css',
	'css/container.css',
	'css/edit.css',
	'css/table.css',
	'theme.css.php',
	'css/new_deal.css'
);

$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',

	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/search.js',
	'js/new_deal.js',

);


$smarty->assign('title', _('New Offer'));
$smarty->assign('css_files', $css_files);
$smarty->assign('js_files', $js_files);

$tipo_filter100='code';
$filter_menu100=array(
	'code'=>array('db_key'=>'code', 'menu_label'=>_('Campaign Code'), 'label'=>_('Code')),
	'name'=>array('db_key'=>'name', 'menu_label'=>_('Campaign Name'), 'label'=>_('Name')),
);
$smarty->assign('filter_name100', $filter_menu100[$tipo_filter100]['label']);
$smarty->assign('filter_menu100', $filter_menu100);
$smarty->assign('filter100', $tipo_filter100);
$smarty->assign('filter_value100', '');
$paginator_menu=array(10, 25, 50, 100, 500);
$smarty->assign('paginator_menu100', $paginator_menu);

$tipo_filter101='code';
$filter_menu101=array(
	'code'=>array('db_key'=>'code', 'menu_label'=>_('Department Code'), 'label'=>_('Code')),
	'name'=>array('db_key'=>'name', 'menu_label'=>_('Department Name'), 'label'=>_('Name')),
);
$smarty->assign('filter_name101', $filter_menu101[$tipo_filter101]['label']);
$smarty->assign('filter_menu101', $filter_menu101);
$smarty->assign('filter101', $tipo_filter101);
$smarty->assign('filter_value101', '');
$paginator_menu=array(10, 25, 50, 100, 500);
$smarty->assign('paginator_menu101', $paginator_menu);

$tipo_filter102='code';
$filter_menu102=array(
	'code'=>array('db_key'=>'code', 'menu_label'=>_('Family Code'), 'label'=>_('Code')),
	'name'=>array('db_key'=>'name', 'menu_label'=>_('Family Name'), 'label'=>_('Name')),
);
$smarty->assign('filter_name102', $filter_menu102[$tipo_filter102]['label']);
$smarty->assign('filter_menu102', $filter_menu102);
$smarty->assign('filter102', $tipo_filter102);
$smarty->assign('filter_value102', '');
$paginator_menu=array(10, 25, 50, 100, 500);
$smarty->assign('paginator_menu102', $paginator_menu);

$tipo_filter103='code';
$filter_menu103=array(
	'code'=>array('db_key'=>'code', 'menu_label'=>_('Product Code'), 'label'=>_('Code')),
	'name'=>array('db_key'=>'name', 'menu_label'=>_('Product Name'), 'label'=>_('Name')),
);
$smarty->assign('filter_name103', $filter_menu103[$tipo_filter103]['label']);
$smarty->assign('filter_menu103', $filter_menu103);
$smarty->assign('filter103', $tipo_filter103);
$smarty->assign('filter_value103', '');
$paginator_menu=array(10, 25, 50, 100, 500);
$smarty->assign('paginator_menu103', $paginator_menu);

$tipo_filter104='name';
$filter_menu104=array(
	'id'=>array('db_key'=>'id', 'menu_label'=>_('Customer ID'), 'label'=>_('ID')),
	'name'=>array('db_key'=>'name', 'menu_label'=>_('Customer Name'), 'label'=>_('Name')),
);
$smarty->assign('filter_name104', $filter_menu104[$tipo_filter104]['label']);
$smarty->assign('filter_menu104', $filter_menu104);
$smarty->assign('filter104', $tipo_filter104);
$smarty->assign('filter_value104', '');
$paginator_menu=array(10, 25, 50, 100, 500);
$smarty->assign('paginator_menu104', $paginator_menu);

$tipo_filter105='code';
$filter_menu105=array(
	'code'=>array('db_key'=>'code', 'menu_label'=>_('Offer Code'), 'label'=>_('Code')),
	'name'=>array('db_key'=>'name', 'menu_label'=>_('Offer Name'), 'label'=>_('Name')),
);
$smarty->assign('filter_name105', $filter_menu105[$tipo_filter105]['label']);
$smarty->assign('filter_menu105', $filter_menu105);
$smarty->assign('filter105', $tipo_filter105);
$smarty->assign('filter_value105', '');
$paginator_menu=array(10, 25, 50, 100, 500);
$smarty->assign('paginator_menu105', $paginator_menu);

$session_data=base64_encode(json_encode(array(
			'label'=>array(
				'Invalid_code'=>_('Invalid code'),
				'Invalid_name'=>_('Invalid name'),
				'Invalid_description'=>_('Invalid description'),
				'Invalid_date'=>_('Invalid date'),
				'Invalid_amount'=>_('Invalid amount'),
				'Invalid_number'=>_('Invalid number'),
				'Invalid_percentage'=>_('Invalid percentage'),

				'Page'=>_('Page'),
				'of'=>_('of')

			)
		)));
$smarty->assign('session_data', $session_data);
$smarty->assign('post_create_action', $_SESSION['state']['deal']['post_create_action']);
$smarty->assign('currency_symbol', currency_symbol($store->data['Store Currency Code']));




$smarty->display('new_deal.tpl');
?>
