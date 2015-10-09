<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2015 18:30:51 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_orders_navigation($data) {

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


	$sections=get_sections('orders', $store->id);
	switch ($block_view) {
	case 'orders':

		//array_pop($sections);
		$sections_class='';
		$title=_('Orders').' <span class="id">'.$store->get('Store Code').'</span>';

		$up_button=array('icon'=>'arrow-up', 'title'=>_('Orders').' ('._('All stores').')', 'reference'=>'orders/all');
		$button_label=_('Orders %s');
		break;
	case 'invoices':
		$sections_class='';
		$title=_('Invoices').' <span class="id">'.$store->get('Store Code').'</span>';

		$up_button=array('icon'=>'arrow-up', 'title'=>_('Invoices').' ('._('All stores').')', 'reference'=>'invoices/all');
		$button_label=_('Invoices %s');
		break;
	case 'delivery_notes':
		$sections_class='';
		$title=_('Delivery Notes').' <span class="id">'.$store->get('Store Code').'</span>';

		$up_button=array('icon'=>'arrow-up', 'title'=>_('Delivery Notes').' ('._('All stores').')', 'reference'=>'delivery_notes/all');
		$button_label=_('Delivery Notes %s');
		break;
	case 'payments':
		$sections_class='';
		$title=_('Payments').' <span class="id">'.$store->get('Store Code').'</span>';

		$up_button=array('icon'=>'arrow-up', 'title'=>_('Payments').' ('._('All stores').')', 'reference'=>'payments/all');
		$button_label=_('Payments %s');
		break;
	}

	$left_buttons=array();
	if ($user->stores>1) {

		list($prev_key, $next_key)=get_prev_next($store->id, $user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=sprintf($button_label, $row['Store Code']);
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d", $next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=sprintf($button_label, $row['Store Code']);
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>$block_view.'/'.$prev_key);
		$left_buttons[]=$up_button;

		$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>$block_view.'/'.$next_key);
	}


	$right_buttons=array();

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;



	$_content=array(
		'sections_class'=>$sections_class,
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search orders'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_orders_server_navigation($data) {

	global $user, $smarty;


	$block_view=$data['section'];


	$sections=get_sections('orders_server');
	switch ($block_view) {
	case 'orders':

		$sections_class='';
		$title=_('Orders').' ('._('All stores').')';

		$button_label=_('Orders %s');
		break;
	case 'invoices':
		$sections_class='';
		$title=_('Invoices').' ('._('All stores').')';

		$button_label=_('Invoices %s');
		break;
	case 'delivery_notes':
		$sections_class='';
		$title=_('Delivery Notes').' ('._('All stores').')';

		$button_label=_('Delivery Notes %s');
		break;
	case 'payments':
		$sections_class='';
		$title=_('Payments').' ('._('All stores').')';

		$button_label=_('Payments %s');
		break;
	}

	$left_buttons=array();



	$right_buttons=array();

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;



	$_content=array(
		'sections_class'=>$sections_class,
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search orders'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_order_navigation($data) {

	global $user, $smarty;

	$object=$data['_object'];
	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'customer':
			$tab='customer.orders';
			$_section='customers';
			break;
		case 'store':
			$tab='orders';
			$_section='orders';
			break;

		}


		$number_results=$_SESSION['table_state'][$tab]['nr'];
		$start_from=0;
		$order=$_SESSION['table_state'][$tab]['o'];
		$order_direction=($_SESSION['table_state'][$tab]['od']==1 ?'desc':'');
		$f_value=$_SESSION['table_state'][$tab]['f_value'];
		$parameters=$_SESSION['table_state'][$tab];

		include_once 'prepare_table/'.$tab.'.ptble.php';

		$_order_field=$order;
		$order=preg_replace('/^.*\.`/', '', $order);
		$order=preg_replace('/^`/', '', $order);
		$order=preg_replace('/`$/', '', $order);
		$_order_field_value=$object->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql=trim($sql_totals." $wheref");

		$res2=mysql_query($sql);
		if ($row2=mysql_fetch_assoc($res2) and $row2['num']>1 ) {

			$sql=sprintf("select `Order Public ID` object_name,O.`Order Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND O.`Order Key` < %d))  order by $_order_field desc , O.`Order Key` desc limit 1",

				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);


			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$prev_key=$row['object_key'];
				$prev_title=_("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';

			}

			$sql=sprintf("select `Order Public ID` object_name,O.`Order Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND O.`Order Key` > %d))  order by $_order_field   , O.`Order Key`  limit 1",
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);


			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$next_key=$row['object_key'];
				$next_title=_("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';

			}


			if ($order_direction=='desc') {
				$_tmp1=$prev_key;
				$_tmp2=$prev_title;
				$prev_key=$next_key;
				$prev_title=$next_title;
				$next_key=$_tmp1;
				$next_title=$_tmp2;
			}


		}

		if ($data['parent']=='customer') {


			$up_button=array('icon'=>'arrow-up', 'title'=>_("Customer").' '.$object->get('Order Customer Name'), 'reference'=>'customers/'.$object->get('Order Store Key').'/'.$object->get('Order Customer Key'));

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'customer/'.$object->get('Order Customer Key').'/order/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'customer/'.$object->get('Order Customer Key').'/order/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}
			$sections=get_sections('customers', $object->get('Order Order Key'));


		}
		elseif($data['parent']=='store')  {
            $store=new Store($data['parent_key']);
			$up_button=array('icon'=>'arrow-up', 'title'=>_("Orders").' ('.$store->get('Store Code').')', 'reference'=>'orders/'.$data['parent_key']);

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'orders/'.$data['parent_key'].'/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'orders/'.$data['parent_key'].'/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}



			$sections=get_sections('orders', '');



		}
	}
	else {
		$_section='staff';
		$sections=get_sections('orders', '');


	}



	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;



	$title= _('Order').' <span class="id">'.$object->get('Order Public ID').'</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search users'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;

}


?>
