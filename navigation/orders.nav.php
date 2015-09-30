<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2015 18:30:51 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_orders_navigation($data) {

	global $user,$smarty;
	require_once 'class.Store.php';

	switch ($data['parent']) {
	case 'store':
		$store=new Store($data['parent_key']);
		break;
	default:

		break;
	}

	$block_view=$data['section'];


	$sections=get_sections('orders',$store->id);
	switch ($block_view) {
	case 'orders':

		//array_pop($sections);
		$sections_class='';
		$title=_('Orders').' <span class="id">'.$store->get('Store Code').'</span>';
		
		$up_button=array('icon'=>'arrow-up','title'=>_('Orders').' ('._('All stores').')','reference'=>'orders/all');
		$button_label=_('Orders %s');
		break;
	case 'invoices':
		$sections_class='';
		$title=_('Invoices').' <span class="id">'.$store->get('Store Code').'</span>';
		
		$up_button=array('icon'=>'arrow-up','title'=>_('Invoices').' ('._('All stores').')','reference'=>'invoices/all');
		$button_label=_('Invoices %s');
		break;
	case 'delivery_notes':
		$sections_class='';
		$title=_('Delivery Notes').' <span class="id">'.$store->get('Store Code').'</span>';
		
		$up_button=array('icon'=>'arrow-up','title'=>_('Delivery Notes').' ('._('All stores').')','reference'=>'delivery_notes/all');
		$button_label=_('Delivery Notes %s');
		break;
	case 'payments':
		$sections_class='';
		$title=_('Payments').' <span class="id">'.$store->get('Store Code').'</span>';
		
		$up_button=array('icon'=>'arrow-up','title'=>_('Payments').' ('._('All stores').')','reference'=>'payments/all');
		$button_label=_('Payments %s');
		break;
	}

	$left_buttons=array();
	if ($user->stores>1) {

		list($prev_key,$next_key)=get_prev_next($store->id,$user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=sprintf($button_label,$row['Store Code']);
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=sprintf($button_label,$row['Store Code']);
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>$block_view.'/'.$prev_key);
		$left_buttons[]=$up_button;

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>$block_view.'/'.$next_key);
	}


	$right_buttons=array();

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;



	$_content=array(
		'sections_class'=>$sections_class,
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true,'placeholder'=>_('Search orders'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_orders_server_navigation($data) {

	global $user,$smarty;

	
	$block_view=$data['section'];


	$sections=get_sections('orders_server');
	switch ($block_view) {
	case 'orders':

		//array_pop($sections);
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
		'search'=>array('show'=>true,'placeholder'=>_('Search orders'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


?>