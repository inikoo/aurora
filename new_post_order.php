<?php
include_once 'common.php';
include_once 'class.CurrencyExchange.php';



include_once 'class.Order.php';
if (!$user->can_view('orders')) {
	header('Location: index.php');
	exit;
}

$modify=$user->can_edit('orders');

if (!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])) {
	header('Location: orders_server.php?msg=wrong_id');
	exit;
}

$order_id=$_REQUEST['id'];
$_SESSION['state']['order']['id']=$order_id;
$order=new Order($order_id);
if (!$order->id) {
	header('Location: orders_server.php?msg=order_not_found');
	exit;

}
if (!($user->can_view('stores') and in_array($order->data['Order Store Key'],$user->stores)   ) ) {
	header('Location: orders_server.php');
	exit;
}

$customer=new Customer($order->get('Order Customer Key'));
$store=new Customer($order->get('Order Store key'));


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/edit.css',
	'css/edit_address.css',
	'css/new_post_order.css',
	'theme.css.php',
);


$js_files=array(

	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'dragdrop/dragdrop-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/search.js',
	'js/table_common.js',
	'js/country_address_labels.js',
	'js/edit_address.js',
	'address_data.js.php?tipo=customer&id='.$customer->id,
	'edit_delivery_address_js/common.js',
	'js/edit_common.js',
	'js/new_post_order.js',
);





if (isset($_REQUEST['referral'])) {
	$referral=$_REQUEST['referral'];
}else {
	$referral='';
}
$smarty->assign('referral',$referral);




$_SESSION['state']['order']['store_key']=$order->data['Order Store Key'];

$tipo_filter=$_SESSION['state']['products']['table']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['products']['table']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>'Code starting with  <i>x</i>','label'=>'Code')
	,'family'=>array('db_key'=>'family','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code')
	,'name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Code')

);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100);
$smarty->assign('paginator_menu0',$paginator_menu);


//$smarty->assign('search_label',_('Products'));
//$smarty->assign('search_scope','products');

$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');


$smarty->assign('order',$order);
$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);
$smarty->assign('store_key',$store->id);


$smarty->assign('products_display_type','list');

$smarty->assign('customer',$customer);



$smarty->assign('parent','orders');
$smarty->assign('title',_('Post Order').' '.$order->get('Order Public ID') );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$order_post_transactions_in_process=$order->get_post_transactions_in_process_data();
$smarty->assign('order_post_transactions_in_process',$order_post_transactions_in_process);




$dns_data=array();
foreach ($order->get_delivery_notes_objects() as $dn) {

	if (!in_array($dn->data['Delivery Note Type'],array('Replacement & Shortages','Replacement','Shortages')))
		continue;

	$current_delivery_note_key=$dn->id;

	$missing_dn_data=false;
	$missing_dn_str='';
	$dn_data='';
	if ($dn->data['Delivery Note Weight']) {
		$dn_data=$dn->get('Weight');
	}else {
		$missing_dn_data=true;
		$missing_dn_str=_('weight');

	}

	if ($dn->data['Delivery Note Number Parcels']!='') {
		$dn_data.=', '.$dn->get_formated_parcels();
	}else {
		$missing_dn_data=true;
		$missing_dn_str.=', '._('parcels');
	}
	$missing_dn_str=preg_replace('/^,/','',$missing_dn_str);


	if ($dn->data['Delivery Note Shipper Consignment']!='') {
		$dn_data.=', '. $dn->get('Consignment');
	}else {
		$missing_dn_data=true;
		$missing_dn_str.=', '._('consignment');
	}
	$missing_dn_str=preg_replace('/^,/','',$missing_dn_str);
	$dn_data=preg_replace('/^,/','',$dn_data);

	if ($missing_dn_data  and in_array($order->data['Order Current Dispatch State'],array('Packed Done','Packed')) ) {
		$dn_data.=' <img src="art/icons/exclamation.png" style="height:14px;vertical-align:-3px"> <span style="font-style:italic;color:#ea6c59">'._('Missing').': '.$missing_dn_str.'</span> <img onClick="show_dialog_set_dn_data_from_order('.$dn->id.')" style="cursor:pointer;display:none" src="art/icons/edit.gif"> ';
	}

	$dns_data[]=array(
		'key'=>$dn->id,
		'number'=>$dn->data['Delivery Note ID'],
		'state'=>$dn->data['Delivery Note XHTML State'],
		'dispatch_state'=>$dn->data['Delivery Note State'],
		'data'=>$dn_data,
		'operations'=>$dn->get_operations($user,'order',$order->id),
	);

	//print_r($dns_data);

}
$number_dns=count($dns_data);
if ($number_dns!=1) {
	$current_delivery_note_key='';
}
$smarty->assign('current_delivery_note_key',$current_delivery_note_key);
$smarty->assign('number_dns',$number_dns);
$smarty->assign('dns_data',$dns_data);



$smarty->assign('default_country_2alpha',$store->get('Store Home Country Code 2 Alpha'));




$template='new_post_order.tpl';

$session_data=base64_encode(json_encode(array(
			'label'=>array(
				'Code'=>_('Code'),
				'Description'=>_('Description'),
				'Stock'=>_('Stock'),
				'Ordered'=>_('Ordered'),
				'Qty'=>_('Qty'),
				'Operation'=>_('Operation'),
				'Reason'=>_('Reason'),
				'Notes'=>_('Notes'),
				'Refund'=>_('Refund'),
				'Credit'=>_('Credit'),
				'Resend'=>_('Resend'),
				'Damaged'=>_('Damaged'),
				'Not_Received'=>_('Not_Received'),
				'Dontlikeit'=>_('Dont like it'),
				'Other'=>_('Other'),
				'Yes'=>_('Yes'),
				'No'=>_('No'),
				'Rtn'=>_('Return'),


				'Page'=>_('Page'),
				'of'=>_('of')
			),
			'state'=>array(
				'post_transactions'=>$_SESSION['state']['order']['post_transactions']
			)
		)));
$smarty->assign('session_data',$session_data);

$smarty->display($template);
?>
