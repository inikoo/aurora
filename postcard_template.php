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
	if(isset($_REQUEST['postcard']) && $_REQUEST['postcard'] == 'Proceed')
	{
		
		$header = $_REQUEST['Pcardheader'];
	
		$block1 = $_REQUEST['PcardBlock'];
		
		
		
	

		$_SESSION['header'] = $header;
	
		$_SESSION['block1'] = $block1;
		

	}

if($_FILES['PcardImage']["name"]!='')
		{
      move_uploaded_file($_FILES["PcardImage"]["tmp_name"],
      "app_files/uploads/" . $_FILES["PcardImage"]["name"]);
      $image1="app_files/uploads/" . $_FILES["PcardImage"]["name"];
$_SESSION['postcard_image']=$image1;

$smarty->assign('image1',$image1);

	
		}
$smarty->assign('header',$_SESSION['header']);

$smarty->assign('block1',$_SESSION['block1']);






	

$smarty->assign('title','Postcard Preview');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display('postcard_template.tpl');
?>
