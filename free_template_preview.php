<?php
include_once('common.php');
if(!isset($_REQUEST['createCampaign']) ){
header('Location: index.php');
   exit;
}



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 'common.css',
		 'container.css',
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
		$yui_path.'calendar/calendar-min.js',
		'common.js.php',
		'table_common.js.php',
		'common_customers.js.php',
		'new_customers_list.js.php',
		'js/edit_common.js',
		'js/list_function.js',
		'js/create_campaign.js',
		'external_libs/ckeditor/ckeditor.js'
		);
	
	

if(isset($_REQUEST['createCampaign']))
{    
	$f_template_body=stripslashes($_REQUEST['f_template_body']);
	$smarty->assign('template_sub',$_REQUEST['f_template_sub']);
	$smarty->assign('template_body',$f_template_body);

	$_SESSION['body'] = $f_template_body;
	$_SESSION['subject'] = $_REQUEST['f_template_sub'];
	
}

$smarty->assign('title', _('Customers Lists'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display('free_template_preview.tpl');
?>
