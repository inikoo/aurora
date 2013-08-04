<?php
/*
 File: insert_csv.php

 UI store page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
/*ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);*/
include_once('common.php');
$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'editor/assets/skins/sam/editor.css',
               $yui_path.'assets/skins/sam/autocomplete.css',

               
               'css/common.css',
               'css/button.css',
               'css/container.css',
               'css/table.css',
               'theme.css.php'
               
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'uploader/uploader-debug.js',
		'js/php.default.min.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'js/insert_csv.js'
        	);
        	
        	
  if (!isset($_REQUEST['subject'])) {
    exit("to do a page where the user can choose the correct options");
}
if (!isset($_REQUEST['subject_key'])) {
    if ($_REQUEST['subject']!='staff' && $_REQUEST['subject']!='positions' && $_REQUEST['subject']!='areas' && $_REQUEST['subject']!='departments')
        exit("to do a page where the user can choose the correct options");
}
$scope=$_REQUEST['subject'];
$scope_key=$_REQUEST['subject_key'];

switch ($scope) {
case 'customers_store':
    include_once('class.Store.php');
    $store=new Store($scope_key);
    $smarty->assign('store',$store);
    $smarty->assign('store_id',$store->id);
    $smarty->assign('search_label',_('Customers'));
    $smarty->assign('search_scope','customers');

    $smarty->assign('search_type','customers_store');

    break;
case 'family':
    include_once('class.Store.php');
    $store=new Store($scope_key);
    $smarty->assign('store',$store);
    $smarty->assign('store_id',$store->id);
    $smarty->assign('search_label',_('Products'));
    $smarty->assign('search_scope','products');

    $smarty->assign('search_type','customers_store');

    break;
case 'department':
    include_once('class.Store.php');
    $store=new Store($scope_key);
    $smarty->assign('store',$store);
    $smarty->assign('store_id',$store->id);
    $smarty->assign('search_label',_('Products'));
    $smarty->assign('search_scope','products');

    $smarty->assign('search_type','customers_store');

    break;
case 'store':
    include_once('class.Store.php');
    $store=new Store($scope_key);
    $smarty->assign('store',$store);
    $smarty->assign('store_id',$store->id);
    $smarty->assign('search_label',_('Customers'));
    $smarty->assign('search_scope','customers');

    $smarty->assign('search_type','customers_store');

    break;
default:
    return;
    break;
}      	
$smarty->assign('scope',$scope);
$smarty->assign('scope_key',$scope_key);

$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);






$smarty->display('insert_csv.tpl');
unset($_SESSION['getQueryString']);


?>
