<?php
include_once('common.php');
if(!$user->can_view('customers') ){
header('Location: index.php');
   exit;
}

/*if(! ($user->can_view('stores') and in_array($store_id,$user->stores)   ) ){
  header('Location: index.php?error_store='.$store_id);
   exit;
}*/

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
		'external_libs/ckeditor/ckeditor.js',
		'js/jquery-1.4.4.js'
		);

if(isset($_REQUEST['newsletter1']) && $_REQUEST['newsletter1'] == 'Proceed')
	{
		
		$newsheader = $_REQUEST['news1header'];
		$newstitle = $_REQUEST['news1title'];
		$newsblock1 = $_REQUEST['news1PBlock1'];
		$newsimage1 = $_REQUEST['news1PBlock1image'];
		$newsblock2 = $_REQUEST['news1PBlock2'];
		$newsimage2 = $_REQUEST['news1PBlock2image'];
		$newsblock3 = $_REQUEST['news1PBlock3'];
		$newsimage3 = $_REQUEST['news1PBlock3image'];

		$_SESSION['newsheader'] = $newsheader;
		$_SESSION['newstitle'] = $newstitle;
		$_SESSION['newsblock1'] = $newsblock1;
		$_SESSION['newsimage1'] = $newsimage1;
		$_SESSION['newsblock2'] = $newsblock2;
		$_SESSION['newsimage2'] = $newsimage2;
		$_SESSION['newsblock3'] = $newsblock3;
		$_SESSION['newsimage3'] = $newsimage3;		

	}

$smarty->assign('newsheader',$_SESSION['newsheader']);
$smarty->assign('newstitle',$_SESSION['newstitle']);
$smarty->assign('newsblock1',$_SESSION['newsblock1']);
$smarty->assign('newsimage1',$_SESSION['newsimage1']);
$smarty->assign('newsblock2',$_SESSION['newsblock2']);
$smarty->assign('newsimage2',$_SESSION['newsimage2']);
$smarty->assign('newsblock3',$_SESSION['newsblock3']);
$smarty->assign('newsimage3',$_SESSION['newsimage3']);



$smarty->assign('title','Newsletter Template 1');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display('newsletter_template1.tpl');
?>
