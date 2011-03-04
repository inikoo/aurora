<?php
include_once('common.php');
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);

$general_options_list=array();

$smarty->assign('general_options_list',$general_options_list);

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 'common.css',
		 'container.css',
		 'table.css',
		'css/marketing_campaigns.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php','js/edit_common.js','js/csv_common.js',
		'js/jquery-1.4.4.js',
		'external_libs/ckeditor/ckeditor.js'
		);


	if(isset($_REQUEST['check']))
	{
		$_SESSION['check_mail_list'] = $_REQUEST['check'];	
	
	}



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->display('send_mail_list.tpl');
?>
