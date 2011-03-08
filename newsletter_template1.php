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
		'js/jquery-1.4.4.js',
                 'customer_list_marketing.js.php'
		);
	if(isset($_REQUEST['basic']) && $_REQUEST['basic'] == 'Proceed')
	{
		
		
		
	
		
		
	

		
			


	}

if($_FILES['news1_Block1image']["name"]!='' || $_FILES['news1_Block2image']["name"]!='' || $_FILES['news1_Block3image']["name"]!='')
		{

	$key0 = rand(1,2000);	
	$key1 = rand(1,1000);
	$key2 = rand(1,100);
	
	$firstImage = $key0.$_FILES["news1_Block1image"]["name"];
	$secondImage = $key1.$_FILES["news1_Block2image"]["name"];
	$thirdImage = $key2.$_FILES["news1_Block3image"]["name"];

	

        move_uploaded_file($_FILES["news1_Block1image"]["tmp_name"],"app_files/uploads/" .$firstImage);
      $image1="app_files/uploads/" .$firstImage;

      move_uploaded_file($_FILES["news1_Block2image"]["tmp_name"],"app_files/uploads/" .$secondImage);
      $image2="app_files/uploads/" .$secondImage;

      move_uploaded_file($_FILES["news1_Block3image"]["tmp_name"],"app_files/uploads/" .$thirdImage);
      $image3="app_files/uploads/" .$thirdImage;

$_SESSION['news1_image1']=$image1;
$_SESSION['news1_image2']=$image2;
$_SESSION['news1_image3']=$image3;




$smarty->assign('image1',$_SESSION['news1_image1']);
$smarty->assign('image2',$image2);
$smarty->assign('image3',$image3);

	
		}




$_SESSION['header'] =  $_REQUEST['news1_header'];
		$_SESSION['contenttitle'] = $_REQUEST['news1_title'];
		$_SESSION['block1'] = $_REQUEST['news1_Block1'];
		
		$_SESSION['block2'] = $_REQUEST['news1_Block2'];
	
		$_SESSION['block3'] = $_REQUEST['news1_Block3'];




$smarty->assign('header',$_SESSION['header']);
$smarty->assign('contenttitle',$_SESSION['contenttitle']);
$smarty->assign('block1',$_SESSION['block1']);

$smarty->assign('block2',$_SESSION['block2']);

$smarty->assign('block3',$_SESSION['block3']);


	

$smarty->assign('title','Newsletter1 Preview');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display('newsletter_template1.tpl');
?>




