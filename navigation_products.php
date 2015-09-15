<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 September 2015 13:12:32 GMT+8 Kuala Lumpur

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_store_navigation($data) {

	global $user,$smarty;


	require_once 'class.Store.php';

    $store=new Store($data['key']);

	$block_view=$data['section'];

	

	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key,$next_key)=get_prev_next($store->id,$user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_('Store').' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_('Store').' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'store/'.$prev_key );
		$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Stores'),'reference'=>'stores','parent'=>'');

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'store/'.$next_key );
	}


	$right_buttons=array();
	$right_buttons[]=array('icon'=>'edit','title'=>_('Edit store'),'reference'=>'store/'.$store->id.'/edit');
	$right_buttons[]=array('icon'=>'plus','title'=>_('New store'),'id'=>"new_store");
	$sections=get_sections('products',$store->id);

	$_section='products';
	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Store').' <span class="id">'.$store->get('Store Name').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search products').' '.$store->get('Store Code'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_products_categories_navigation($data) {

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


	$left_buttons=array();
	if ($user->stores>1) {




		list($prev_key,$next_key)=get_prev_next($store->id,$user->stores);

		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_("Products's Categories").' '.$row['Store Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_("Products's Categories").' '.$row['Store Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'customers/categories/'.$prev_key);
		//$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Customers').' '.$store->data['Store Code'],'reference'=>'customers/'.$store->id);

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'customers/categories/'.$next_key);
	}


	$right_buttons=array();

	$right_buttons[]=array('icon'=>'edit','title'=>_('Edit'),'url'=>"edit_customer_categories.php?store_id=".$store->id);

	$sections=get_sections('customers',$store->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;

	$_content=array(
		'branch'=>$branch,
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_("Customer's Categories").' <span class="id">'.$store->get('Store Code').'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search customers'))

	);
	$smarty->assign('_content',$_content);
	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


?>
