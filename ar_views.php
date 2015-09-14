<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2015 23:49:27 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

require_once 'common.php';
require_once 'ar_common.php';



$tipo=$_REQUEST['tipo'];


switch ($tipo) {
case 'views':



	$data=prepare_values($_REQUEST,array(
			'request'=>array('type'=>'string'),
			'old_state'=>array('type'=>'json array'),
		));

	$state=parse_request($data['request']);

	if ($state['module']=='customers' or $state['module']=='customers_server') {
		require_once 'navigation_customers.php';
	}elseif ($state['module']=='orders' or $state['module']=='orders_server') {
		require_once 'navigation_orders.php';
	}elseif ($state['module']=='websites' or $state['module']=='website') {
		require_once 'navigation_websites.php';
	}elseif ($state['module']=='dashboard' ) {
		require_once 'navigation_dashboard.php';
	}

	$response=array('state'=>$state);


	if ($data['old_state']['module']!=$state['module']) {
		$response['menu']=get_menu($state);

	}


	if ($data['old_state']['section']!=$state['section'] or
		$data['old_state']['parent_key']!=$state['parent_key'] or
		$data['old_state']['key']!=$state['key']

	) {

		$response['navigation']=get_navigation($state);
		$response['tabs']=get_tabs($state);
	}

	$response['view_position']=get_view_position($state);

	if ($modules[$state['module']]['sections'][$state['section']]['type']=='object') {
		$response['object_showcase']=get_object_showcase($state);
	}else {
		$response['object_showcase']='';
	}

	$response['tab']=get_tab($state['tab'],$state);




	echo json_encode($response);

	break;
case 'tab':
	$data=prepare_values($_REQUEST,array(
			'tab'=>array('type'=>'tab'),
			'state'=>array('type'=>'json array'),
		));


	$response=array(
		'tab'=>get_tab($data['tab'],$data['state'])
	);




	echo json_encode($response);
	break;


default:
	$response=array('state'=>404,'resp'=>'Operation not found 2');
	echo json_encode($response);

}

function get_tab($tab,$state=false) {

	global $smarty;


	$results_per_page_options=array(500,100,50,20);
	$results_per_page=20;


	switch ($tab) {
	case 'customers_server':
		include_once 'tab_customers_server.php';
		break;
	case 'customers':
		include_once 'tab_customers.php';
		break;
	case 'customer.details':
		include_once 'tab_customer.details.php';
		break;
	default:
		$html='Not found '.$tab;
		break;
	}
	return $html;

}



function get_object_showcase($data) {



	switch ($data['object']) {
	case 'customer':
		include_once 'showcase_customer.php';
		$html=get_customer_showcase($data);
		break;
	default:
		$html=$data['object'].' -> '.$data['key'];
		break;
	}
	return $html;

}

function get_menu($data) {

	global $user,$smarty;

	$nav_menu=array();

	//$nav_menu[] = array('<i class="fa fa-home fa-fw"></i> '._('Home'), 'home','');

	if ($user->can_view('customers')) {


		if ($user->data['User Hooked Store Key']) {
			$nav_menu[] = array('<i class="fa fa-users fa-fw"></i> '._('Customers'), 'customers','customers');

		}else {
			$nav_menu[] = array('<i class="fa fa-users fa-fw"></i> '._('Customers'), 'customers_server','customers');

		}



	}

	if ($user->can_view('orders')) {

		if ($user->data['User Hooked Store Key']) {
			$nav_menu[] = array('<i class="fa fa-shopping-cart fa-fw"></i> '._('Orders'), 'orders','orders');
		}
		else {
			$nav_menu[] = array('<i class="fa fa-shopping-cart fa-fw"></i> '._('Orders'), 'orders_server','orders');
		}

	}

	if ($user->can_view('sites')) {


		if ($user->data['User Hooked Site Key']) {
			$nav_menu[] = array('<i class="fa fa-globe fa-fw"></i> '._('Websites'), 'website','websites');
		}
		else {
			$nav_menu[] = array('<i class="fa fa-globe fa-fw"></i> '._('Websites'), 'websites','websites');
		}




	}

	if ($user->can_view('stores')) {
		if (count($user->stores)==1) {
			$nav_menu[] = array('<i class="fa fa-square fa-fw"></i> '._('Products'), 'store.php?id='.$user->stores[0],'products');
		} elseif (count($user->stores)>1) {

			if ($user->data['User Hooked Store Key']) {
				$nav_menu[] = array('<i class="fa fa-square fa-fw"></i> '._('Products'), 'store.php?id='.$user->data['User Hooked Store Key'],'products');
			}
			else {
				$nav_menu[] = array('<i class="fa fa-square fa-fw"></i> '._('Products'), 'stores.php','products');

			}
		}

	}

	if ($user->can_view('marketing')) {
		if (count($user->stores)==1) {
			$nav_menu[] = array('<i class="fa fa-bullhorn fa-fw"></i> '._('Marketing'), 'marketing.php?store='.$user->stores[0],'marketing');
		} elseif (count($user->stores)>1) {

			if ($user->data['User Hooked Store Key']) {
				$nav_menu[] = array('<i class="fa fa-bullhorn fa-fw"></i> '._('Marketing'), 'marketing.php?store='.$user->data['User Hooked Store Key'],'marketing');
			}
			else {
				$nav_menu[] = array('<i class="fa fa-bullhorn fa-fw"></i> '._('Marketing'), 'marketing_server.php','marketing');

			}
		}


	}

	if ($user->can_view('warehouses')) {


		if (count($user->warehouses)==1)
			$nav_menu[] = array('<i class="fa fa-th  fa-fw"></i> '._('Inventory'), 'inventory.php?block_view=parts&warehouse_id='.$user->warehouses[0],'parts');
		else
			$nav_menu[] = array('<i class="fa fa-th fa-fw"></i> '._('Inventory'), 'warehouses.php','parts');

		if (count($user->warehouses)==1)
			$nav_menu[] = array('<i class="fa fa-map-marker fa-fw"></i> '._('Locations'), 'warehouse.php?id='.$user->warehouses[0],'locations');
		else
			$nav_menu[] = array('<i class="fa fa-map-marker fa-fw"></i> '._('Locations'), 'warehouses.php','locations');


	}
	if ($user->can_view('reports')) {
		$nav_menu[] = array('<i class="fa fa-line-chart fa-fw"></i> '._('Reports'), 'reports.php','reports');
	}


	if ($user->can_view('suppliers')) {
		$nav_menu[] = array('<i class="fa fa-industry fa-fw"></i> '._('Suppliers'), 'suppliers.php','suppliers');
	}


	if ($user->can_view('staff'))
		$nav_menu[] = array('<i class="fa fa-hand-rock-o fa-fw"></i> '._('Manpower'), 'hr.php','staff');



	if ($user->can_view('users'))
		$nav_menu[] = array('<i class="fa fa-male fa-fw"></i> '._('Users'), 'users.php','users');

	if ($user->can_view('account'))
		$nav_menu[] = array('<i class="fa fa-cog fa-fw"></i> '._('Settings'), 'account.php','account');



	if ($user->data['User Type']=='Supplier') {


		//$nav_menu[] = array(_('Orders'), 'suppliers.php?orders'  ,'orders');
		$nav_menu[] = array(_('Products'), 'suppliers.php'  ,'suppliers');
		$nav_menu[] = array(_('Dashboard'), 'index.php','home');
	}


	if ($user->data['User Type']=='Warehouse') {

		$nav_menu[] = array(_('Pending Orders'), 'warehouse_orders.php?id='.$user->data['User Parent Key'],'orders');


	}

	$current_item=$data['module'];
	if ($current_item=='customers_server')$current_item='customers';


	$smarty->assign('current_item',$current_item);

	$smarty->assign('nav_menu',$nav_menu);

	$html=$smarty->fetch('menu.tpl');

	return $html;


}

function get_navigation($data) {


	switch ($data['module']) {

	case ('dashboard'):
		return get_dashboard_navigation($data);
		break;
		break;
	case ('customers'):

		switch ($data['section']) {

		case ('customer'):
			return get_customer_navigation($data);
			break;

		case ('customers'):
			return get_customers_navigation($data);
			break;
		case ('categories'):

			return get_customers_categories_navigation($data);
			break;
		case ('lists'):
			return get_customers_lists_navigation($data);
			break;
		case ('dashboard'):
			return get_customers_dashboard_navigation($data);
			break;
		case ('statistics'):

			return get_customers_statistics_navigation($data);
			break;
		case ('pending_orders'):
			return get_customers_pending_orders_navigation($data);
			break;
		}

		break;
	case ('customers_server'):
		switch ($data['section']) {
		case ('customers'):
		case('pending_orders'):
			return get_customers_server_navigation($data);
			break;
		}

		break;

	case ('orders'):

		switch ($data['section']) {
		case ('dn'):
		case ('orders'):
		case ('invoices'):
		case ('payments'):


			return get_orders_navigation($data);
			break;
		default:
			return 'View not found';

		}
		break;

	case ('websites'):

		switch ($data['section']) {
		case ('websites'):


			return get_websites_navigation($data);
			break;
		case ('websites'):


			return get_website_navigation($data);
			break;
		default:
			return 'View not found';

		}
		break;

	case ('utils'):
		switch ($data['section']) {
		case ('forbidden'):
		case ('not_found'):
			return get_utils_navigation($data);
			break;
		}

		break;
	default:
		return 'Module not found';
	}

}

function get_utils_navigation($data) {
	global $smarty;
	$branch=array(array('label'=>'','icon'=>'home','reference'=>''));

	if ($data['section']=='not_found') {
		$title=_('Not found');
	}else if ($data['section']=='forbidden') {
			$title=_('Forbidden');
		}else {
		$title='';
	}

	$_content=array(
		'branch'=>$branch,
		'sections_class'=>'',
		'sections'=>array(),
		'left_buttons'=>array(),
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false,'placeholder'=>_('Search customers'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}




function get_tabs($data) {
	global $modules,$user,$smarty;
	if (isset($modules[$data['module']]['sections'][$data['section']]['tabs'])) {
		$tabs=$modules[$data['module']]['sections'][$data['section']]['tabs'];
	}else {
		$tabs=array();
	}
	if (isset($modules[$data['module']]['sections'][$data['section']]['subtabs'])) {
		$subtabs=$modules[$data['module']]['sections'][$data['section']]['subtabs'];
	}else {
		$subtabs=array();
	}

	if (isset($tabs[$data['tab']]) ) {
		$tabs[$data['tab']]['selected']=true;
	}

	if (isset($subtabs[$data['subtab']]) )$tabs[$data['subtab']]['selected']=true;

	$_content=array(
		'tabs'=>$tabs,
		'subtabs'=>$subtabs



	);


	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('tabs.tpl');
	return $html;
}

function get_view_position($data) {
	global $user,$smarty;
	$branch=array(array('label'=>_('Home'),'icon'=>'home','reference'=>''));

	switch ($data['module']) {

	case 'customers_server':
		if ($data['section']=='customers')
			$branch[]=array('label'=>_('Customers (All stores)'),'icon'=>'bars','reference'=>'customers/all');
		elseif ($data['section']=='pending_orders')
			$branch[]=array('label'=>_('Pending orders (All stores)'),'icon'=>'bars','reference'=>'pending_orders/all');

		break;
	case 'customers':


		switch ($data['parent']) {
		case 'store':
			$store=new Store($data['parent_key']);
			break;


		}





		if ( $user->get_number_stores()>1) {

			if ($data['section']=='pending_orders')
				$branch[]=array('label'=>_('Pending orders (All stores)'),'icon'=>'bars','reference'=>'pending_orders/all');
			else
				$branch[]=array('label'=>_('Customers (All stores)'),'icon'=>'bars','reference'=>'customers/all');

		}


		switch ($data['section']) {
		case 'customer':
			$customer=new Customer($data['key']);
			$store=new Store($customer->data['Customer Store Key']);


			$branch[]=array('label'=>_('Customers').' '.$store->data['Store Code'],'icon'=>'users','reference'=>'customers/'.$store->id);
			$branch[]=array('label'=>_('Customer').' '.$customer->get_formated_id(),'icon'=>'user','reference'=>'customer/'.$customer->id);

			break;
		case 'dashboard':
			$branch[]=array('label'=>_("Customer's dashboard").' '.$store->data['Store Code'],'icon'=>'dashboard','reference'=>'customers/dashboard/'.$store->id);
			break;
		case 'customers':
			$branch[]=array('label'=>_('Customers').' '.$store->data['Store Code'],'icon'=>'users','reference'=>'customers/'.$store->id);
			break;
		case 'categories':
			$branch[]=array('label'=>_("Customer's categories").' '.$store->data['Store Code'],'icon'=>'sitemap','reference'=>'customers/categories/'.$store->id);
			break;
		case 'lists':
			$branch[]=array('label'=>_("Customer's lists").' '.$store->data['Store Code'],'icon'=>'list','reference'=>'customers/lists/'.$store->id);
			break;
		case 'statistics':
			$branch[]=array('label'=>_("Customer's stats").' '.$store->data['Store Code'],'icon'=>'line-chart','reference'=>'customers/statistics/'.$store->id);
			break;
		case 'pending_orders':
			$branch[]=array('label'=>_("Pending orders").' '.$store->data['Store Code'],'icon'=>'clock-o','reference'=>'customers/pending_orders/'.$store->id);
			break;
		}
		break;
	case 'orders':
		switch ($data['section']) {
		case 'orders':

			if ( $user->get_number_stores()>1) {
				$branch[]=array('label'=>_('Orders').' ('._('All stores').')','icon'=>'bars','reference'=>'orders/all');
			}
			break;
		case 'invoices':
			if ( $user->get_number_stores()>1) {
				$branch[]=array('label'=>_('Invoices').' ('._('All stores').')','icon'=>'bars','url'=>'invoices/all');
			}
			break;
		case 'dn':
			if ( $user->get_number_stores()>1) {
				$branch[]=array('label'=>_('Delivery Notes').' ('._('All stores').')','icon'=>'bars','url'=>'dn/all');
			}
			break;
		case 'payments':
			if ( $user->get_number_stores()>1) {
				$branch[]=array('label'=>_('Payments').' ('._('All stores').')','icon'=>'bars','url'=>'payments/all');
			}
			break;
		}
		break;

	}

	$_content=array(
		'branch'=>$branch,

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('view_position.tpl');
	return $html;



}





function get_state($data) {

	$state=parse_request($data['request']);
	$response=array('state'=>200,'resp'=>$state);
	echo json_encode($response);

}
function parse_request_old($request) {

	global $user,$modules,$inikoo_account;

	$original_request=preg_replace('/^\//','',$request);
	$view_path=preg_split('/\//',$original_request);



	$object='';
	$key='';
	$module='';
	$section='';
	$tab='';
	$subtab='';
	$parent=false;
	$parent_key=false;
	$count_view_path=count($view_path);

	$shorcut=false;

	$is_main_section=false;

	reset($modules);

	if ($request=='') {


		$module=key($modules);
		$section=key($modules[$module]['sections']);
		$is_main_section=true;
		$parent=$modules[$module]['parent'];
		if (isset($modules[$module]['sections'][$section]['tabs'])) {
			$tab=key($modules[$module]['sections'][$section]['tabs']);
			if (isset($modules[$module]['sections'][$section]['tabs'][$tab]['subtabs'])) {
				$tab=key($modules[$module]['sections'][$section]['tabs'][$tab]['subtabs']);

			}
		}



	}elseif ($count_view_path==1) {

		$arg0=array_shift($view_path);


		if ($arg0=='customers') {
			$module='customers';
			$section='customers';
			$tab='customers';
			$shorcut=true;
		}elseif ($arg0=='websites') {
			$module='websites';
			$section='websites';
			$shorcut=true;
		}elseif ($arg0=='website') {
			$module='websites';
			$section='websites';
			$shorcut=true;
		}else {

			if (!array_key_exists($arg0,$modules)) {
				$module=key($modules);
			}else {
				$module=$arg0;
			}
			$section=key($modules[$module]['sections']);
			$is_main_section=true;



		}
		$parent=$modules[$module]['parent'];
		if (isset($modules[$module]['sections'][$section]['tabs'])) {
			$tab=key($modules[$module]['sections'][$section]['tabs']);
			if (isset($modules[$module]['sections'][$section]['tabs'][$tab]['subtabs'])) {
				$tab=key($modules[$module]['sections'][$section]['tabs'][$tab]['subtabs']);

			}
		}
	}
	elseif ($count_view_path==2) {

		if ($view_path[1]=='all') {
			if ($view_path[0]=='customers') {
				$module='customers_server';
				$section='customers';
				$shorcut=true;
			}elseif ($view_path[0]=='pending_orders') {
				$module='customers_server';
				$section='pending_orders';
				$shorcut=true;
			}


		}elseif (is_numeric($view_path[1])) {
			if ($view_path[0]=='customer') {
				$module='customers';
				$section='customer';
				$parent='';
				$parent_key='';
				$object='customer';
				$key=$view_path[1];

				$shorcut=true;
			}if ($view_path[0]=='customers') {
				$module='customers';
				$section='customers';
				$parent='store';
				$parent_key=$view_path[1];
				$tab='customers';
				$shorcut=true;
			}elseif ($view_path[0]=='orders') {
				$module='orders';
				$section='orders';
				$parent='store';
				$parent_key=$view_path[1];
				$shorcut=true;
			}elseif ($view_path[0]=='invoices') {
				$module='orders';
				$section='invoices';
				$parent='store';
				$parent_key=$view_path[1];
				$shorcut=true;
			}elseif ($view_path[0]=='dn') {
				$module='orders';
				$section='dn';
				$parent='store';
				$parent_key=$view_path[1];
				$shorcut=true;
			}elseif ($view_path[0]=='payments') {
				$module='orders';
				$section='payments';
				$parent='store';
				$parent_key=$view_path[1];
				$shorcut=true;
			}elseif ($view_path[0]=='website') {
				$module='websites';
				$section='website';
				$parent='website';
				$parent_key=$view_path[1];
				$shorcut=true;
			}


		}else {


			$module=array_shift($view_path);
			if (!array_key_exists($module,$modules)) {
				$module=key($modules);
			}
			$parent=$modules[$module]['parent'];
			$arg=array_shift($view_path);
			if ($modules[$module]['parent_type']=='key' and is_numeric($arg)) {
				$section=key($modules[$module]['sections']);
				$is_main_section=true;
				$parent=$modules[$module]['parent'];
				$parent_key=$arg;
			}else {
				if (array_key_exists($arg,$modules[$module]['sections'])) {
					$section=$arg;
				}else {
					$section=key($modules[$module]['sections']);
				}
			}
			if (isset($modules[$module]['sections'][$section]['tabs'])) {
				$tab=key($modules[$module]['sections'][$section]['tabs']);
				if (isset($modules[$module]['sections'][$section]['tabs'][$tab]['subtabs'])) {
					$tab=key($modules[$module]['sections'][$section]['tabs'][$tab]['subtabs']);

				}
			}
		}
	}
	elseif ($count_view_path==3) {

		if (($view_path[0]=='customers' and is_numeric($view_path[1]) and is_numeric($view_path[2]))  ) {

			$shorcut=true;
			$module='customers';
			$section='customer';
			$parent='store';
			$parent_key=$view_path[1];
			$object='customer';
			$key=$view_path[2];
			$tab='customer.details';

		}else {

			$module=array_shift($view_path);
			if (!array_key_exists($module,$modules)) {
				$module=key($modules);
			}
			$parent=$modules[$module]['parent'];
			$arg=array_shift($view_path);
			if ($modules[$module]['parent_type']=='key' and is_numeric($arg)) {
				$section=key($modules[$module]['sections']);
				$is_main_section=true;
				$parent=$modules[$module]['parent'];
				$parent_key=$arg;
			}else {
				if (array_key_exists($arg,$modules[$module]['sections'])) {
					$section=$arg;
					if ($section=='customers') {
						$tab='customers';
					}


				}else {
					$section=key($modules[$module]['sections']);
				}
			}

			$arg2=array_shift($view_path);

			if (!$parent_key and  $modules[$module]['parent_type']=='key' and is_numeric($arg2)) {


				$parent_key=$arg2;
			}else {


			}


			if (!$tab and isset($modules[$module]['sections'][$section]['tabs'])) {
				$tab=key($modules[$module]['sections'][$section]['tabs']);
				if (isset($modules[$module]['sections'][$section]['tabs'][$tab]['subtabs'])) {
					$tab=key($modules[$module]['sections'][$section]['tabs'][$tab]['subtabs']);

				}
			}

		}
	}
	elseif ($count_view_path==4) {

		if (($view_path[0]=='customer' and is_numeric($view_path[1]) and $view_path[2]=='store' and is_numeric($view_path[3]))  ) {

			$shorcut=true;
			$module='customers';
			$section='customer';
			$parent='store';
			$parent_key=$view_path[3];
			$object='customer';
			$key=$view_path[1];

		}elseif (($view_path[0]=='customers' and is_numeric($view_path[1]) and $view_path[2]=='customer' and is_numeric($view_path[3]))  ) {

			$shorcut=true;
			$module='customers';
			$section='customer';
			$parent='store';
			$parent_key=$view_path[1];
			$object='customer';
			$key=$view_path[3];

		}


	}



	if (!$parent_key) {
		if ($parent=='store') {
			if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'],$user->stores)) {
				$parent_key=$user->data['User Hooked Store Key'];
			}else {
				$_tmp=$user->stores;
				$parent_key=array_shift($_tmp);
			}
		}
	}


	if ($parent=='store' and !in_array($parent_key,$user->stores)) {
		$module='utils';


		if (in_array($parent_key,$inikoo_account->get_store_keys())) {
			$section='forbidden';
		}else {
			$section='not_found';
		}
		$parent='none';
	}

	if ($shorcut) {
		$request=$original_request;
	}else {

		$request=$module;

		if (!$is_main_section) {
			$request.='/'.$section;
		}

		if ($parent=='store' and $user->data['User Hooked Store Key']!=$parent_key  ) {
			$request.='/'.$parent_key;
		}
	}

	if ($module=='') {
		$module='utils';
		$section='not_found';
	}

	$state=array(
		'request'=>$request,
		'module'=>$module,
		'section'=>$section,
		'tab'=>$tab,
		'subtab'=>$subtab,
		'parent'=>$parent,
		'parent_key'=>$parent_key,
		'object'=>$object,
		'key'=>$key,
	);

	return $state;

}

function parse_request($request) {

	global $user,$modules,$inikoo_account;

	$original_request=preg_replace('/^\//','',$request);
	$view_path=preg_split('/\//',$original_request);



	$module='dashboard';
	$section='dashboard';
	$tab='dashboard';
	$subtab='';
	$parent=false;
	$parent_key=false;
	$object='';
	$key='';

	$count_view_path=count($view_path);
	$shorcut=false;
	$is_main_section=false;

	reset($modules);

	if ($count_view_path>0) {
		$root=array_shift($view_path);
		$count_view_path=count($view_path);
		switch ($root) {
		case 'customers':
			$module='customers';
			if ($count_view_path==0) {
				$section='customers';
				$tab='customers';
				$parent='store';
				if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'],$user->stores)) {
					$parent_key=$user->data['User Hooked Store Key'];
				}else {
					$_tmp=$user->stores;
					$parent_key=array_shift($_tmp);
				}

			}
			$arg1=array_shift($view_path);
			if ($arg1=='all') {
				$module='customers_server';
				$section='customers';
				$tab='customers_server';

				if (isset($view_path[0]) and $view_path[0]=='pending_orders') {
					$section='pending_orders';
					$tab='customers_server.pending_orders';

				}

			}
			elseif (is_numeric($arg1)) {
				$section='customers';
				$tab='customers';
				$parent='store';
				$parent_key=$arg1;

				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$section='customer';
					$object='customer';
					$tab='customer.details';
					$parent='store';
					$parent_key=$arg1;
					$key=$view_path[0];

				}

			}
			break;
		case 'orders':
			$module='orders';
			if ($count_view_path==0) {
				$section='orders';
				$tab='orders';
				$parent='store';
				if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'],$user->stores)) {
					$parent_key=$user->data['User Hooked Store Key'];
				}else {
					$_tmp=$user->stores;
					$parent_key=array_shift($_tmp);
				}

			}
			$arg1=array_shift($view_path);
			if ($arg1=='all') {
				$module='customers_server';
				$section='customers';
				$tab='customers_server';

				if (isset($view_path[0]) and $view_path[0]=='pending_orders') {
					$section='pending_orders';
					$tab='customers_server.pending_orders';

				}

			}
			elseif (is_numeric($arg1)) {
				$section='customers';
				$tab='customers';
				$parent='store';
				$parent_key=$arg1;

				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$section='customer';
					$object='customer';
					$tab='customer.details';
					$parent='store';
					$parent_key=$arg1;
					$key=$view_path[0];

				}

			}
			break;
		default:

			break;
		}

	}

	$state=array(
		'request'=>$request,
		'module'=>$module,
		'section'=>$section,
		'tab'=>$tab,
		'subtab'=>$subtab,
		'parent'=>$parent,
		'parent_key'=>$parent_key,
		'object'=>$object,
		'key'=>$key,
	);

	return $state;

}

?>
