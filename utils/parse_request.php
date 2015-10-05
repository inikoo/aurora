<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2015 23:49:27 GMT+8 Singapore
 Moved: 3 October 2015 at 08:57:36 BST Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function parse_request($_data) {
	global $user, $modules, $inikoo_account;


	$request=$_data['request'];

	$request=preg_replace('/\/+/', '/', $request);

	$original_request=preg_replace('/^\//', '', $request);
	$view_path=preg_split('/\//', $original_request);



	$module='utils';
	$section='not_found';
	$tab='not_found';
	$tab_parent='';
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
		case 'stores':
			$module='products';
			$section='stores';

			$tab='stores';

			break;
		case 'store':
			$module='products';
			$section='store';
			$object='store';
			if ($count_view_path==0 ) {
				if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'], $user->stores)) {
					$key=$user->data['User Hooked Store Key'];
				}else {
					$_tmp=$user->stores;
					$key=array_shift($_tmp);
				}
			}else {

				if (is_numeric($view_path[0])) {
					$key=array_shift($view_path);
				}
               
				if (isset($view_path[0])) {
					if ($view_path[0]=='department') {
						$module='products';
						$section='department';
						$object='department';
						$parent='store';
						$parent_key=$key;

						if (is_numeric($view_path[1])) {
							$key=$view_path[1];
						}

                      
					}
				}

			}
			list($tab, $subtab)=parse_tabs($module, $section, $_data, $modules);

			break;

		case 'department':
			$module='products';
			$section='department';
			$object='department';

			if (is_numeric($view_path[0])) {
				$key=array_shift($view_path);
			}
			
			if (isset($view_path[0])) {
					if ($view_path[0]=='family') {
						$module='products';
						$section='family';
						$object='family';
						$parent='department';
						$parent_key=$key;

						if (is_numeric($view_path[1])) {
							$key=$view_path[1];
						}

                      
					}
					elseif ($view_path[0]=='product') {
						$module='products';
						$section='product';
						$object='product';
						$parent='department';
						$parent_key=$key;

						if (is_numeric($view_path[1])) {
							$key=$view_path[1];
						}

                      
					}
				}
			

			list($tab, $subtab)=parse_tabs($module, $section, $_data, $modules);

			break;
		case 'family':
			$module='products';
			$section='family';
			$object='family';

			if (is_numeric($view_path[0])) {
				$key=array_shift($view_path);
			}

			list($tab, $subtab)=parse_tabs($module, $section, $_data, $modules);

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
		case 'websites':
			$module='websites';
			$section='websites';
			$tab='websites';
			break;
		case 'website':
			$module='websites';
			$section='website';



			if (isset($_data['tab'])) {
				$tab=$_data['tab'];
			}else {
				$tab='website.details';
			}

			$object='website';
			$key=$view_path[0];
			break;
		case 'customer':
			$module='customers';
			$section='customer';
			$object='customer';
			$key=$view_path[0];
			list($tab, $subtab)=parse_tabs($module, $section, $_data, $modules);
			break;
		case 'supplier':
			$module='suppliers';
			$section='supplier';
			$parent='suppliers';

			$tab='supplier.details';
			$object='supplier';

			$key=$view_path[0];

			break;
		case 'customers':
			$module='customers';
			if ($count_view_path==0) {
				$section='customers';
				$tab='customers';
				$parent='store';
				if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'], $user->stores)) {
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
						$parent='list';
						$parent_key=$list->id;
						$object='customer';
						$key=$view_path[1];
						list($tab, $subtab)=parse_tabs($module, $section, $_data, $modules);

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
						$parent='category';
						$parent_key=$category->id;
						$object='customer';
						$key=$view_path[1];
						list($tab, $subtab)=parse_tabs($module, $section, $_data, $modules);

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

						$parent='store';
						$parent_key=$arg1;
						$object='customer';
						$key=$view_path[0];

						list($tab, $subtab)=parse_tabs($module, $section, $_data, $modules);

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
				if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'], $user->stores)) {
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
				if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'], $user->stores)) {
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
		case 'delivery_notes':
			$module='orders';
			if ($count_view_path==0) {
				$section='delivery_notes';
				$tab='delivery_notes';
				$parent='store';
				if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'], $user->stores)) {
					$parent_key=$user->data['User Hooked Store Key'];
				}else {
					$_tmp=$user->stores;
					$parent_key=array_shift($_tmp);
				}

			}
			$arg1=array_shift($view_path);
			if ($arg1=='all') {
				$module='orders_server';
				$section='delivery_notes';
				$tab='orders_server.delivery_notes';


			}
			elseif (is_numeric($arg1)) {
				$section='delivery_notes';
				$tab='delivery_notes';
				$parent='store';
				$parent_key=$arg1;

				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$section='delivery_notes';
					$object='delivery_note';
					$tab='items';
					$parent='store';
					$parent_key=$arg1;
					$key=$view_path[0];

				}

			}
			break;
		case 'marketing':
			$module='marketing';
			if ($count_view_path==0) {
				$section='deals';
				$tab='offers';
				$parent='store';
				if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'], $user->stores)) {
					$parent_key=$user->data['User Hooked Store Key'];
				}else {
					$_tmp=$user->stores;
					$parent_key=array_shift($_tmp);
				}

			}
			$arg1=array_shift($view_path);
			if ($arg1=='all') {
				$module='marketing_server';
				$section='marketing';
				$tab='marketing_server';



			}

			elseif (is_numeric($arg1)) {

				$parent='store';
				$parent_key=$arg1;

				if (isset($view_path[0])) {



				}else {

					$section='deals';
					$tab='campaigns';
				}

			}
			break;
		case 'warehouses':
			$module='warehouses';
			$section='warehouses';
			$tab='warehouses';


			break;

		case 'warehouse':
			$module='warehouses';
			$section='warehouse';
			$tab='details';
			$object='warehouse';

			$key=$view_path[0];
			break;
		case 'inventory':
			$module='warehouses';
			$section='inventory';
			if (isset($_data['tab'])) {
				$tab=$_data['tab'];
			}else {
				$tab='inventory.parts';
			}
			$parent='warehouse';

			$parent_key=$view_path[0];
			break;
		case 'locations':
			$module='warehouses';
			$section='locations';

			if (isset($_data['tab'])) {
				$tab=$_data['tab'];
			}else {
				$tab='locations';
			}
			$parent='warehouse';

			$parent_key=$view_path[0];
			break;
		case 'suppliers':
			$module='suppliers';
			$section='suppliers';
			$tab='suppliers';



			if ( isset($view_path[0]) and  $view_path[0]=='list') {
				$section='list';
				$tab='suppliers.list';
				$object='list';




				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$key=$view_path[0];
					include_once 'class.List.php';
					$list=new SubjectList($key);
					$parent='store';
					$parent_key=$list->get('List Parent Key');


					if (isset($view_path[1]) and is_numeric($view_path[1])) {
						$section='supplier';

						$tab='supplier.details';
						$parent='list';
						$parent_key=$list->id;
						$object='supplier';
						$key=$view_path[1];

					}


				}else {
					//error
				}

			}
			elseif (isset($view_path[0]) and  $view_path[0]=='category') {
				$section='category';
				$tab='suppliers.category';
				$object='category';




				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$key=$view_path[0];
					include_once 'class.Category.php';
					$category=new Category($key);
					$parent='store';
					$parent_key=$category->get('Category Store Key');


					if (isset($view_path[1]) and is_numeric($view_path[1])) {
						$section='supplier';

						$tab='supplier.details';
						$parent='category';
						$parent_key=$category->id;
						$object='supplier';
						$key=$view_path[1];

					}


				}else {
					//error
				}

			}
			break;
		case 'hr':
			$module='hr';
			$section='employees';


			if (isset($_data['tab'])) {
				$tab=$_data['tab'];
			}else {
				$tab='employees';
			}

			break;
		case 'employee':

			$module='hr';
			$section='employee';
			$object='employee';
			$parent='none';
			if (isset($view_path[0]))
				$key=$view_path[0];
			list($tab, $subtab)=parse_tabs($module, $section, $_data, $modules);
			break;
		case 'reports':
			$module='reports';
			$section='reports';
			$tab='reports';
			/*
			$section='performance';


			if (isset($_data['tab'])) {
				$tab=$_data['tab'];
			}else {
				$tab='report.pp';
			}
            */
			break;
		case 'users':
			$module='users';
			$section='staff';


			if (isset($_data['tab'])) {
				$tab=$_data['tab'];
			}else {
				$tab='users.staff.users';
			}

			break;

		case 'user':

			$module='users';

			if (isset($view_path[0])) {

				if ($view_path[0]=='staff') {
					$parent='users';
					$section='staff.user';
					$object='user';
					$key=$view_path[1];
				}elseif ($view_path[0]=='suppliers') {

				}elseif ($view_path[0]=='warehouse') {

				}elseif ($view_path[0]=='rott') {

				}else {

					$key=$view_path[0];
				}

			}

			list($tab, $subtab)=parse_tabs($module, $section, $_data, $modules);
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


function parse_tabs($module, $section, $_data, $modules) {


	$subtab='';

	if (isset($_data['subtab'])) {
		$subtab=$_data['subtab'];


		$tab=$modules[$module]['sections'][$section]['subtabs_parent'][$subtab];


	}
	elseif (isset($_data['tab'])) {
		$tab=$_data['tab'];
		$subtab=parse_subtab($module, $section, $tab, $modules);
	}
	else {

		if (isset ( $_SESSION['state'][$module][$section]['tab'])   ) {
			$tab=$_SESSION['state'][$module][$section]['tab'];

		}
		else {
			$tab=each($modules[$module]['sections'][$section]['tabs'])['key'];
		}
		$subtab=parse_subtab($module, $section, $tab, $modules);
	}

	return array($tab, $subtab);

}


function parse_subtab($module, $section, $tab, $modules) {

	if ( isset(  $modules[$module]['sections'][$section]['tabs'][$tab]['subtabs']  ) ) {
		if (isset ( $_SESSION['tab_state'][$tab])   ) {
			$subtab= $_SESSION['tab_state'][$tab];
		}else {
			$subtab= each( $modules[$module]['sections'][$section]['tabs'][$tab]['subtabs'] )['key'];

		}
	}else {
		$subtab='';
	}

	return $subtab;
}


?>
