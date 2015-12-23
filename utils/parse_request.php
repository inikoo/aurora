<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2015 23:49:27 GMT+8 Singapore
 Moved: 3 October 2015 at 08:57:36 BST Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function parse_request($_data, $db) {
	global $user, $modules, $account;



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
		case 'index.php':
		case 'dashboard':
			$module='dashboard';
			$section='dashboard';
			break;
		case 'stores':
			$module='products_server';
			$section='stores';


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
					if ($view_path[0]=='product') {
						$module='products';
						$section='product';
						$object='product';
						$parent='store';
						$parent_key=$key;

						if (is_numeric($view_path[1])) {
							$key=$view_path[1];
						}


					}
				}

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

			break;
		case 'website':
			$module='websites';
			$section='website';
			$object='website';
			$key=$view_path[0];


			if (isset($view_path[1])) {
				if ($view_path[1]=='page') {
					$section='page';
					$object='page';
					$parent='website';
					$parent_key=$key;

					if (is_numeric($view_path[2])) {
						$key=$view_path[2];
					}


				}
				elseif ($view_path[1]=='user') {
					$section='website.user';
					$object='user';
					$parent='website';
					$parent_key=$key;

					if (is_numeric($view_path[2])) {
						$key=$view_path[2];
					}


				}

			}


			break;
		case 'page':
			$module='websites';
			$section='page';
			$object='page';
			$key=$view_path[0];
			if (isset($view_path[1])) {
				if ($view_path[1]=='user') {
					$section='website.user';
					$object='user';
					$parent='page';
					$parent_key=$key;

					if (is_numeric($view_path[2])) {
						$key=$view_path[2];
					}


				}


			}

			break;
		case 'customer':
			$module='customers';
			$section='customer';
			$object='customer';
			$key=$view_path[0];

			if (isset($view_path[1])) {
				if ($view_path[1]=='order') {


					$module='orders';
					$section='order';
					$parent='customer';
					$parent_key=$key;
					$object='order';
					$key=$view_path[2];

				}


			}



			break;
		case 'supplier':
			$module='suppliers';
			$section='supplier';
			$parent='suppliers';

			$object='supplier';

			$key=$view_path[0];

			break;
		case 'customers':
			$module='customers';
			if ($count_view_path==0) {
				$section='customers';
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

				if (isset($view_path[0]) and $view_path[0]=='pending_orders') {
					$section='pending_orders';

				}

			}
			elseif ($arg1=='list') {
				$section='list';
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

					}


				}else {
					//error
				}

			}
			elseif ($arg1=='category') {
				$section='category';
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

					}


				}else {
					//error
				}

			}
			elseif (is_numeric($arg1)) {
				$section='customers';
				$parent='store';
				$parent_key=$arg1;

				if (isset($view_path[0])) {

					if ( is_numeric($view_path[0])) {
						$section='customer';

						$parent='store';
						$parent_key=$arg1;
						$object='customer';
						$key=$view_path[0];


					}elseif ($view_path[0]=='lists') {
						$section='lists';
					}elseif ($view_path[0]=='categories') {
						$section='categories';
					}

				}

			}
			break;
		case 'orders':
			$module='orders';
			if ($count_view_path==0) {
				$section='orders';

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




			}
			elseif (is_numeric($arg1)) {
				$section='orders';
				$parent='store';
				$parent_key=$arg1;

				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$section='order';
					$object='order';

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

				if (isset($view_path[0]) and $view_path[0]=='pending_orders') {
					$section='pending_orders';

				}

			}
			elseif (is_numeric($arg1)) {
				$section='invoices';
				$parent='store';
				$parent_key=$arg1;

				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$section='invoice';
					$object='invoice';
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


			}
			elseif (is_numeric($arg1)) {
				$section='delivery_notes';
				$parent='store';
				$parent_key=$arg1;

				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$section='delivery_note';
					$object='delivery_note';
					$parent='store';
					$parent_key=$arg1;
					$key=$view_path[0];

				}

			}
			break;
		case 'order':
			$module='orders';
			if ( isset($view_path[0])) {

				if ( is_numeric($view_path[0])) {
					$section='order';
					$object='order';

					$parent='';
					$parent_key='';
					$key=$view_path[0];

					if ( isset($view_path[1])) {
						if ( $view_path[1]=='item') {
							$module='products';
							$section='product';
							$object='product';
							$parent='order';
							$parent_key=$key;

							if (is_numeric($view_path[2])) {
								$otf=$view_path[2];
							}

							$sql=sprintf("select `Product ID` as `key` from `Order Transaction Fact` where `Order Transaction Fact Key`=%d", $otf);
							if ($row = $db->query($sql)->fetch()) {
								$key=$row['key'];
								$_data['otf']=$otf;
							}


						}
						elseif ( $view_path[1]=='delivery_note') {
							$section='delivery_note';
							$object='delivery_note';
							$parent='order';
							$parent_key=$key;

							if (is_numeric($view_path[2])) {
								$key=$view_path[2];
							}




						}
						elseif ( $view_path[1]=='pick_aid') {
							$section='pick_aid';
							$object='pick_aid';
							$parent='order';
							$parent_key=$key;

							if (is_numeric($view_path[2])) {
								$key=$view_path[2];
							}




						}
						elseif ( $view_path[1]=='invoice') {
							$section='invoice';
							$object='invoice';
							$parent='order';
							$parent_key=$key;

							if (is_numeric($view_path[2])) {
								$key=$view_path[2];
							}




						}
					}


				}



			}

			break;

		case 'delivery_note':
			$module='orders';
			if ( isset($view_path[0])) {

				if ( is_numeric($view_path[0])) {
					$section='delivery_note';
					$object='delivery_note';

					$parent='';
					$parent_key='';
					$key=$view_path[0];

					if ( isset($view_path[1])) {
						if ( $view_path[1]=='item') {
							$module='parts';
							$section='part';
							$object='part';
							$parent='delivery_note';
							$parent_key=$key;

							if (is_numeric($view_path[2])) {
								$otf=$view_path[2];
							}

							$sql=sprintf("select `Part SKU` as `key` from `Inventory Transaction Fact` where `Inventory Transaction Fact Key`=%d", $otf);
							if ($row = $db->query($sql)->fetch()) {
								$key=$row['key'];
								$_data['otf']=$otf;
							}


						}
						elseif ( $view_path[1]=='order') {
							$section='order';
							$object='order';
							$parent='delivery_note';
							$parent_key=$key;

							if (is_numeric($view_path[2])) {
								$key=$view_path[2];
							}




						}
						elseif ( $view_path[1]=='pick_aid') {
							$section='pick_aid';
							$object='pick_aid';
							$parent='delivery_note';
							$parent_key=$key;

							if (is_numeric($view_path[2])) {
								$key=$view_path[2];
							}




						}
						elseif ( $view_path[1]=='pack_aid') {
							$section='pack_aid';
							$object='pack_aid';
							$parent='delivery_note';
							$parent_key=$key;

							if (is_numeric($view_path[2])) {
								$key=$view_path[2];
							}




						}
						elseif ( $view_path[1]=='invoice') {
							$section='invoice';
							$object='invoice';
							$parent='delivery_note';
							$parent_key=$key;

							if (is_numeric($view_path[2])) {
								$key=$view_path[2];
							}




						}
					}


				}



			}

			break;
		case 'invoice':
			$module='orders';
			if ( isset($view_path[0])) {

				if ( is_numeric($view_path[0])) {
					$section='invoice';
					$object='invoice';

					$parent='';
					$parent_key='';
					$key=$view_path[0];

					if ( isset($view_path[1])) {
						if ( $view_path[1]=='item') {
							$module='products';
							$section='product';
							$object='product';
							$parent='invoice';
							$parent_key=$key;

							if (is_numeric($view_path[2])) {
								$otf=$view_path[2];
							}

							$sql=sprintf("select `Product ID` as `key` from `Order Transaction Fact` where `Order Transaction Fact Key`=%d", $otf);
							if ($row = $db->query($sql)->fetch()) {
								$key=$row['key'];
								$_data['otf']=$otf;
							}


						}
						elseif ( $view_path[1]=='order') {
							$section='order';
							$object='order';
							$parent='invoice';
							$parent_key=$key;

							if (is_numeric($view_path[2])) {
								$key=$view_path[2];
							}




						}

						elseif ( $view_path[1]=='delivery_note') {
							$section='delivery_note';
							$object='delivery_note';
							$parent='invoice';
							$parent_key=$key;

							if (is_numeric($view_path[2])) {
								$key=$view_path[2];
							}




						}
					}


				}



			}

			break;
		case 'marketing':
			$module='marketing';
			if ($count_view_path==0) {
				$section='deals';
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



			}

			elseif (is_numeric($arg1)) {

				$parent='store';
				$parent_key=$arg1;

				if (isset($view_path[0])) {



				}else {

					$section='deals';
				}

			}
			break;
		case 'warehouses':
			$module='warehouses_server';
			$section='warehouses';


			break;

		case 'warehouse':
			$module='warehouses';
			$section='warehouse';

			$object='warehouse';

			$key=$view_path[0];

			break;
		case 'inventory':
			$module='inventory';
			$section='inventory';



			if (isset($view_path[0])) {
				if ($view_path[0]=='part') {
					$section='part';
					$object='part';
					if (is_numeric($view_path[1])) {
						$key=$view_path[1];
					}


				}
				elseif ($view_path[0]=='transactions') {
					$section='transactions';
				}elseif ($view_path[0]=='stock_history') {
					$section='stock_history';
				}elseif ($view_path[0]=='categories') {
					$section='categories';




				}

			}


			break;
		case 'locations':
			$module='warehouses';
			$section='locations';


			$parent='warehouse';

			$parent_key=$view_path[0];
			break;

		case 'production':
			$module='production';
			$section='dashboard';
			$parent='account';
			$parent_key=1;

			if ( isset($view_path[0]) ) {
				if (  $view_path[0]=='manufacture_tasks') {
					$section='manufacture_tasks';

				}elseif (  $view_path[0]=='operatives') {
					$section='operatives';

				}
				elseif (  $view_path[0]=='batches') {
					$section='batches';

				}
				elseif (  $view_path[0]=='operative') {
					$section='operative';
					$object='operative';
					if ( isset($view_path[1]) ) {
						if (is_numeric($view_path[1])) {
							$key=$view_path[1];
						}elseif ($view_path[1]=='add') {
							$section='operative.add';
						}
					}

				}
				elseif (  $view_path[0]=='manufacture_task') {
					$section='manufacture_task';
					$object='manufacture_task';
					if ( isset($view_path[1]) ) {
						if (is_numeric($view_path[1])) {
							$key=$view_path[1];
						}elseif ($view_path[1]=='new') {
							$section='manufacture_task.new';

						}
					}

				}
			}

			break;

		case 'manufacture_task':
			$module='production';
			$section='manufacture_task';
			$object='manufacture_task';
			if ( isset($view_path[1]) ) {
				if (is_numeric($view_path[1])) {
					$key=$view_path[1];
				}elseif ($view_path[1]=='new') {
					$section='manufacture_task.new';
				}
			}
			break;

		case 'suppliers':
			$module='suppliers';
			$section='suppliers';



			if ( isset($view_path[0]) and  $view_path[0]=='list') {
				$section='list';
				$object='list';




				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$key=$view_path[0];
					include_once 'class.List.php';
					$list=new SubjectList($key);
					$parent='store';
					$parent_key=$list->get('List Parent Key');


					if (isset($view_path[1]) and is_numeric($view_path[1])) {
						$section='supplier';

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
				$object='category';




				if (isset($view_path[0]) and is_numeric($view_path[0])) {
					$key=$view_path[0];
					include_once 'class.Category.php';
					$category=new Category($key);
					$parent='store';
					$parent_key=$category->get('Category Store Key');


					if (isset($view_path[1]) and is_numeric($view_path[1])) {
						$section='supplier';

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
			$parent='account';
			$parent_key=1;
			if (isset($view_path[0])) {
				if ( $view_path[0]=='new_timesheet_record') {

					$section='new_timesheet_record';
				}elseif ( $view_path[0]=='contractors') {

					$section='contractors';
				}elseif ( $view_path[0]=='organization') {

					$section='organization';
				}

				elseif ($view_path[0]=='timesheet') {
					$section='timesheet';
					$object='timesheet';
					$parent='account';
					$parent_key=1;
					if (isset($view_path[1])) {
						if (is_numeric($view_path[1])) {
							$key=$view_path[1];
						}
					}



				}

			}




			break;

		case 'timesheet':

			$module='hr';
			$section='timesheet';
			$object='timesheet';
			$parent='account';
			$parent_key=1;

			if (isset($view_path[0])) {
				if (is_numeric($view_path[0])) {
					$key=$view_path[0];
				}
			}

			break;

		case 'timesheets':

			$module='hr';
			$section='timesheets';
			$object='';

			$parent='account';
			$parent_key=1;

			if (isset($view_path[0])) {

				if ($view_path[0]=='year') {
					$parent=$view_path[0];

				}elseif ($view_path[0]=='month') {
					$parent=$view_path[0];
				}elseif ($view_path[0]=='week') {
					$parent=$view_path[0];
				}elseif ($view_path[0]=='day') {
					$parent=$view_path[0];
				}

			}

			if (isset($view_path[1])) {
				$parent_key=$view_path[1];
			}

			break;

		case 'employee':

			$module='hr';
			$section='employee';
			$object='employee';
			$parent='account';
			$parent_key=1;

			if (isset($view_path[0])) {
				if (is_numeric($view_path[0])) {
					$key=$view_path[0];

					if (isset($view_path[1])) {
						if ($view_path[1]=='timesheet') {
							$section='timesheet';
							$object='timesheet';
							$parent='employee';
							$parent_key=$key;
							if (isset($view_path[2])) {
								if (is_numeric($view_path[2])) {
									$key=$view_path[2];
								}
							}



						}if ($view_path[1]=='attachment') {
							$section='employee.attachment';
							$object='attachment';
							$parent='employee';
							$parent_key=$key;
							if (isset($view_path[2])) {
								if (is_numeric($view_path[2])) {
									$key=$view_path[2];
								}
							}



						}
						else if ($view_path[1]=='new') {


							$parent='employee';
							$parent_key=$key;


							if (isset($view_path[2])) {
								if ($view_path[2]=='attachment') {

									$section='employee.attachment.new';
									$object='attachment';
								}
							}



						}
					}



				}elseif ($view_path[0]=='new') {
					$section='employee.new';
					$object='';

				}
			}

			break;

		case 'overtime':

			$module='hr';
			$section='overtime';
			$object='overtime';
			$parent='account';
			$parent_key=1;

			if (isset($view_path[0])) {
				if (is_numeric($view_path[0])) {
					$key=$view_path[0];




				}elseif ($view_path[0]=='new') {
					$section='overtime.new';
					$object='overtime';

				}
			}

			break;

		case 'contractor':

			$module='hr';
			$section='contractor';
			$object='contractor';
			$parent='account';
			$parent_key=1;

			if (isset($view_path[0])) {
				if (is_numeric($view_path[0])) {
					$key=$view_path[0];
				}elseif ($view_path[0]=='new') {
					$section='contractor.new';
					$object='';

				}
			}

			break;
		case 'reports':
			$module='reports';
			$section='reports';

			break;


		case 'report':
			$module='reports';

			if (isset($view_path[0])) {
				if ( $view_path[0]=='billingregion_taxcategory') {
					$section='billingregion_taxcategory';

					if (isset($view_path[1]) and isset($view_path[2]) and isset($view_path[3]) ) {

						if ( $view_path[1]=='invoices') {
							$section='billingregion_taxcategory.invoices';
							$parent='billingregion_taxcategory.invoices';
						}elseif ( $view_path[1]=='refunds') {
							$section='billingregion_taxcategory.refunds';
							$parent='billingregion_taxcategory.refunds';

						}


						$parent_key=$view_path[2].'_'.$view_path[3];


					}


				}

			}
			break;



		case 'profile':
			$module='profile';
			$section='profile';

			$object='user';
			$key=$user->id;

			break;
		case 'account':
			$module='account';
			$section='account';
			$object='account';
			$key=1;

			if (isset($view_path[0])) {
				$object='';
				if ( $view_path[0]=='users') {
					$section='users';

					if (isset($view_path[1])) {

						if ( $view_path[1]=='staff') {
							$section='staff';

						}
					}

				}
				elseif ($view_path[0]=='settings') {
					$section='settings';



				}

				elseif ($view_path[0]=='user') {

					if (isset($view_path[1])) {

						$parent='account';
						$parent_key=1;
						$section='staff.user';
						$object='user';
						$key=$view_path[1];


						if (isset($view_path[2])) {

							if ($view_path[2]=='new') {

								if (isset($view_path[3])) {

									if ($view_path[3]=='api_key') {

										$parent='user';
										$parent_key=$key;
										$section='staff.user.api_key.new';
										$object='api_key';

									}

								}
							}
							elseif ($view_path[2]=='api_key') {

								if (isset($view_path[3])) {

									if (is_numeric($view_path[3])) {

										$parent='user';
										$parent_key=$key;
										$section='staff.user.api_key';
										$object='api_key';

										$key=$view_path[3];
									}

								}
							}

						}



					}




				}
				elseif ($view_path[0]=='payment_service_provider') {

					if (isset($view_path[1])) {

						$section='payment_service_provider';
						$object='payment_service_provider';
						$parent='account';
						$key=$view_path[1];


					}




				}
				elseif ($view_path[0]=='payment_account') {
					if (isset($view_path[1])) {
						$section='payment_account';
						$object='payment_account';
						$parent='account';
						$key=$view_path[1];
					}




				}
				elseif ($view_path[0]=='payment') {

					if (isset($view_path[1])) {

						$section='payment';
						$object='payment';
						$parent='account';
						$key=$view_path[1];


					}




				}

			}




			break;
		case 'payment_service_provider':
			$module='account';
			$section='payment_service_provider';
			$parent='account';
			if (isset($view_path[0])) {

				if ( is_numeric($view_path[0])) {
					$object='payment_service_provider';
					$key=$view_path[0];

					if (isset($view_path[1])) {

						if ($view_path[1]=='payment_account') {
							$section='payment_account';
							$object='payment_account';
							$parent='payment_service_provider';
							$parent_key=$key;
							if (isset($view_path[2])) {
								$key=$view_path[2];
							}

						}
						elseif ($view_path[1]=='payment') {
							$section='payment';
							$object='payment';
							$parent='payment_service_provider';
							$parent_key=$key;
							if (isset($view_path[2])) {
								$key=$view_path[2];
							}

						}

					}



				}





			}
			break;
		case 'payment_account':
			$module='account';
			$section='payment_account';
			$parent='account';
			if (isset($view_path[0])) {

				if ( is_numeric($view_path[0])) {
					$object='payment_account';
					$key=$view_path[0];

					if (isset($view_path[1])) {

						if ($view_path[1]=='payment') {
							$section='payment';
							$object='payment';
							$parent='payment_account';
							$parent_key=$key;
							if (isset($view_path[2])) {
								$key=$view_path[2];
							}

						}

					}



				}





			}
			break;
		case 'fire':
			$module='utils';
			$section='fire';

		default:

			break;
		}

	}
	list($tab, $subtab)=parse_tabs($module, $section, $_data, $modules);
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

	if (isset($_data['otf'])) {
		$state['otf']=$_data['otf'];
	}
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
			if ( !isset($modules[$module]['sections'][$section]['tabs']) or  !is_array($modules[$module]['sections'][$section]['tabs']) or count($modules[$module]['sections'][$section]['tabs'])==0 ) {
				print "problem with M: $module S: $section";
			}
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
