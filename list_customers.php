<?php
include_once('common.php');


$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 'common.css',
		 'css/container.css',
		 'table.css'
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
		'search_customers.js.php'
		);

$_SESSION['state']['customers']['advanced_search']['where']='';
$smarty->assign('parent','customers');
$smarty->assign('title', _('Advanced Search, Customers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Search Results'));
$smarty->assign('view',$_SESSION['state']['customers']['customers']['view']);
$smarty->assign('home',$myconf['_home']);
$smarty->display('search_customers.tpl');
?>