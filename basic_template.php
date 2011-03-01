<?php
include_once('common.php');
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);
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
		 'table.css',
                 'css/marketing_campaigns.css'
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
	if(isset($_REQUEST['basic']) && $_REQUEST['basic'] == 'Proceed')
	{
		
		$header = $_REQUEST['basicheader'];
		$title = $_REQUEST['basictitle'];
		$block1 = $_REQUEST['basicPBlock1'];
		
		$block2 = $_REQUEST['basicPBlock2'];
		
		$block3 = $_REQUEST['basicPBlock3'];
		
	
		
		
	

		$_SESSION['header'] = $header;
		$_SESSION['contenttitle'] = $title;
		$_SESSION['block1'] = $block1;
		
		$_SESSION['block2'] = $block2;
	
		$_SESSION['block3'] = $block3;
			


	}

if($_FILES['basicPBlock1image']["name"]!='' || $_FILES['basicPBlock21image']["name"]!='' || $_FILES['basicPBlock3image']["name"]!='')
		{
      move_uploaded_file($_FILES["basicPBlock1image"]["tmp_name"],
      "app_files/uploads/" . $_FILES["basicPBlock1image"]["name"]);
      $image1="app_files/uploads/" . $_FILES["basicPBlock1image"]["name"];

	      move_uploaded_file($_FILES["basicPBlock21image"]["tmp_name"],
      "app_files/uploads/" . $_FILES["basicPBlock21image"]["name"]);
      $image2="app_files/uploads/" . $_FILES["basicPBlock21image"]["name"];

      move_uploaded_file($_FILES["basicPBlock3image"]["tmp_name"],
      "app_files/uploads/" . $_FILES["basicPBlock3image"]["name"]);
      $image3="app_files/uploads/" . $_FILES["basicPBlock3image"]["name"];


$smarty->assign('image1',$image1);
$smarty->assign('image2',$image2);
$smarty->assign('image3',$image3);

	
		}
$smarty->assign('header',$_SESSION['header']);
$smarty->assign('contenttitle',$_SESSION['contenttitle']);
$smarty->assign('block1',$_SESSION['block1']);

$smarty->assign('block2',$_SESSION['block2']);

$smarty->assign('block3',$_SESSION['block3']);


	

$smarty->assign('title','Basic Template Preview');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display('basic_template.tpl');
?>
