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
		

if(isset($_REQUEST['newsletter2']) && $_REQUEST['newsletter2'] == 'Proceed')
	{
		
		$news2header = $_REQUEST['news2header'];
		$news2title = $_REQUEST['news2title'];
		$news2block1 = $_REQUEST['news2PBlock1'];
		$news2image1 = $_REQUEST['news2PBlock1image'];
		$news2block2 = $_REQUEST['news2PBlock2'];
		$news2image2 = $_REQUEST['news2PBlock2image'];
		$news2block3 = $_REQUEST['news2PBlock3'];
		$image3 = $_REQUEST['news2PBlock3image'];

		$_SESSION['news2header'] = $news2header;
		$_SESSION['news2title'] = $news2title;
		$_SESSION['news2block1'] = $news2block1;
		$_SESSION['news2image1'] = $news2image1;
		$_SESSION['news2block2'] = $news2block2;
		$_SESSION['news2image2'] = $news2image2;
		$_SESSION['news2block3'] = $news2block3;
		$_SESSION['news2image3'] = $news2image3;		

	}

$smarty->assign('news2header',$_SESSION['news2header']);
$smarty->assign('news2title',$_SESSION['news2title']);
$smarty->assign('news2block1',$_SESSION['news2block1']);
$smarty->assign('news2image1',$_SESSION['news2image1']);
$smarty->assign('news2block2',$_SESSION['news2block2']);
$smarty->assign('news2image2',$_SESSION['news2image2']);
$smarty->assign('news2block3',$_SESSION['news2block3']);
$smarty->assign('news2image3',$_SESSION['news2image3']);

	
		

$smarty->assign('title', 'Newsletter Template 2');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display('newsletter_template2.tpl');
?>
