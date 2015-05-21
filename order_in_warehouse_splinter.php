<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 227 September 2014 14:40:26 BST, Sheffield UK

 Copyright (c) 2014, Inikoo

 Version 2.1
*/


if (!isset($inikoo_account))exit;



$js_files[]='js/php.default.min.js';
$js_files[]='js/add_payment.js';



$shipper_data=array();

$sql=sprintf("select `Shipper Key`,`Shipper Code`,`Shipper Name` from `Shipper Dimension` where `Shipper Active`='Yes' order by `Shipper Name` ");
$result=mysql_query($sql);
while ($row=mysql_fetch_assoc($result)) {
	$shipper_data[$row['Shipper Key']]=array(
		'shipper_key'=>$row['Shipper Key'],
		'code'=>$row['Shipper Code'],
		'name'=>$row['Shipper Name'],
		'selected'=>0
	);


}


if (isset($_REQUEST['amend']) and $_REQUEST['amend']) {

	$js_files[]='js/edit_common.js';


	$js_files[]='js/country_address_labels.js';
	$js_files[]='js/edit_address.js';

	$js_files[]='js/edit_delivery_address_common.js';
	$js_files[]='js/edit_billing_address_common.js';
	$js_files[]='js/common_order_not_dispatched.js?150428';

	$js_files[]='order_in_warehouse_amend.js.php?order_key='.$order_id.'&customer_key='.$customer->id;


	$css_files[]='css/edit.css';
	$css_files[]='css/edit_address.css';


	$template='order_in_warehouse_amend.tpl';


	$products_display_type='ordered_products';

	$_SESSION['state']['order']['products']['display']=$products_display_type;

	$products_display_type=$_SESSION['state']['order']['block_view'];

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

	$smarty->assign('block_view','items');
	$smarty->assign('lookup_family',$_SESSION['state']['order']['products']['lookup_family']);

	$smarty->assign('search_label',_('Orders'));
	$smarty->assign('search_scope','orders');





}
else {

	$css_files[]='css/edit.css';
	$css_files[]='css/edit_address.css';

	$js_files[]='js/edit_common.js';
	$js_files[]='js/country_address_labels.js';
	$js_files[]='js/edit_address.js';
	$js_files[]='js/edit_delivery_address_common.js';
	$js_files[]='js/edit_billing_address_common.js';
	$js_files[]='js/common_order_not_dispatched.js?141007';
	$js_files[]='js/common_assign_picker_packer.js';
	$js_files[]='order_in_warehouse.js.php';

	$template='order_in_warehouse.tpl';

	$products_display_type='ordered_products';
	$_SESSION['state']['order']['products']['display']=$products_display_type;

	$products_display_type=$_SESSION['state']['order']['products']['display'];

	$smarty->assign('products_display_type',$products_display_type);
	$smarty->assign('view',$_SESSION['state']['order']['products']['view']);




	$tipo_filter=$_SESSION['state']['order']['products']['f_field'];


	$smarty->assign('filter0',$tipo_filter);
	$smarty->assign('filter_value0',$_SESSION['state']['order']['products']['f_value']);
	$filter_menu=array(
		'code'=>array('db_key'=>'code','menu_label'=>'Code starting with  <i>x</i>','label'=>'Code'),
		'family'=>array('db_key'=>'family','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code'),
		'name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Code')

	);
	$smarty->assign('filter_menu0',$filter_menu);
	$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


	$paginator_menu=array(10,25,50,100);
	$smarty->assign('paginator_menu0',$paginator_menu);



	$tipo_filter2='alias';
	$filter_menu2=array(
		'alias'=>array('db_key'=>'alias','menu_label'=>_('Alias'),'label'=>_('Alias')),
		'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
	);
	$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
	$smarty->assign('filter_menu2',$filter_menu2);
	$smarty->assign('filter2',$tipo_filter2);
	$smarty->assign('filter_value2','');

	$smarty->assign('search_label',_('Orders'));
	$smarty->assign('search_scope','orders');


}





$pickers=$corporation->get_current_staff_with_position_code('PICK');
$number_cols=5;
$row=0;
$pickers_data=array();
$contador=0;
foreach ($pickers as $picker) {
	if (fmod($contador,$number_cols)==0 and $contador>0)
		$row++;
	$tmp=array();
	foreach ($picker as $key=>$value) {
		$tmp[preg_replace('/\s/','',$key)]=$value;
	}
	$pickers_data[$row][]=$tmp;
	$contador++;
}

$smarty->assign('pickers',$pickers_data);
$smarty->assign('number_pickers',count($pickers_data));

$packers=$corporation->get_current_staff_with_position_code('PACK');
$number_cols=5;
$row=0;
$packers_data=array();
$contador=0;
foreach ($packers as $packer) {
	if (fmod($contador,$number_cols)==0 and $contador>0)
		$row++;
	$tmp=array();
	foreach ($packer as $key=>$value) {
		$tmp[preg_replace('/\s/','',$key)]=$value;
	}
	$packers_data[$row][]=$tmp;
	$contador++;
}




$smarty->assign('packers',$packers_data);
$smarty->assign('number_packers',count($packers_data));



?>
