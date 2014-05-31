<?php
include_once('common.php');
include_once('class.Store.php');

if(isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ){
  $store_id=$_REQUEST['store'];

}else{
header('Location: index.php?error');

}

if(! ($user->can_view('stores') and in_array($store_id,$user->stores)   ) ){
  header('Location: index.php?error_store='.$store_id);
   exit;
}

$store=new Store($store_id);
$smarty->assign('store',$store);
$modify=$user->can_edit('customers');



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 'css/common.css',
		 'css/container.css',
		 'css/table.css'
		 );

$css_files[]='theme.css.php';
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'animation/animation-min.js',

		$yui_path.'datasource/datasource.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'editor/editor-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/phpjs.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'js/edit_common.js',
		'search_customers.js.php'
	
		);
		
		


$smarty->assign('parent','customers');
$smarty->assign('title', _('Advanced Search, Customers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->display('search_customers.tpl');
?>
