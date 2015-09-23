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

	global $smarty,$user;


	$smarty->assign('data',$state);

	if (file_exists('tabs/'.$tab . '.tab.php')) {
		include_once 'tabs/'.$tab . '.tab.php';
	}else {
		$html='Tab Not found: '.$tab;

	}



	return $html;

}



function get_object_showcase($data) {



	switch ($data['object']) {
	case 'store':
	case 'website':
		$html='';
		break;

	case 'customer':
		include_once 'showcase/customer.show.php';
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




	include_once 'navigation/menu.php';

	return $html;


}

function get_navigation($data) {




	switch ($data['module']) {

	case ('dashboard'):
		require_once 'navigation/dashboard.nav.php';
		return get_dashboard_navigation($data);
		break;
	case ('products'):
		require_once 'navigation/products.nav.php';
		switch ($data['section']) {

		case 'store':
			return get_store_navigation($data);
			break;
		case 'department':
			return get_department_navigation($data);
			break;
		case 'family':
			return get_family_navigation($data);
			break;
		case 'product':
			return get_product_navigation($data);
			break;

		case ('categories'):
			return get_products_categories_navigation($data);
			break;
		}
	case ('customers'):
		require_once 'navigation/customers.nav.php';
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
		case ('category'):

			return get_customers_category_navigation($data);
			break;
		case ('lists'):
			return get_customers_lists_navigation($data);
			break;
		case ('list'):
			return get_customers_list_navigation($data);
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
		require_once 'navigation/customers.nav.php';
		switch ($data['section']) {
		case ('customers'):
		case('pending_orders'):
			return get_customers_server_navigation($data);
			break;
		}

		break;

	case ('orders'):
		require_once 'navigation/orders.nav.php';
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
		require_once 'navigation/websites.nav.php';
		switch ($data['section']) {
		case ('websites'):


			return get_websites_navigation($data);
			break;
		case ('website'):


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

	case 'products':

		if ( $user->get_number_stores()>1) {


			$branch[]=array('label'=>_('Stores'),'icon'=>'bars','reference'=>'stores');

		}


		if ($data['section']=='products') {

			if ($data['object']=='store') {
				$store=new Store($data['key']);
				$branch[]=array('label'=>$store->get('Store Code'),'icon'=>'','reference'=>'store/'.$store->id);
			}


		}elseif ($data['section']=='categories') {
			$branch[]=array('label'=>_('Pending orders (All stores)'),'icon'=>'bars','reference'=>'pending_orders/all');
		}


		break;
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
		case 'list':
			$list=new SubjectList($data['key']);
			$store=new Store($list->data['List Parent Key']);


			$branch[]=array('label'=>_("Customer's lists").' '.$store->data['Store Code'],'icon'=>'list','reference'=>'customers/'.$store->id.'/lists');
			$branch[]=array('label'=>$list->get('List Name'),'icon'=>'','reference'=>'customers/list/'.$list->id);

			break;

		case 'customer':

			if ($data['parent']=='store') {
				$customer=new Customer($data['key']);
				$store=new Store($customer->data['Customer Store Key']);


				$branch[]=array('label'=>_('Customers').' '.$store->data['Store Code'],'icon'=>'users','reference'=>'customers/'.$store->id);
				$branch[]=array('label'=>_('Customer').' '.$customer->get_formated_id(),'icon'=>'user','reference'=>'customer/'.$customer->id);
			}elseif ($data['parent']=='list') {
				$customer=new Customer($data['key']);
				$store=new Store($customer->data['Customer Store Key']);

				$list=new SubjectList($data['parent_key']);

				$branch[]=array('label'=>_("Customer's lists").' '.$store->data['Store Code'],'icon'=>'list','reference'=>'customers/'.$store->id.'/lists');
				$branch[]=array('label'=>$list->get('List Name'),'icon'=>'','reference'=>'customers/list/'.$list->id);


				$branch[]=array('label'=>_('Customer').' '.$customer->get_formated_id(),'icon'=>'user','reference'=>'customer/'.$customer->id);
			}
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
			$branch[]=array('label'=>_("Customer's lists").' '.$store->data['Store Code'],'icon'=>'list','reference'=>'customers/'.$store->id.'/lists');
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
		case 'store':
			$module='products';
			$section='store';
			$object='store';
			if ($count_view_path==0 or !is_numeric($view_path[0])) {
				if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'],$user->stores)) {
					$key=$user->data['User Hooked Store Key'];
				}else {
					$_tmp=$user->stores;
					$key=array_shift($_tmp);
				}
			}

			if (is_numeric($view_path[0])) {
				$key=array_shift($view_path);
			}

			//print_r($_SESSION['state']);

			if (isset ( $_SESSION['state'][$module][$section]['tab'])   ) {
				$tab=$_SESSION['state'][$module][$section]['tab'];
			}else {

				$tab='store_dashboard';
			}
			break;
		case 'category':
			$object='category';

			if (isset($view_path[0]) and is_numeric($view_path[0])) {
				$key=$view_path[0];
				$category=new Category($key);

				$parent='category';
				$parent_key=$category->get('Category Parent Key');

				switch ($category->get('Category Subject')) {
				case 'Customer':
					$module='customers';
					$section='category';
					$tab='customers.category';

					if ($category->get('Category Branch Type')=='Root') {
						$parent='store';
						$parent_key=$category->get('Category Store Key');
					}

					break;
				default:
					exit('error');
					break;
				}

			}else {
				//error
			}






			break;
		case 'website':
			$module='websites';
			$section='website';

			$tab='website.details';
			$object='website';
			$key=$view_path[0];
			break;
		case 'customer':
			$module='customers';
			$section='customer';

			$tab='customer.details';
			$object='customer';
			$key=$view_path[0];
			break;
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
			elseif ($arg1=='list') {
				$section='list';
				$tab='customers.list';
				$object='list';




				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$key=$view_path[0];
					include_once 'class.List.php';
					$list=new SubjectList($key);
					$parent='store';
					$parent_key=$list->get('List Parent Key');


					if (isset($view_path[1]) and is_numeric($view_path[1])) {
						$section='customer';

						$tab='customer.details';
						$parent='list';
						$parent_key=$list->id;
						$object='customer';
						$key=$view_path[1];

					}


				}else {
					//error
				}

			}
			elseif ($arg1=='category') {
				$section='category';
				$tab='customers.category';
				$object='category';




				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$key=$view_path[0];
					include_once 'class.Category.php';
					$category=new Category($key);
					$parent='store';
					$parent_key=$category->get('Category Store Key');


					if (isset($view_path[1]) and is_numeric($view_path[1])) {
						$section='customer';

						$tab='customer.details';
						$parent='category';
						$parent_key=$category->id;
						$object='customer';
						$key=$view_path[1];

					}


				}else {
					//error
				}

			}
			elseif (is_numeric($arg1)) {
				$section='customers';
				$tab='customers';
				$parent='store';
				$parent_key=$arg1;

				if (isset($view_path[0])) {

					if ( is_numeric($view_path[0])) {
						$section='customer';

						$tab='customer.details';
						$parent='store';
						$parent_key=$arg1;
						$object='customer';
						$key=$view_path[0];

					}elseif ($view_path[0]=='lists') {
						$section='lists';
						$tab='customers.lists';
					}elseif ($view_path[0]=='categories') {
						$section='categories';
						$tab='customers.categories';
					}

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
				$section='orders';
				$tab='orders';
				$parent='store';
				$parent_key=$arg1;

				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$section='order';
					$object='order';
					$tab='items';
					$parent='store';
					$parent_key=$arg1;
					$key=$view_path[0];

				}

			}
			break;
		case 'invoices':
			$module='orders';
			if ($count_view_path==0) {
				$section='invoices';
				$tab='invoices';
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
				$module='orders_server';
				$section='orders';
				$tab='orders_server';

				if (isset($view_path[0]) and $view_path[0]=='pending_orders') {
					$section='pending_orders';
					$tab='customers_server.pending_orders';

				}

			}
			elseif (is_numeric($arg1)) {
				$section='invoices';
				$tab='invoices';
				$parent='store';
				$parent_key=$arg1;

				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$section='invoices';
					$object='invoice';
					$tab='items';
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
    //print_r($state);
	return $state;

}

?>
