<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2014 14:34:57 BST, Sheffield UK

 Copyright (c) 2014, Inikoo

 Version 2.1
*/


if (!isset($inikoo_account))exit;

//$order->update_payment_status();
$order->update_no_normal_totals('save');


$js_files[]='js/php.default.min.js';
$js_files[]='js/add_payment.js';
$js_files[]='js/common_order_not_dispatched.js';
$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');
$js_files[]='order_dispatched.js.php';
$template='order_dispatched.tpl';


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



$tipo_filter=$_SESSION['state']['order']['post_transactions']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['order']['post_transactions']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Code starting with <i>x</i>'),'label'=>_('Code')),
	'family'=>array('db_key'=>'family','menu_label'=>_('Family starting with <i>x</i>'),'label'=>_('Family')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Name starting with <i>x</i>'),'label'=>_('Name'))
);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100);
$smarty->assign('paginator_menu1',$paginator_menu);


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



$tipo_filter2='alias';
$filter_menu2=array(
	'alias'=>array('db_key'=>'alias','menu_label'=>_('Alias'),'label'=>_('Alias')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2','');

?>
