<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2014 14:37:30 BST, Sheffield UK

 Copyright (c) 2014, Inikoo

 Version 2.1
*/


 if (!isset($inikoo_account))exit;



 $smarty->assign('elements',$_SESSION['state']['customer']['history']['elements']);
 $filter_menu=array(
 	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with notes *<i>x</i>*'),'label'=>_('Notes')),
	//   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Done by')),
 	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
 	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)'))
 	);
 $tipo_filter=$_SESSION['state']['customer']['history']['f_field'];
 $filter_value=$_SESSION['state']['customer']['history']['f_value'];
 $smarty->assign('filter_value2',$filter_value);
 $smarty->assign('filter_menu2',$filter_menu);
 $smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
 $paginator_menu=array(10,25,50,100,500);
 $smarty->assign('paginator_menu2',$paginator_menu);

 $js_files[]='js/php.default.min.js';
 $js_files[]='js/add_payment.js';
 $js_files[]='js/edit_common.js';
 $js_files[]='js/country_address_labels.js';
 $js_files[]='js/edit_address.js';
 $js_files[]='js/edit_delivery_address_common.js';
 $js_files[]='js/edit_billing_address_common.js';
 $js_files[]='js/order_in_process.js';
 $js_files[]='js/common_order_not_dispatched.js?141007';
 $js_files[]='js/edit_bonus.js';
 $css_files[]='css/edit.css';
 $css_files[]='css/edit_address.css';


 $template='order_in_process.tpl';

 $products_display_type='ordered_products';

 $_SESSION['state']['order']['products']['display']=$products_display_type;

 $products_display_type=$_SESSION['state']['order']['products']['display'];
 $smarty->assign('products_display_type',$products_display_type);

 $smarty->assign('products_view',$_SESSION['state']['order']['products']['view']);
 $smarty->assign('items_view',$_SESSION['state']['order']['items']['view']);

 $smarty->assign('products_period',$_SESSION['state']['order']['products']['period']);
 $smarty->assign('items_period',$_SESSION['state']['order']['items']['period']);

 $tipo_filter=$_SESSION['state']['order']['items']['f_field'];
 $smarty->assign('filter0',$tipo_filter);
 $smarty->assign('filter_value0',$_SESSION['state']['order']['items']['f_value']);
 $filter_menu=array(
 	'code'=>array('db_key'=>'code','menu_label'=>_('Code starting with <i>x</i>'),'label'=>_('Code')),
 	'family'=>array('db_key'=>'family','menu_label'=>_('Family starting with <i>x</i>'),'label'=>_('Family')),
 	'name'=>array('db_key'=>'name','menu_label'=>_('Name starting with <i>x</i>'),'label'=>_('Name'))
 	);
 $smarty->assign('filter_menu0',$filter_menu);
 $smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
 $paginator_menu=array(10,25,50,100);
 $smarty->assign('paginator_menu0',$paginator_menu);



 $tipo_filter=$_SESSION['state']['order']['products']['f_field'];
 $smarty->assign('filter1',$tipo_filter);
 $smarty->assign('filter_value1',$_SESSION['state']['order']['products']['f_value']);
 $filter_menu=array(
 	'code'=>array('db_key'=>'code','menu_label'=>_('Code starting with <i>x</i>'),'label'=>_('Code')),
 	'family'=>array('db_key'=>'family','menu_label'=>_('Family starting with <i>x</i>'),'label'=>_('Family')),
 	'name'=>array('db_key'=>'name','menu_label'=>_('Name starting with <i>x</i>'),'label'=>_('Name'))
 	);
 $smarty->assign('filter_menu1',$filter_menu);
 $smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
 $paginator_menu=array(10,25,50,100);
 $smarty->assign('paginator_menu1',$paginator_menu);

 $smarty->assign('search_label',_('Orders'));
 $smarty->assign('search_scope','orders');


 $charges_deal_info=$order->get_no_product_deal_info('Charges');
 if ($charges_deal_info!='') {
 	$charges_deal_info='<span style="color:red" title="'.$charges_deal_info.'">*</span> ';
 }
 $smarty->assign('charges_deal_info',$charges_deal_info);

 if ($order->data['Order Number Items']==0) {
 	$_SESSION['state']['order']['block_view']='products';
 }else {
 	$_SESSION['state']['order']['block_view']='items';
 }

 $smarty->assign('block_view',$_SESSION['state']['order']['block_view']);
 $smarty->assign('lookup_family',$_SESSION['state']['order']['products']['lookup_family']);


 $session_data=base64_encode(json_encode(array(
 	'label'=>array(
 		'Code'=>_('Code'),
 		'Description'=>_('Description'),
 		'Qty'=>_('Qty'),
 		'Date'=>_('Date'),
 		'Time'=>_('Time'),
 		'Author'=>_('Author'),
 		'Notes'=>_('Notes'), 	
 		'Page'=>_('Page'),
 		'of'=>_('of')
 		),
'state'=>array(
	'order'=>$_SESSION['state']['order'],
	'customer'=>array('history'=>$_SESSION['state']['customer']['history'])

	)
)));
$smarty->assign('session_data',$session_data);



?>