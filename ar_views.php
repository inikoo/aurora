<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2015 23:49:27 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';



$tipo=$_REQUEST['tipo'];


switch ($tipo) {
case 'views':

	require_once 'utils/parse_request.php';


	$data=prepare_values($_REQUEST, array(
			'request'=>array('type'=>'string'),
			'old_state'=>array('type'=>'json array'),
			'tab'=>array('type'=>'string', 'optional'=>true),
			'subtab'=>array('type'=>'string', 'optional'=>true),
			'otf'=>array('type'=>'string', 'optional'=>true),
			'metadata'=>array('type'=>'json array', 'optional'=>true),

		));


	if (isset($data['metadata']['help']) and $data['metadata']['help'] ) {
		get_help($data, $modules, $db);
		return;
	}


	if (isset($data['metadata']['reload']) and $data['metadata']['reload'] ) {
		$reload=true;
	}else {
		$reload=false;
	}

	$state=parse_request($data, $db);



	if ($state['object']!='') {

		$_object=get_object($state['object'], $state['key']);


		if (!$_object->id  and $modules[$state['module']]['sections'][$state['section']]['type']=='object') {


			$state=array('old_state'=>$state, 'module'=>'utils', 'section'=>'not_found', 'tab'=>'not_found', 'subtab'=>'', 'parent'=>$state['object'], 'parent_key'=>'', 'object'=>'');


		}else {

			$state['_object']=$_object;

		}

	}

	switch ($state['parent']) {

	case 'store':
		$_parent=new Store($state['parent_key']);
		break;
	case 'website':
		$_parent=new Site($state['parent_key']);



		break;

	default:
		$_parent=false;
	}





	if (is_object($_parent) and !$_parent->id) {


		$state=array('old_state'=>$state, 'module'=>'utils', 'section'=>'not_found', 'tab'=>'not_found', 'subtab'=>'', 'parent'=>$state['parent'], 'parent_key'=>'', 'object'=>'');

	}

	if ($state['module']=='hr') {

		if (!$user->can_view('staff') ) {

			$state=array('old_state'=>$state, 'module'=>'utils', 'section'=>'forbidden', 'tab'=>'forbidden', 'subtab'=>'', 'parent'=>$state['parent'], 'parent_key'=>$state['parent_key'], '_object'=>'', 'object'=>'', 'key'=>'');

		}

	}

	$sql=sprintf('insert into `User System View Fact`  (`User Key`,`Date`,`Module`,`Section`,`Tab`,`Parent`,`Parent Key`,`Object`,`Object Key`)  values (%d,%s,%s,%s,%s,%s,%s,%s,%s)',
		$user->id,
		prepare_mysql(gmdate('Y-m-d H:i:s')),
		prepare_mysql($state['module']),
		prepare_mysql($state['section']),
		prepare_mysql(($state['subtab']!=''?$state['subtab']:$state['tab'])),
		prepare_mysql($state['parent']),
		prepare_mysql($state['parent_key']),
		prepare_mysql($state['object']),
		prepare_mysql($state['key'])

	);
	$db->exec($sql);



	list($state, $response['view_position'])=get_view_position($state);


	$response=array('state'=>array());


	if ($data['old_state']['module']!=$state['module']  or $reload ) {
		$response['menu']=get_menu($state);

	}


	if ($data['old_state']['section']!=$state['section'] or
		$data['old_state']['parent_key']!=$state['parent_key'] or
		$data['old_state']['key']!=$state['key'] or  $reload

	) {

		$response['navigation']=get_navigation($state);

	}

	if ($reload) {
		$response['logout_label']=_('Logout');
	}

	//special dynamic tabs
	if ($state['section']=='timesheets') {

		if ($state['parent']=='day') {

            unset($modules[$state['module']]['sections'][$state['section']]['tabs']['timesheets.days']);
            unset($modules[$state['module']]['sections'][$state['section']]['tabs']['timesheets.weeks']);
            unset($modules[$state['module']]['sections'][$state['section']]['tabs']['timesheets.months']);

			if ($state['tab']=='timesheets.days' or $state['tab']=='timesheets.weeks' or $state['tab']=='timesheets.months' )
				$state['tab']='timesheets.employees';

		}elseif ($state['parent']=='week') {

            unset($modules[$state['module']]['sections'][$state['section']]['tabs']['timesheets.weeks']);
            unset($modules[$state['module']]['sections'][$state['section']]['tabs']['timesheets.months']);

			if ( $state['tab']=='timesheets.weeks' or $state['tab']=='timesheets.months' )
				$state['tab']='timesheets.days';

		}elseif ($state['parent']=='month') {

            unset($modules[$state['module']]['sections'][$state['section']]['tabs']['timesheets.months']);

			if ( $state['tab']=='timesheets.months' )
				$state['tab']='timesheets.weeks';

		}
	}

	$response['tabs']=get_tabs($state, $modules);// todo only calculate when is subtabs in the section



	if ($state['object']!=''  and $modules[$state['module']]['sections'][$state['section']]['type']=='object') {
		$response['object_showcase']=get_object_showcase($state);
	}else {
		$response['object_showcase']='';
	}


	$response['tab']=get_tab($state['tab'], $state['subtab'], $state);

	unset($state['_object']);
	$response['state']=$state;





	echo json_encode($response);

	break;
case 'tab':
	$data=prepare_values($_REQUEST, array(
			'tab'=>array('type'=>'string'),
			'subtab'=>array('type'=>'string'),
			'state'=>array('type'=>'json array'),
		));


	$response=array(
		'tab'=>get_tab($data['tab'], $data['subtab'], $data['state'])
	);




	echo json_encode($response);
	break;


default:
	$response=array('state'=>404, 'resp'=>'Operation not found 2');
	echo json_encode($response);

}

function get_tab($tab, $subtab, $state=false) {

	global $smarty, $user, $db, $account;

	$_tab=$tab;
	$_subtab=$subtab;

	$actual_tab=($subtab!=''?$subtab:$tab);
	$state['tab']=$actual_tab;

	$smarty->assign('data', $state);



	if (file_exists('tabs/'.$actual_tab . '.tab.php')) {
		include_once 'tabs/'.$actual_tab . '.tab.php';
	}else {
		$html='Tab Not found: >'.$actual_tab.'<';

	}


	if (is_array($state)) {


		$_SESSION['state'][$state['module']][$state['section']]['tab']=$_tab;
		if ($_subtab!='') {
			$_SESSION['tab_state'][$_tab]=$_subtab;
		}

	}
	return $html;

}



function get_object_showcase($data) {




	switch ($data['object']  ) {
	case 'store':
	case 'website':
		$html='';
		break;
	case 'account':
		include_once 'showcase/account.show.php';
		$html=get_account_showcase($data);
		break;
	case 'employee':
		include_once 'showcase/employee.show.php';
		$html=get_employee_showcase($data);
		break;
	case 'contractor':
		include_once 'showcase/contractor.show.php';
		$html=get_contractor_showcase($data);
		break;
	case 'customer':
		include_once 'showcase/customer.show.php';
		$html=get_customer_showcase($data);
		break;
	case 'order':
		include_once 'showcase/order.show.php';
		$html=get_order_showcase($data);
		break;
	case 'invoice':
		include_once 'showcase/invoice.show.php';
		$html=get_invoice_showcase($data);
		break;
	case 'delivery_note':
		include_once 'showcase/delivery_note.show.php';
		$html=get_delivery_note_showcase($data);
		break;
	case 'user':
		include_once 'showcase/user.show.php';
		$html=get_user_showcase($data);
		break;
	case 'warehouse':
		include_once 'showcase/warehouse.show.php';
		$html=get_warehouse_showcase($data);
		break;
	case 'timesheet':
		include_once 'showcase/timesheet.show.php';
		$html=get_timesheet_showcase($data);
		break;
	default:
		$html=$data['object'].' -> '.$data['key'];
		break;
	}
	return $html;

}


function get_menu($data) {

	global $user, $smarty;




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
	case ('products_server'):
		require_once 'navigation/products.nav.php';
		switch ($data['section']) {
		case 'stores':
			return get_stores_navigation($data);
			break;
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
	case ('orders_server'):
		require_once 'navigation/orders.nav.php';
		switch ($data['section']) {
		case ('delivery_notes'):
		case ('orders'):
		case ('invoices'):
		case ('payments'):
			return get_orders_server_navigation($data);
			break;
		}

		break;

	case ('orders'):
		require_once 'navigation/orders.nav.php';
		switch ($data['section']) {
		case ('delivery_notes'):
		case ('orders'):
		case ('invoices'):
		case ('payments'):
			return get_orders_navigation($data);
			break;
		case ('order'):
			return get_order_navigation($data);
			break;
		case ('invoice'):
			return get_invoice_navigation($data);
			break;
		case ('delivery_note'):
			return get_delivery_note_navigation($data);
			break;
		case ('pick_aid'):
			return get_pick_aid_navigation($data);
			break;
		case ('pack_aid'):
			return get_pack_aid_navigation($data);
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
		case ('page'):
			return get_page_navigation($data);
			break;
		case ('website.user'):
			return get_user_navigation($data);
			break;
		default:
			return 'View not found';

		}
		break;
	case ('marketing_server'):
		require_once 'navigation/marketing.nav.php';
		switch ($data['section']) {
		case ('marketing'):

			return get_marketing_server_navigation($data);
			break;
		}

		break;

	case ('marketing'):
		require_once 'navigation/marketing.nav.php';
		switch ($data['section']) {

		case ('deals'):
			return get_deals_navigation($data);
			break;

		case ('enewsletters'):
			return get_enewsletters_navigation($data);
			break;
		case ('mailshots'):

			return get_mailshots_navigation($data);
			break;
		case ('marketing_post'):

			return get_marketing_post_navigation($data);
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



	case ('reports'):
		require_once 'navigation/reports.nav.php';
		switch ($data['section']) {
		case ('reports'):
			return get_reports_navigation($data);
			break;
		case ('performance'):
			return get_performance_navigation($data);
			break;

		case ('sales'):
			return get_sales_navigation($data);
			break;
		case ('tax'):

			return get_tax_navigation($data);
			break;
		}
	case ('suppliers'):
		require_once 'navigation/suppliers.nav.php';
		switch ($data['section']) {

		case ('supplier'):
			return get_supplier_navigation($data);
			break;

		case ('suppliers'):
			return get_suppliers_navigation($data);
			break;
		case ('categories'):

			return get_suppliers_categories_navigation($data);
			break;
		case ('category'):

			return get_suppliers_category_navigation($data);
			break;
		case ('lists'):
			return get_suppliers_lists_navigation($data);
			break;
		case ('list'):
			return get_suppliers_list_navigation($data);
			break;
		case ('dashboard'):
			return get_suppliers_dashboard_navigation($data);
			break;
		}

		break;
	case ('inventory'):
		require_once 'navigation/inventory.nav.php';
		switch ($data['section']) {


		case ('inventory'):
			return get_inventory_navigation($data);
			break;

		case ('part'):
			return get_part_navigation($data);
			break;
		case ('transactions'):
			return get_transactions_navigation($data);
			break;
		case ('stock_history'):
			return get_stock_history_navigation($data);
			break;
		case ('categories'):
			return get_categories_navigation($data);
			break;
		case ('category'):
			return get_category_navigation($data);
			break;
		}

		break;
	case ('warehouses'):
	case ('warehouses_server'):
		require_once 'navigation/warehouses.nav.php';
		switch ($data['section']) {

		case ('warehouses'):
			return get_warehouses_navigation($data);
			break;
		case ('warehouse'):
			return get_warehouse_navigation($data);
			break;

		case ('locations'):
			return get_locations_navigation($data);
			break;

		}

		break;

	case ('hr'):
		require_once 'navigation/hr.nav.php';

		switch ($data['section']) {

		case ('employees'):
		case ('new_timesheet_record'):
			return get_employees_navigation($data);
			break;
		case ('contractors'):
			return get_contractors_navigation($data);
			break;
		case ('organization'):
			return get_organization_navigation($data);
			break;
		case ('employee'):
			return get_employee_navigation($data);
			break;
		case ('employee.new'):
			return get_new_employee_navigation($data);
			break;
		case ('contractor'):
			return get_contractor_navigation($data);
			break;
		case ('contractor.new'):
			return get_new_contractor_navigation($data);
			break;
		case ('timesheet'):
			return get_timesheet_navigation($data);
			break;
		case ('timesheets'):
			return get_timesheets_navigation($data);
			break;
		case ('employee.attachment.new'):
			return get_new_employee_attachment_navigation($data);
			break;
		}

		break;



	case ('utils'):
		require_once 'navigation/utils.nav.php';
		switch ($data['section']) {
		case ('forbidden'):
		case ('not_found'):
			return get_utils_navigation($data);
			break;
		case ('fire'):
			return get_fire_navigation($data);
			break;
		}

		break;
	case ('profile'):
		require_once 'navigation/users.nav.php';
		return get_profile_navigation($data);
		break;
	case ('account'):
		require_once 'navigation/account.nav.php';

		switch ($data['section']) {
		case ('account'):
			return get_account_navigation($data);
			break;
		case ('users'):
			return get_users_navigation($data);
			break;
		case ('staff'):
			return get_staff_navigation($data);
			break;
		case ('suppliers'):
			return get_suppliers_navigation($data);
			break;
		case ('warehouse'):
			return get_warehouse_navigation($data);
			break;
		case ('root'):
			return get_root_navigation($data);
			break;
		case ('staff.user'):
			return get_staff_user_navigation($data);
			break;
		case ('suppliers.user'):
			return get_supplierss_user_navigation($data);
			break;

		case ('warehouse.user'):
			return get_warehouse_user_navigation($data);
			break;
		case ('root.user'):
			return get_root_user_navigation($data);
			break;
		case ('settings'):
			return get_settings_navigation($data);
			break;
		case ('staff.user.api_key') :
			return get_api_key_navigation($data);
			break;
		case ('staff.user.api_key.new') :
			return get_new_api_key_navigation($data);
			break;
		case ('payment_service_provider'):
			require_once 'navigation/payments.nav.php';
			return get_payment_service_provider_navigation($data);
			break;
		case ('payment_account'):
			require_once 'navigation/payments.nav.php';
			return get_payment_account_navigation($data);
			break;
		case ('payment'):
			require_once 'navigation/payments.nav.php';
			return get_payment_navigation($data);
			break;


		}



		break;
	case ('settings'):
		require_once 'navigation/account.nav.php';
		return get_settings_navigation($data);
		break;
	default:
		return 'Module not found';
	}

}




function get_tabs($data, $modules) {
	global $user, $smarty;



	if (isset($modules[$data['module']]['sections'][$data['section']]['tabs'])) {
		$tabs=$modules[$data['module']]['sections'][$data['section']]['tabs'];
	}else {
		$tabs=array();
	}



	if (isset($modules[$data['module']]['sections'][$data['section']]['tabs'][$data['tab']] ['subtabs'])) {

		$subtabs=$modules[$data['module']]['sections'][$data['section']]['tabs'][$data['tab']]['subtabs'];
	}else {
		$subtabs=array();
	}


	if (isset($tabs[$data['tab']]) ) {
		$tabs[$data['tab']]['selected']=true;
	}


	if (isset($subtabs[$data['subtab']]) )$subtabs[$data['subtab']]['selected']=true;

	$_content=array(
		'tabs'=>$tabs,
		'subtabs'=>$subtabs



	);



	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('tabs.tpl');

	return $html;
}


function get_view_position($state) {
	global $user, $smarty, $account;

	$state['current_store']='';
	$state['current_website']='';
	$state['current_warehouse']='';

	$branch=array(array('label'=>_('Home'), 'icon'=>'home', 'reference'=>''));

	switch ($state['module']) {

	case 'products':

		if ( $user->get_number_stores()>1) {


			$branch[]=array('label'=>_('Stores'), 'icon'=>'bars', 'reference'=>'stores');

		}
		if ($state['section']=='store') {
			$branch[]=array('label'=>_('Store').' <span class="id">'.$state['_object']->get('Store Code').'</span>', 'icon'=>'', 'reference'=>'store/'.$state['_object']->id);
			$state['current_store']=$state['_object']->id;

		}elseif ($state['section']=='department') {

			$store=new Store($state['parent_key']);
			$branch[]=array('label'=>_('Store').' <span class="id">'.$store->get('Store Code').'</span>', 'icon'=>'', 'reference'=>'store/'.$store->id);
			$branch[]=array('label'=>_('Department').' <span class="id">'.$state['_object']->get('Product Department Code').'</span>', 'icon'=>'', 'reference'=>$state['parent'].'/'.$state['parent_key'].'/department/'.$state['_object']->id);
			$state['current_store']=$store->id;

		}elseif ($state['section']=='family') {

			if ($state['parent']=='store') {
				$store=new Store($state['parent_key']);
				$branch[]=array('label'=>_('Store').' <span class="id">'.$store->get('Store Code').'</span>', 'icon'=>'', 'reference'=>'store/'.$store->id);
				$state['current_store']=$store->id;
			}elseif ($state['parent']=='department') {
				$department=new Department($state['parent_key']);
				$store=new Store($department->get('Product Department Store Key'));
				$branch[]=array('label'=>_('Store').' <span class="id">'.$store->get('Store Code').'</span>', 'icon'=>'', 'reference'=>'store/'.$store->id);
				$branch[]=array('label'=>_('Department').' <span class="id">'.$department->get('Product Department Code').'</span>', 'icon'=>'', 'reference'=>'store/'.$store->id.'/department/'.$department->id);
				$state['current_store']=$store->id;
			}
			$branch[]=array('label'=>_('Family').' <span class="id">'.$state['_object']->get('Product Family Code').'</span>', 'icon'=>'', 'reference'=>$state['parent'].'/'.$state['parent_key'].'/family/'.$state['_object']->id);

		}elseif ($state['section']=='product') {

			if ($state['parent']=='store') {
				$store=new Store($state['parent_key']);
				$branch[]=array('label'=>_('Store').' <span class="id">'.$store->get('Store Code').'</span>', 'icon'=>'', 'reference'=>'store/'.$store->id);
			}elseif ($state['parent']=='department') {
				$department=new Department($state['parent_key']);
				$store=new Store($department->get('Product Department Store Key'));
				$branch[]=array('label'=>_('Store').' <span class="id">'.$store->get('Store Code').'</span>', 'icon'=>'', 'reference'=>'store/'.$store->id);
				$branch[]=array('label'=>_('Department').' <span class="id">'.$department->get('Product Department Code').'</span>', 'icon'=>'', 'reference'=>'store/'.$store->id.'/department/'.$department->id);
			}elseif ($state['parent']=='family') {



				$family=new Family($state['parent_key']);
				$department=new Department($family->get('Product Family Main Department Key'));
				$store=new Store($department->get('Product Department Store Key'));
				$branch[]=array('label'=>_('Store').' <span class="id">'.$store->get('Store Code').'</span>', 'icon'=>'', 'reference'=>'store/'.$store->id);
				$branch[]=array('label'=>_('Department').' <span class="id">'.$department->get('Product Department Code').'</span>', 'icon'=>'', 'reference'=>'store/'.$store->id.'/department/'.$department->id);
				$branch[]=array('label'=>_('Family').' <span class="id">'.$family->get('Product Family Code').'</span>', 'icon'=>'', 'reference'=>'department/'.$department->id.'/family/'.$family->id);
			}elseif ($state['parent']=='order') {
				$order=new Order($state['parent_key']);
				$store=new Store($order->get('Order Store Key'));
				$branch=array(array('label'=>_('Home'), 'icon'=>'home', 'reference'=>''));

				if ( $user->get_number_stores()>1) {
					$branch[]=array('label'=>_('Orders').' ('._('All stores').')', 'icon'=>'bars', 'reference'=>'orders/all');
				}

				$branch[]=array('label'=>_('Orders').' '.$store->data['Store Code'], 'icon'=>'', 'reference'=>'orders/'.$store->id);

				$branch[]=array('label'=>_('Order').' '.$order->get('Order Public ID'), 'icon'=>'shopping-cart', 'reference'=>'orders/'.$store->id.'/'.$state['parent_key']);


			}
			$state['current_store']=$store->id;
			$_ref=$state['parent'].'/'.$state['parent_key'].'/product/'.$state['_object']->id;
			if (isset($state['otf'])) {
				$_ref=$state['parent'].'/'.$state['parent_key'].'/item/'.$state['otf'];
			}

			$branch[]=array('label'=>_('Product').' <span class="id">'.$state['_object']->get('Product Code').'</span>', 'icon'=>'', 'reference'=>$_ref);

		}elseif ($state['section']=='products') {

			if ($state['object']=='store') {
				$store=new Store($state['key']);
				$branch[]=array('label'=>$store->get('Store Code'), 'icon'=>'', 'reference'=>'store/'.$store->id);
				$state['current_store']=$store->id;
			}


		}elseif ($state['section']=='categories') {
			$branch[]=array('label'=>_('Pending orders (All stores)'), 'icon'=>'bars', 'reference'=>'pending_orders/all');
		}


		break;
	case 'customers_server':
		if ($state['section']=='customers')
			$branch[]=array('label'=>_('Customers (All stores)'), 'icon'=>'bars', 'reference'=>'customers/all');
		elseif ($state['section']=='pending_orders')
			$branch[]=array('label'=>_('Pending orders (All stores)'), 'icon'=>'bars', 'reference'=>'pending_orders/all');

		break;


	case 'customers':


		switch ($state['parent']) {
		case 'store':
			$store=new Store($state['parent_key']);
			$state['current_store']=$store->id;

			break;


		}




		if ( $user->get_number_stores()>1) {

			if ($state['section']=='pending_orders')
				$branch[]=array('label'=>_('Pending orders (All stores)'), 'icon'=>'bars', 'reference'=>'pending_orders/all');
			else
				$branch[]=array('label'=>_('Customers (All stores)'), 'icon'=>'bars', 'reference'=>'customers/all');

		}

		switch ($state['section']) {
		case 'list':
			$list=new SubjectList($state['key']);
			$store=new Store($list->data['List Parent Key']);


			$branch[]=array('label'=>_("Customer's lists").' '.$store->data['Store Code'], 'icon'=>'list', 'reference'=>'customers/'.$store->id.'/lists');
			$branch[]=array('label'=>$list->get('List Name'), 'icon'=>'', 'reference'=>'customers/list/'.$list->id);

			break;

		case 'customer':

			if ($state['parent']=='store') {
				$customer=new Customer($state['key']);
				if ($customer->id) {


					$store=new Store($customer->data['Customer Store Key']);


					$branch[]=array('label'=>_('Customers').' '.$store->data['Store Code'], 'icon'=>'users', 'reference'=>'customers/'.$store->id);
					$branch[]=array('label'=>_('Customer').' '.$customer->get_formated_id(), 'icon'=>'user', 'reference'=>'customer/'.$customer->id);
				}
			}elseif ($state['parent']=='list') {
				$customer=new Customer($state['key']);
				$store=new Store($customer->data['Customer Store Key']);

				$list=new SubjectList($state['parent_key']);

				$branch[]=array('label'=>_("Customer's lists").' '.$store->data['Store Code'], 'icon'=>'list', 'reference'=>'customers/'.$store->id.'/lists');
				$branch[]=array('label'=>$list->get('List Name'), 'icon'=>'', 'reference'=>'customers/list/'.$list->id);


				$branch[]=array('label'=>_('Customer').' '.$customer->get_formated_id(), 'icon'=>'user', 'reference'=>'customer/'.$customer->id);
			}
			break;
		case 'dashboard':
			$branch[]=array('label'=>_("Customer's dashboard").' '.$store->data['Store Code'], 'icon'=>'dashboard', 'reference'=>'customers/dashboard/'.$store->id);
			break;
		case 'customers':
			$branch[]=array('label'=>_('Customers').' '.$store->data['Store Code'], 'icon'=>'users', 'reference'=>'customers/'.$store->id);
			break;

		case 'categories':
			$branch[]=array('label'=>_("Customer's categories").' '.$store->data['Store Code'], 'icon'=>'sitemap', 'reference'=>'customers/categories/'.$store->id);
			break;
		case 'lists':
			$branch[]=array('label'=>_("Customer's lists").' '.$store->data['Store Code'], 'icon'=>'list', 'reference'=>'customers/'.$store->id.'/lists');
			break;
		case 'statistics':
			$branch[]=array('label'=>_("Customer's stats").' '.$store->data['Store Code'], 'icon'=>'line-chart', 'reference'=>'customers/statistics/'.$store->id);
			break;
		case 'pending_orders':
			$branch[]=array('label'=>_("Pending orders").' '.$store->data['Store Code'], 'icon'=>'clock-o', 'reference'=>'customers/pending_orders/'.$store->id);
			break;
		}
		break;
	case 'orders_server':
		if ( $user->get_number_stores()>1) {
			$branch[]=array('label'=>_('Orders').' ('._('All stores').')', 'icon'=>'bars', 'reference'=>'orders/all');
		}
		break;
	case 'orders':
		switch ($state['section']) {
		case 'orders':

			if ( $user->get_number_stores()>1) {
				$branch[]=array('label'=>_('Orders').' ('._('All stores').')', 'icon'=>'bars', 'reference'=>'orders/all');
			}
			$store=new Store($state['parent_key']);

			$branch[]=array('label'=>_('Orders').' '.$store->data['Store Code'], 'icon'=>'', 'reference'=>'orders/'.$store->id);


			break;
		case 'invoices':
			if ( $user->get_number_stores()>1) {
				$branch[]=array('label'=>_('Invoices').' ('._('All stores').')', 'icon'=>'bars', 'url'=>'invoices/all');
			}
			break;
		case 'dn':
			if ( $user->get_number_stores()>1) {
				$branch[]=array('label'=>_('Delivery Notes').' ('._('All stores').')', 'icon'=>'bars', 'url'=>'dn/all');
			}
			break;
		case 'payments':
			if ( $user->get_number_stores()>1) {
				$branch[]=array('label'=>_('Payments').' ('._('All stores').')', 'icon'=>'bars', 'url'=>'payments/all');
			}
			break;

		case 'order':

			if ($state['parent']=='customer') {

				$customer=new Customer($state['parent_key']);
				if ($customer->id) {
					if ( $user->get_number_stores()>1) {


						$branch[]=array('label'=>_('Customers (All stores)'), 'icon'=>'bars', 'reference'=>'customers/all');

					}

					$store=new Store($customer->data['Customer Store Key']);


					$branch[]=array('label'=>_('Customers').' '.$store->data['Store Code'], 'icon'=>'users', 'reference'=>'customers/'.$store->id);
					$branch[]=array('label'=>_('Customer').' '.$customer->get_formated_id(), 'icon'=>'user', 'reference'=>'customer/'.$customer->id);
				}



			}
			else {
				$store=new Store($state['_object']->data['Order Store Key']);

				if ( $user->get_number_stores()>1) {
					$branch[]=array('label'=>_('Orders').' ('._('All stores').')', 'icon'=>'bars', 'reference'=>'orders/all');
				}
				$branch[]=array('label'=>_('Orders').' '.$store->data['Store Code'], 'icon'=>'', 'reference'=>'orders/'.$store->id);


			}
			$branch[]=array('label'=>_('Order').' '.$state['_object']->get('Order Public ID'), 'icon'=>'shopping-cart', 'reference'=>'');

			break;

		}

		break;

	case 'help':
		switch ($state['section']) {
		case 'help':
			$branch[]=array('label'=>_('Help'), 'icon'=>'', 'reference'=>'help');
			break;


		}
		break;
	case 'hr':
		switch ($state['section']) {
		case 'employees':
			$branch[]=array('label'=>_('Employees'), 'icon'=>'', 'reference'=>'hr');
			break;

		case 'employee':
			$branch[]=array('label'=>_('Employees'), 'icon'=>'', 'reference'=>'hr');

			$branch[]=array('label'=>_('Employee').' <span class="id Staff_Alias">'.$state['_object']->get('Staff Alias').'</span>', 'icon'=>'', 'reference'=>'employee/'.$state['_object']->id);

			break;
		}
		break;
	case 'inventory':
		$branch[]=array('label'=>_('Inventory'), 'icon'=>'', 'reference'=>'inventory');

		break;

	case 'websites':


		if ( $user->get_number_websites()>1) {

			$branch[]=array('label'=>_('Websites'), 'icon'=>'bars', 'reference'=>'websites');

		}
		switch ($state['section']) {
		case 'website':

			$website=$state['_object'];

			$branch[]=array('label'=>_('Website').' '.$website->data['Site Code'], 'icon'=>'globe', 'reference'=>'website/'.$website->id);
			break;
		case 'page':
			$page=$state['_object'];
			$website=new Site($page->get('Page Site Key'));
			$branch[]=array('label'=>_('Website').' '.$website->data['Site Code'], 'icon'=>'globe', 'reference'=>'website/'.$website->id);
			$branch[]=array('label'=>_('Page').' '.$page->data['Page Code'], 'icon'=>'file', 'reference'=>'website/'.$website->id.'/page/'.$website->id);

			break;
		case 'website.user':

			if ($state['parent']=='website') {
				$website=new Site($state['parent_key']);
			}elseif ($state['parent']=='page') {
				$page=new Page($state['parent_key']);

				$website=new Site($page->get('Page Site Key'));

			}

			$branch[]=array('label'=>_('Website').' '.$website->data['Site Code'], 'icon'=>'globe', 'reference'=>'website/'.$website->id);

			if ($state['parent']=='page') {

				$branch[]=array('label'=>_('Page').' '.$page->data['Page Code'], 'icon'=>'file', 'reference'=>'website/'.$website->id.'/page/'.$page->id);

			}

			$branch[]=array('label'=>_('User').' '.$state['_object']->data['User Handle'], 'icon'=>'user', 'reference'=>'website/'.$website->id.'/user/'.$state['_object']->id);

			break;
		}

		break;

	case 'profile':
		$branch[]=array('label'=>_('My profile').' <span class="id">'.$user->get('User Alias').'</span>', 'icon'=>'', 'reference'=>'profile');


		break;
	case 'account':

		$branch[]=array('label'=>_('Account').' <span class="id">'.$account->get('Account Code').'</span>', 'icon'=>'', 'reference'=>'account');
		if ($state['section']=='users') {
			$branch[]=array('label'=>_('Users'), 'icon'=>'', 'reference'=>'account/users');

		}elseif ($state['section']=='staff') {
			$branch[]=array('label'=>_('Users'), 'icon'=>'', 'reference'=>'account/users');

			$branch[]=array('label'=>_('Staff users'), 'icon'=>'', 'reference'=>'account/users/staff');

		}elseif ($state['section']=='staff.user') {
			$branch[]=array('label'=>_('Users'), 'icon'=>'', 'reference'=>'account/users');

			$branch[]=array('label'=>_('Staff users'), 'icon'=>'', 'reference'=>'account/users/staff');
			$branch[]=array('label'=>_('User').' <span id="id">'.$state['_object']->data['User Alias'].'</span>', 'icon'=>'male', 'reference'=>'account/user/'.$state['_object']->id);

		}elseif ($state['section']=='settings') {
			$branch[]=array('label'=>_('Settings'), 'icon'=>'cog', 'reference'=>'account/settings');

		}elseif ($state['section']=='payment_service_provider') {
			$branch[]=array('label'=>_('Payment option').'  <span id="id">'.$state['_object']->get('Payment Service Provider Code').'</span>', 'icon'=>'', 'reference'=>'account/payment_service_provider/'.$state['_object']->id);

		}elseif ($state['section']=='payment_account') {

			$psp=new Payment_Service_Provider($state['_object']->get('Payment Service Provider Key'));

			$branch[]=array('label'=>_('Payment option').'  <span id="id">'.$psp->get('Payment Service Provider Code').'</span>', 'icon'=>'', 'reference'=>'account/payment_service_provider/'.$psp->id);

			$branch[]=array('label'=>_('Payment account').'  <span id="id">'.$state['_object']->get('Payment Account Code').'</span>', 'icon'=>'', 'reference'=>'account/payment_service_provider/'.$state['_object']->id);

		}elseif ($state['section']=='payment_account') {

			$psp=new Payment_Service_Provider($state['_object']->get('Payment Service Provider Key'));

			$branch[]=array('label'=>_('Payment option').'  <span id="id">'.$psp->get('Payment Service Provider Code').'</span>', 'icon'=>'', 'reference'=>'account/payment_service_provider/'.$psp->id);

			$branch[]=array('label'=>_('Payment account').'  <span id="id">'.$state['_object']->get('Payment Account Code').'</span>', 'icon'=>'', 'reference'=>'account/payment_service_provider/'.$state['_object']->id);

		}elseif ($state['section']=='payment') {

			include_once 'class.Payment_Service_Provider.php';
			include_once 'class.Payment_Account.php';

			$psp=new Payment_Service_Provider($state['_object']->get('Payment Service Provider Key'));
			$payment_account=new Payment_Account($state['_object']->get('Payment Account Key'));

			$branch[]=array('label'=>_('Payment option').'  <span id="id">'.$psp->get('Payment Service Provider Code').'</span>', 'icon'=>'', 'reference'=>'account/payment_service_provider/'.$psp->id);

			$branch[]=array('label'=>_('Payment account').'  <span id="id">'.$payment_account->get('Payment Account Code').'</span>', 'icon'=>'', 'reference'=>'payment_service_provider/'.$psp->id.'/payment_account/'.$payment_account->id);

			if ($state['parent']=='payment_service_provider') {
				$branch[]=array('label'=>_('Payment').'  <span id="id">'.$state['_object']->get('Payment Key').'</span>', 'icon'=>'', 'reference'=>'payment_service_provider/'.$psp->id.'/payment/'.$state['_object']->id);

			}elseif ($state['parent']=='payment_account') {
				$branch[]=array('label'=>_('Payment').'  <span id="id">'.$state['_object']->get('Payment Key').'</span>', 'icon'=>'', 'reference'=>'payment_account/'.$payment_account->id.'/payment/'.$state['_object']->id);

			}else {
				$branch[]=array('label'=>_('Payment').'  <span id="id">'.$state['_object']->get('Payment Key').'</span>', 'icon'=>'', 'reference'=>'account/payment/'.$state['_object']->id);
			}
		}


		break;
	}

	$_content=array(
		'branch'=>$branch,

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('view_position.tpl');
	return array($state, $html);



}







function parse_request_old($request) {

	global $user, $modules, $account;

	$original_request=preg_replace('/^\//', '', $request);
	$view_path=preg_split('/\//', $original_request);



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

			if (!array_key_exists($arg0, $modules)) {
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
			if (!array_key_exists($module, $modules)) {
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
				if (array_key_exists($arg, $modules[$module]['sections'])) {
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
			if (isset($_data['tab'])) {
				$tab=$_data['tab'];
			}else {
				if (isset ( $_SESSION['state'][$module][$section]['tab'])   ) {
					$tab=$_SESSION['state'][$module][$section]['tab'];
				}
				else {
					$tab='customer.details';
				}
			}

		}else {

			$module=array_shift($view_path);
			if (!array_key_exists($module, $modules)) {
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
				if (array_key_exists($arg, $modules[$module]['sections'])) {
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
			if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'], $user->stores)) {
				$parent_key=$user->data['User Hooked Store Key'];
			}else {
				$_tmp=$user->stores;
				$parent_key=array_shift($_tmp);
			}
		}
	}


	if ($parent=='store' and !in_array($parent_key, $user->stores)) {
		$module='utils';


		if (in_array($parent_key, $account->get_store_keys())) {
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



function get_help($data, $modules, $db) {

	$scope_state=parse_request($data, $db);


	$state=array(
		'request'=>$scope_state['request'],
		'module'=>'help',
		'section'=>'help',
		'tab'=>'help',
		'subtab'=>'',
		'parent'=>'',
		'parent_key'=>'',
		'object'=>'',
		'key'=>'',
	);

	$response=array('state'=>array());


	if ($data['old_state']['module']!=$state['module']   ) {
		$response['menu']=get_menu($state);

	}


	if ($data['old_state']['section']!=$state['section'] or
		$data['old_state']['parent_key']!=$state['parent_key'] or
		$data['old_state']['key']!=$state['key']

	) {

		require_once 'navigation/help.nav.php';
		$response['navigation']=get_help_navigation($data);

	}




	$response['tabs']=get_tabs($state, $modules);// todo only calculate when is subtabs in the section

	$response['view_position']=get_view_position($state);


	if ($state['object']!=''  and $modules[$state['module']]['sections'][$state['section']]['type']=='object') {
		$response['object_showcase']=get_object_showcase($state);
	}else {
		$response['object_showcase']='';
	}


	$response['tab']=get_tab($state['tab'], $state['subtab'], $state);

	unset($state['_object']);
	$response['state']=$state;


	echo json_encode($response);




}


?>
