<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 September 2015 13:12:32 GMT+8 Kuala Lumpur

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_store_navigation($data, $smarty, $user, $db, $account) {




	require_once 'class.Store.php';

	$store=$data['_object'];

	$block_view=$data['section'];



	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key, $next_key)=get_prev_next($store->id, $user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $prev_key);
		if ($result=$db->query($sql)) {
			if ($row = $result->fetch()) {
				$prev_title=_('Store').' '.$row['Store Code'];
			}else {
				$prev_title='';
			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}



		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $next_key);
		if ($result=$db->query($sql)) {
			if ($row = $result->fetch()) {
				$next_title=_('Store').' '.$row['Store Code'];
			}else {
				$next_title='';
			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}





		$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'store/'.$prev_key );
		$left_buttons[]=array('icon'=>'arrow-up', 'title'=>_('Stores'), 'reference'=>'stores', 'parent'=>'');

		$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'store/'.$next_key );
	}


	$right_buttons=array();
	$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit store'), 'reference'=>'store/'.$store->id.'/edit');
	$right_buttons[]=array('icon'=>'plus', 'title'=>_('New store'), 'id'=>"new_store");
	$sections=get_sections('products', $store->id);
	$_section='store';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Store').' <span class="id">'.$store->get('Code').'</span>',
		'search'=>array('show'=>true, 'placeholder'=>_('Search products').' '.$store->get('Store Code'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_store_dashboard_navigation($data, $smarty, $user, $db, $account) {




	require_once 'class.Store.php';

	$store=new Store($data['key']);

	$block_view=$data['section'];



	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key, $next_key)=get_prev_next($store->id, $user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $prev_key);
		if ($result=$db->query($sql)) {
			if ($row = $result->fetch()) {
				$prev_title=_('Store').' '.$row['Store Code'];
			}else {
				$prev_title='';
			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}



		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $next_key);
		if ($result=$db->query($sql)) {
			if ($row = $result->fetch()) {
				$next_title=_('Store').' '.$row['Store Code'];
			}else {
				$next_title='';
			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}





		$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'store/'.$prev_key );
		$left_buttons[]=array('icon'=>'arrow-up', 'title'=>_('Stores'), 'reference'=>'stores', 'parent'=>'');

		$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'store/'.$next_key );
	}


	$right_buttons=array();
	$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit store'), 'reference'=>'store/'.$store->id.'/edit');
	$right_buttons[]=array('icon'=>'plus', 'title'=>_('New store'), 'id'=>"new_store");
	$sections=get_sections('products', $store->id);
	$_section='dashboard';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Store's dashboard").' <span class="id">'.$store->get('Code').'</span>',
		'search'=>array('show'=>true, 'placeholder'=>_('Search products').' '.$store->get('Store Code'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


function get_products_navigation($data, $smarty, $user, $db, $account) {




	require_once 'class.Store.php';


	if ($data['parent']=='store') {



	}else {
		exit('');

	}

	$block_view=$data['section'];



	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key, $next_key)=get_prev_next($data['store']->id, $user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $prev_key);
		if ($result=$db->query($sql)) {
			if ($row = $result->fetch()) {
				$prev_title=_('Store').' '.$row['Store Code'];
			}else {
				$prev_title='';
			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}



		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $next_key);
		if ($result=$db->query($sql)) {
			if ($row = $result->fetch()) {
				$next_title=_('Store').' '.$row['Store Code'];
			}else {
				$next_title='';
			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}





		$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'products/'.$prev_key );
		$left_buttons[]=array('icon'=>'arrow-up', 'title'=>_('Stores'), 'reference'=>'stores', 'parent'=>'');

		$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'products/'.$next_key );
	}


	$right_buttons=array();
	$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit store'), 'reference'=>'store/'.$data['store']->id.'/edit');
	$right_buttons[]=array('icon'=>'plus', 'title'=>_('New store'), 'id'=>"new_store");

	$sections=get_sections('products', $data['store']->id);
	$_section='products';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Products').' <span class="id">'.$data['store']->get('Code').'</span>',
		'search'=>array('show'=>true, 'placeholder'=>_('Search products').' '.$data['store']->get('Store Code'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_products_categories_navigation($data, $smarty, $user, $db, $account) {




	require_once 'class.Store.php';

	switch ($data['parent']) {
	case 'store':
		$store=new Store($data['parent_key']);
		break;
	default:

		break;
	}

	$block_view=$data['section'];


	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key, $next_key)=get_prev_next($store->id, $user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $prev_key);
		if ($result=$db->query($sql)) {
			if ($row = $result->fetch()) {
				$prev_title=_("Products's Categories").' '.$row['Store Code'];
			}else {
				$prev_title='';
			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}


		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $next_key);

		if ($result=$db->query($sql)) {
			if ($row = $result->fetch()) {
				$next_title=_("Products's Categories").' '.$row['Store Code'];
			}else {
				$next_title='';
			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}



		$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'products/'.$next_key.'/categories');

		$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'products/'.$next_key.'/categories');
	}


	$right_buttons=array();

	//$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'), 'url'=>"edit_customer_categories.php?store_id=".$store->id);

	$sections=get_sections('products', $store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Product's Categories").' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true, 'placeholder'=>_('Search products'))

	);
	$smarty->assign('_content', $_content);
	$html=$smarty->fetch('navigation.tpl');
	return $html;

}



function get_stores_navigation($data, $smarty, $user, $db, $account) {



	$block_view=$data['section'];


	$sections=get_sections('products_server');


	$left_buttons=array();



	$right_buttons=array();

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;



	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Stores'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search products all stores'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;


}


function get_products_all_stores_navigation($data, $smarty, $user, $db, $account) {



	$block_view=$data['section'];


	$sections=get_sections('products_server');


	$left_buttons=array();



	$right_buttons=array();

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;



	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Product').' ('._('All stores').')',
		'search'=>array('show'=>true, 'placeholder'=>_('Search products all stores'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;


}



function get_product_navigation($data, $smarty, $user, $db, $account) {




	$object=$data['_object'];

	$block_view=$data['section'];



	$left_buttons=array();


	if ($data['parent']) {

		switch ($data['parent']) {
		case 'store':
			$tab='store.products';
			$_section='products';
			break;
		case 'part':
			$tab='part.products';
			$_section='inventory';
			break;
		case 'department':
			$tab='department.products';
			$_section='products';
			break;
		case 'family':
			$tab='family.products';
			$_section='products';
			break;
		case 'order':
			$tab='order.items';
			$_section='orders';
			break;
		}


		if (isset($_SESSION['table_state'][$tab])) {
			$number_results=$_SESSION['table_state'][$tab]['nr'];
			$start_from=0;
			$order=$_SESSION['table_state'][$tab]['o'];
			$order_direction=($_SESSION['table_state'][$tab]['od']==1 ?'desc':'');
			$f_value=$_SESSION['table_state'][$tab]['f_value'];
			$parameters=$_SESSION['table_state'][$tab];
		}else {

			$default=$user->get_tab_defaults($tab);
			$number_results=$default['rpp'];
			$start_from=0;
			$order=$default['sort_key'];
			$order_direction=($default['sort_order']==1 ?'desc':'');
			$f_value='';
			$parameters=$default;
			$parameters['parent']=$data['parent'];
			$parameters['parent_key']=$data['parent_key'];
		}

		include_once 'prepare_table/'.$tab.'.ptble.php';

		$_order_field=$order;
		$order=preg_replace('/^.*\.`/', '', $order);
		$order=preg_replace('/^`/', '', $order);
		$order=preg_replace('/`$/', '', $order);

		if ($data['parent']=='order') {
			$_order_field_value=$data['otf'];
			$extra_field=',OTF.`Order Transaction Fact Key` as extra_field';
		}else {

			$_order_field_value=$object->get($order);
			$extra_field='';
		}

		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$prev_extra_field_value='';
		$next_extra_field_value='';

		$sql=trim($sql_totals." $wheref");


		if ($result2=$db->query($sql)) {
			if ($row2 = $result2->fetch() and $row2['num']>1) {


				$sql=sprintf("select P.`Store Product Code` object_name,P.`Store Product Key` as object_key %s from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND P.`Store Product Key` < %d))  order by $_order_field desc , P.`Store Product Key` desc limit 1",
					$extra_field,
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);

				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$prev_key=$row['object_key'];
						$prev_title=_("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
						if ($extra_field) {
							$prev_extra_field_value=$row['extra_field'];
						}
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}


				$sql=sprintf("select P.`Store Product Code` object_name,P.`Store Product Key` as object_key %s from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND P.`Store Product Key` > %d))  order by $_order_field   , P.`Store Product Key`  limit 1",
					$extra_field,
					prepare_mysql($_order_field_value),
					prepare_mysql($_order_field_value),
					$object->id
				);


				if ($result=$db->query($sql)) {
					if ($row = $result->fetch()) {
						$next_key=$row['object_key'];
						$next_title=_("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
						if ($extra_field) {
							$next_extra_field_value=$row['extra_field'];
						}
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}





				if ($order_direction=='desc') {
					$_tmp1=$prev_key;
					$_tmp2=$prev_title;
					$prev_key=$next_key;
					$prev_title=$next_title;
					$next_key=$_tmp1;
					$next_title=$_tmp2;
					if ($extra_field) {
						$_tmp3=$prev_extra_field_value;
						$prev_extra_field_value=$next_extra_field_value;
						$next_extra_field_value=$_tmp3;
					}

				}









			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}



	}
	else {
		$_section='products';

	}




	switch ($data['parent']) {
	case 'store':



		$up_button=array('icon'=>'arrow-up', 'title'=>_("Store").' ('.$data['store']->get('Code').')', 'reference'=>'products/'.$data['store']->id);

		if ($prev_key) {
			$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'products/'.$data['parent_key'].'/'.$prev_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'');

		}
		$left_buttons[]=$up_button;


		if ($next_key) {
			$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'products/'.$data['parent_key'].'/'.$next_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

		}

		break;
	case 'part':

		$part_warehouse=$data['current_warehouse'];
		if (!$part_warehouse) {
			$part_warehouse=array_pop($data['_parent']->get('Warehouse Keys'));
		}

		$up_button=array('icon'=>'arrow-up', 'title'=>_("Part").' ('.$data['_parent']->get('SKU').')', 'reference'=>'inventory/'.$part_warehouse.'/part/'.$data['_parent']->id);

		if ($prev_key) {
			$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'inventory/'.$part_warehouse.'/part/'.$data['_parent']->id.'/product/'.$prev_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'');

		}
		$left_buttons[]=$up_button;


		if ($next_key) {
			$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title,  'reference'=>'inventory/'.$part_warehouse.'/part/'.$data['_parent']->id.'/product/'.$next_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

		}

		break;
	case 'order':
		$order=new Order($data['parent_key']);
		$up_button=array('icon'=>'arrow-up', 'title'=>_("Order").' ('.$order->get('Order Public ID').')', 'reference'=>'orders/'.$order->get('Order Store Key').'/'.$order->id);

		if ($prev_key) {
			$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'order/'.$data['parent_key'].'/item/'.$prev_extra_field_value);

		}else {
			$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'');

		}
		$left_buttons[]=$up_button;


		if ($next_key) {
			$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'order/'.$data['parent_key'].'/item/'.$next_extra_field_value);

		}else {
			$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

		}

		break;
	}

	$right_buttons=array();
	//$right_buttons[]=array('icon'=>'edit','title'=>_('Edit store'),'reference'=>'store/'.$data['store']->id.'/edit');
	$sections=get_sections('products', $data['store']->id);
	$_section='products';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Product').' <span class="id Store_Product_Code">'.$object->get('Code').'</span>',
		'search'=>array('show'=>true, 'placeholder'=>_('Search products').' '.$object->get('Product Store Code'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_products_category_navigation($data, $smarty, $user, $db) {




	require_once 'class.Category.php';
	require_once 'class.Store.php';


	$category=new Category($data['key']);

	$left_buttons=array();
	$right_buttons=array();

	switch ($data['parent']) {
	case 'category':

		$parent_category=new Category($data['parent_key']);



		$up_button=array('icon'=>'arrow-up', 'title'=>_("Product's Categories").' '.$data['store']->data['Store Code'], 'reference'=>'products/'.$data['store']->id.'/category/'.$parent_category->id);

		if ($data['tab']=='category.subjects') {
			$tab='subject_categories';

		}else {

			$tab='category.categories';
		}
		$parent_categories=$parent_category->get('Category Position');
		break;
	case 'store':


		$up_button=array('icon'=>'arrow-up', 'title'=>_("Product's Categories").' '.$data['store']->data['Store Code'], 'reference'=>'products/'.$data['store']->id.'/categories');
		$tab='products.categories';
		$parent_categories='';
		break;

	default:

		break;
	}


	if (isset($_SESSION['table_state'][$tab])) {
		$number_results=$_SESSION['table_state'][$tab]['nr'];
		$start_from=0;
		$order=$_SESSION['table_state'][$tab]['o'];
		$order_direction=($_SESSION['table_state'][$tab]['od']==1 ?'desc':'');
		$f_value=$_SESSION['table_state'][$tab]['f_value'];
		$parameters=$_SESSION['table_state'][$tab];
	}else {

		$default=$user->get_tab_defaults($tab);
		$number_results=$default['rpp'];
		$start_from=0;
		$order=$default['sort_key'];
		$order_direction=($default['sort_order']==1 ?'desc':'');
		$f_value='';
		$parameters=$default;

	}
	$parameters['parent']=$data['parent'];
	$parameters['parent_key']=$data['parent_key'];
	include_once 'prepare_table/'.$tab.'.ptble.php';


	$_order_field=$order;
	$order=preg_replace('/^.*\.`/', '', $order);
	$order=preg_replace('/^`/', '', $order);
	$order=preg_replace('/`$/', '', $order);



	$_order_field_value=$category->get($order);
	$extra_field='';



	$prev_title='';
	$next_title='';
	$prev_key=0;
	$next_key=0;
	$prev_extra_field_value='';
	$next_extra_field_value='';


	$sql=trim($sql_totals." $wheref");
	//print $sql;

	if ($result2=$db->query($sql)) {
		if ($row2 = $result2->fetch() and $row2['num']>1) {


			$sql=sprintf("select C.`Category Label` object_name,C.`Category Key` as object_key %s from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND C.`Category Key` < %d))  order by $_order_field desc , C.`Category Key` desc limit 1",
				$extra_field,
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$category->id
			);

			if ($result=$db->query($sql)) {
				if ($row = $result->fetch()) {
					$prev_key=$row['object_key'];
					$prev_title=_("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
					if ($extra_field) {
						$prev_extra_field_value=$row['extra_field'];
					}
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}


			$sql=sprintf("select C.`Category Label` object_name,C.`Category Key` as object_key %s from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND C.`Category Key`> %d))  order by $_order_field   , C.`Category Key` limit 1",
				$extra_field,
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$category->id
			);


			if ($result=$db->query($sql)) {
				if ($row = $result->fetch()) {
					$next_key=$row['object_key'];
					$next_title=_("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
					if ($extra_field) {
						$next_extra_field_value=$row['extra_field'];
					}
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}





			if ($order_direction=='desc') {
				$_tmp1=$prev_key;
				$_tmp2=$prev_title;
				$prev_key=$next_key;
				$prev_title=$next_title;
				$next_key=$_tmp1;
				$next_title=$_tmp2;
				if ($extra_field) {
					$_tmp3=$prev_extra_field_value;
					$prev_extra_field_value=$next_extra_field_value;
					$next_extra_field_value=$_tmp3;
				}

			}









		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}




	if ($prev_key) {
		$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'products/'.$data['store']->id.'/category/'.$parent_categories.$prev_key);

	}else {
		$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'');

	}
	$left_buttons[]=$up_button;


	if ($next_key) {
		$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'products/'.$data['store']->id.'/category/'.$parent_categories.$next_key);

	}else {
		$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

	}









	if ($data['store']->get('Store Department Category Key')==$data['_object']->get('Category Root Key')) {
		$category_title_label=_('Department');
	} if ($data['store']->get('Store Family Category Key')==$data['_object']->get('Category Root Key')) {
		$category_title_label=_('Family');
	}else {
		$category_title_label=_('Category');
	}


	$title=$category_title_label.' <span class="Category_Code id">'.$data['_object']->get('Code').'</span>';


	$right_buttons[]=array('icon'=>'sticky-note', 'title'=>_('Sticky note'), 'id'=>'sticky_note_button', 'click'=>"show_sticky_note_edit_dialog('sticky_note_button')",  'class'=> ($category->get('Sticky Note')==''?'':'hide'));

	//$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'), 'url'=>"edit_product_categories.php?store_id=".$data['store']->id);

	$sections=get_sections('products', $data['store']->id);
	$sections['categories']['selected']=true;

	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search products'))

	);
	$smarty->assign('_content', $_content);
	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


?>
