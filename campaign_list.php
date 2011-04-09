<?php
include_once('common.php');


$modify=$user->can_edit('staff');

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
		'js/common.js',
		'js/table_common.js','js/edit_common.js','js/csv_common.js',
		'campaign_list.js.php',
		'js/jquery-1.4.4.js'
		);


	$msg = isset($_SESSION['msg'])?$_SESSION['msg']:'';

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('msg',$msg);


$smarty->display('campaign_list.tpl');
unset($_SESSION['msg']);
?>
