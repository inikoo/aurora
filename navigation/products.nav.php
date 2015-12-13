<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 September 2015 13:12:32 GMT+8 Kuala Lumpur

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_store_navigation($data) {

	global $user, $smarty;


	require_once 'class.Store.php';

	$store=new Store($data['key']);

	$block_view=$data['section'];



	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key, $next_key)=get_prev_next($store->id, $user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_('Store').' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_('Store').' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'store/'.$prev_key );
		$left_buttons[]=array('icon'=>'arrow-up', 'title'=>_('Stores'), 'reference'=>'stores', 'parent'=>'');

		$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'store/'.$next_key );
	}


	$right_buttons=array();
	$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit store'), 'reference'=>'store/'.$store->id.'/edit');
	$right_buttons[]=array('icon'=>'plus', 'title'=>_('New store'), 'id'=>"new_store");
	$sections=get_sections('products', $store->id);
	$_section='products';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Store').' <span class="id">'.$store->get('Store Name').'</span>',
		'search'=>array('show'=>true, 'placeholder'=>_('Search products').' '.$store->get('Store Code'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_products_categories_navigation($data) {

	global $user, $smarty;


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
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_("Products's Categories").' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_("Products's Categories").' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'customers/categories/'.$prev_key);
		//$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Customers').' '.$store->data['Store Code'],'reference'=>'customers/'.$store->id);

		$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'customers/categories/'.$next_key);
	}


	$right_buttons=array();

	$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'), 'url'=>"edit_customer_categories.php?store_id=".$store->id);

	$sections=get_sections('customers', $store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(
		'branch'=>$branch,
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Customer's Categories").' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true, 'placeholder'=>_('Search customers'))

	);
	$smarty->assign('_content', $_content);
	$html=$smarty->fetch('navigation.tpl');
	return $html;

}



function get_stores_navigation($data) {

	global $user, $smarty;


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



function get_product_navigation($data) {

	global $user, $smarty;



	$object=$data['_object'];

	$block_view=$data['section'];



	$left_buttons=array();


	if ($data['parent']) {

		switch ($data['parent']) {
		case 'store':
			$tab='store.products';
			$_section='products';
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

		$res2=mysql_query($sql);
		if ($row2=mysql_fetch_assoc($res2) and $row2['num']>1 ) {

			$sql=sprintf("select P.`Product Code` object_name,P.`Product ID` as object_key %s from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND P.`Product ID` < %d))  order by $_order_field desc , P.`Product ID` desc limit 1",
				$extra_field,
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->pid
			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$prev_key=$row['object_key'];
				$prev_title=_("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
				if ($extra_field) {
					$prev_extra_field_value=$row['extra_field'];
				}
			}

			$sql=sprintf("select P.`Product Code` object_name,P.`Product ID` as object_key %s from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND P.`Product ID` > %d))  order by $_order_field   , P.`Product ID`  limit 1",
				$extra_field,
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->pid
			);

			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$next_key=$row['object_key'];
				$next_title=_("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
				if ($extra_field) {
					$next_extra_field_value=$row['extra_field'];
				}
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


	}
	else {
		$_section='products';

	}

	switch ($data['parent']) {
	case 'store':

		$store= new Store($object->get('Product Store Key'));

		$up_button=array('icon'=>'arrow-up', 'title'=>_("Store").' ('.$store->get('Store Code').')', 'reference'=>'store/'.$object->get('Product Store Key'));

		if ($prev_key) {
			$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'store/'.$data['parent_key'].'/product/'.$prev_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'');

		}
		$left_buttons[]=$up_button;


		if ($next_key) {
			$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'store/'.$data['parent_key'].'/product/'.$next_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

		}

		break;
	case 'department':
		$up_button=array('icon'=>'arrow-up', 'title'=>_("Department").' ('.$object->get('Product Main Department Code').')', 'reference'=>'store/'.$object->get('Product Store Key').'/department/'.$object->get('Product Main Department Key'));

		if ($prev_key) {
			$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'department/'.$data['parent_key'].'/product/'.$prev_key);

		}else {
			$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'');

		}
		$left_buttons[]=$up_button;


		if ($next_key) {
			$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'department/'.$data['parent_key'].'/product/'.$next_key);

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
	//$right_buttons[]=array('icon'=>'edit','title'=>_('Edit store'),'reference'=>'store/'.$store->id.'/edit');
	$sections=get_sections('products', $object->get('Product Store Key'));
	$_section='products';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Product').' <span class="id">'.$object->get('Product Code').'</span>',
		'search'=>array('show'=>true, 'placeholder'=>_('Search products').' '.$object->get('Product Store Code'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


?>
