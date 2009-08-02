<?php
//@create 22/04/09 14:37
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Customer.php');
if(!$LU->checkRight(CUST_VIEW))
  exit;


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		  $yui_path.'editor/assets/skins/sam/editor.css',
		  'text_editor.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'editor/editor-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/search.js',
		'js/address_form.js',
		'js/new_customer.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$smarty->assign('box_layout','yui-t0');

$smarty->assign('parent','customers.php');
$smarty->assign('title',_('New Customer'));



$customer_home=_("Customers List");


$smarty->display('new_customer.tpl');
?>