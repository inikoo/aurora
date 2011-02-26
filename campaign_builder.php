<?php
include_once('common.php');
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);

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
		'common.js.php',
		'table_common.js.php','js/edit_common.js','js/csv_common.js',
		'js/jquery-1.4.4.js'
		);

	$key = 'Email Campaign Key';
	$name = 'Email Campaign Name';
	$emails = 'Email Campaign Maximum Emails';
	$obj = 'Email Campaign Objective';
	$status = 'Email Campaign Status';

	$campaign=array();

	$sqlCount = "select `Email Campaign Key`,`Email Campaign Name`,`Email Campaign Maximum Emails`,`Email Campaign Objective`,`Email Campaign Status` from `Email Campaign Dimension";
		$queryCount = mysql_query($sqlCount);
		
		$campaign_size=mysql_num_rows($queryCount);
		while($k=mysql_fetch_assoc($queryCount))
		{
		  	$campaign[]=$k;
                	
       	 	}
		//print_r($campaign);

	
$smarty->assign('campaign',$campaign);

$smarty->assign('campaign_size',$campaign_size);
$msg = isset($_SESSION['msg'])?$_SESSION['msg']:'';

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('msg',$msg);

$smarty->assign('name',$name);
$smarty->assign('emails',$emails);
$smarty->assign('obj',$obj);
$smarty->assign('status',$status);
$smarty->assign('key',$key);


$smarty->display('campaign_builder.tpl');
unset($_SESSION['msg']);
?>
