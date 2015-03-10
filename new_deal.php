<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

 include_once 'common.php';
 include_once 'class.Store.php';
 if (!$user->can_view('stores') or count($user->stores)==0 ) {

 	header('Location: index.php');
 	exit;
 }

 switch ($_REQUEST['parent']) {

 	case 'customer':
 	$customer=new Customer($_REQUEST['parent_key']);


 	$store=new Store($customer->data['Customer Store Key']);

 	$smarty->assign('scope',$customer);
 	$smarty->assign('customer',$customer);

 	$smarty->assign('store',$store);

 	$smarty->assign('scope_subject','Customer');
 	$smarty->assign('parent','customers');
 	$smarty->assign('search_label',_('Customers'));
 	$smarty->assign('search_scope','customers');
break;
 	case 'store':
 	$store=new Store($_REQUEST['parent_key']);

 	$smarty->assign('scope',$store);

 	$smarty->assign('store',$store);

 	$smarty->assign('scope_subject','Order');
 	$smarty->assign('parent','products');
 	$smarty->assign('search_label',_('Products'));
 	$smarty->assign('search_scope','products');

 	break;
 	case 'department':
 	$department=new Department($_REQUEST['parent_key']);

 	$store=new Store($department->data['Product Department Store Key']);

 	$smarty->assign('scope',$department);
 	$smarty->assign('department',$department);

 	$smarty->assign('store',$store);
 	$smarty->assign('department',$department);

 	$smarty->assign('scope_subject','department');
 	$smarty->assign('parent','products');
 	$smarty->assign('search_label',_('Products'));
 	$smarty->assign('search_scope','products');

 	break;
 	case 'family':
 	$family=new Family($_REQUEST['parent_key']);
 	$department=new Department($family->get('Product Family Main Department Key'));

 	$store=new Store($family->data['Product Family Store Key']);

 	$smarty->assign('scope',$family);
 	$smarty->assign('family',$family);

 	$smarty->assign('store',$store);
 	$smarty->assign('department',$department);

 	$smarty->assign('scope_subject','Family');
 	$smarty->assign('parent','products');
 	$smarty->assign('search_label',_('Products'));
 	$smarty->assign('search_scope','products');

 	break;
 	case 'product':
 	$product=new Product($_REQUEST['parent_key']);
 	$department=new Department($product->get('Product Main Department Key'));
 	$family=new Family($product->get('Product Family Key'));

 	$store=new Store($product->data['Product Store Key']);

 	$smarty->assign('scope',$family);
 	$smarty->assign('family',$family);
 	$smarty->assign('department',$department);
 	$smarty->assign('store',$store);

 	$smarty->assign('scope_subject','Product');
 	$smarty->assign('parent','products');

 	$smarty->assign('search_label',_('Products'));
 	$smarty->assign('search_scope','products');

 	break;
 }

 $css_files=array(
 	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
 	$yui_path.'menu/assets/skins/sam/menu.css',
 	$yui_path.'calendar/assets/skins/sam/calendar.css',
 	$yui_path.'button/assets/skins/sam/button.css',
 	$yui_path.'assets/skins/sam/autocomplete.css',
 	'css/common.css',
 	'css/button.css',
 	'css/container.css',
 	'css/edit.css',
 	'css/table.css',
 	'theme.css.php'

 	);

 $js_files=array(
 	$yui_path.'utilities/utilities.js',
 	$yui_path.'json/json-min.js',
 	$yui_path.'paginator/paginator-min.js',
 	$yui_path.'datasource/datasource-min.js',
 	$yui_path.'autocomplete/autocomplete-min.js',
 	$yui_path.'datatable/datatable.js',
 	$yui_path.'container/container-min.js',
 	$yui_path.'menu/menu-min.js',
 	'js/common.js',
 	'js/table_common.js',
 	'js/edit_common.js',
 	'js/search.js',
 	'js/new_deal.js',

 	);


 $smarty->assign('title', _('New Offer'));
 $smarty->assign('css_files',$css_files);
 $smarty->assign('js_files',$js_files);






 $sql=sprintf("select `Deal Campaign Key`,`Deal Campaign Code`,`Deal Campaign Name` from `Deal Campaign Dimension` where `Deal Campaign Store Key`=%d and `Deal Campaign Status`!='Finish'",
 	$store->id
 	);

 $res = mysql_query($sql);
 $campaigns=array();
 while ($row=mysql_fetch_assoc($res)) {
 	$campaigns[]=array(
 		'id'=>$row['Deal Campaign Key'],
 		'code'=>$row['Deal Campaign Code'],
 		'name'=>$row['Deal Campaign Name']
 		);

 }
 $smarty->assign('campaigns',$campaigns);

 $smarty->display('new_deal.tpl');
 ?>
