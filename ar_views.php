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
$view=$_REQUEST['view'];

switch ($tipo) {
case ('navigation'):


	switch ($view) {
	case ('dn'):
	case ('orders'):
	case ('invoices'):
	case ('payments'):

		$data=prepare_values($_REQUEST,array(
				'parent_key'=>array('type'=>'key'),
				'parent'=>array('type'=>'string'),
				'view'=>array('type'=>'view'),
			));
		get_orders_view($data);
		break;
	default:
		$response=array('state'=>404,'resp'=>'Operation not found');
		echo json_encode($response);

	}
	break;
default:
	$response=array('state'=>404,'resp'=>'Operation not found 2');
	echo json_encode($response);

}

function get_orders_view($data) {
	global $user,$smarty;
	require_once 'class.Store.php';

	switch ($data['parent']) {
	case 'store':
		$store=new Store($data['parent_key']);
		break;
	default:

		break;
	}

	$block_view=$data['view'];

	
	$branch=array(array('label'=>'','icon'=>'home','url'=>'index.php'));
	$sections=get_sections('orders',$store->id);
	switch ($block_view) {
	case 'orders':

		//array_pop($sections);
		$sections_class='only_icons';
		$title=_('Orders').' <span class="id">'.$store->get('Store Code').'</span>';
		if ( $user->get_number_stores()>1) {
			$branch[]=array('label'=>_('Orders'),'icon'=>'bars','url'=>'orders_server.php?view=orders');
		}
		$up_button=array('icon'=>'arrow-up','title'=>_('Orders (All stores)'),'url'=>'orders_server.php?view=orders');
		$button_label=_('Orders %s');
		break;
	case 'invoices':
		$sections_class='only_icons';
		$title=_('Invoices').' <span class="id">'.$store->get('Store Code').'</span>';
		if ( $user->get_number_stores()>1) {
			$branch[]=array('label'=>_('Invoices'),'icon'=>'bars','url'=>'orders_server.php?view=invoices');
		}
		$up_button=array('icon'=>'arrow-up','title'=>_('Invoices (All stores)'),'url'=>'orders_server.php?view=invoices');
		$button_label=_('Invoices %s');
		break;
	case 'dn':
		$sections_class='only_icons';
		$title=_('Delivery Notes').' <span class="id">'.$store->get('Store Code').'</span>';
		if ( $user->get_number_stores()>1) {
			$branch[]=array('label'=>_('Delivery Notes'),'icon'=>'bars','url'=>'orders_server.php?view=dn');
		}
		$up_button=array('icon'=>'arrow-up','title'=>_('Delivery Notes (All stores)'),'url'=>'orders_server.php?view=dn');
		$button_label=_('Delivery Notes %s');
		break;
	case 'payments':
		$sections_class='only_icons';
		$title=_('Payments').' <span class="id">'.$store->get('Store Code').'</span>';
		if ( $user->get_number_stores()>1) {
			$branch[]=array('label'=>_('Payments'),'icon'=>'bars','url'=>'orders_server.php?view=payments');
		}
		$up_button=array('icon'=>'arrow-up','title'=>_('Payments (All stores)'),'url'=>'orders_server.php?view=payments');
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
			$next_title=_('Customers').' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'url'=>'orders.php?store='.$prev_key);
		$left_buttons[]=$up_button;

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'url'=>'orders.php?store='.$next_key);
	}


	$right_buttons=array();




	$_content=array(
		'branch'=>$branch,
		'sections_class'=>$sections_class,
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true,'placeholder'=>_('Search customers'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	$response=array('state'=>200,'resp'=>$html);
	echo json_encode($response);

}

?>
