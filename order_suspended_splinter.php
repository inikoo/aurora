<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2014 14:45:31 BST, Sheffield UK

 Copyright (c) 2014, Inikoo

 Version 2.1
*/


if (!isset($inikoo_account))exit;
$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');
$smarty->assign('store_id',$store->id);

$js_files[]='js/order_suspended.js';
$template='order_suspended.tpl';

$tipo_filter=$_SESSION['state']['order_cancelled']['items']['f_field'];


$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['order_cancelled']['items']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>'Code starting with  <i>x</i>','label'=>'Code'),
	// 'family'=>array('db_key'=>'family','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code'),
	// 'name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Code')

);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->assign('items_view',$_SESSION['state']['order_cancelled']['items']['view']);


?>
