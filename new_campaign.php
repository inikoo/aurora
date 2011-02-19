<?php
include_once('common.php');

if(!$user->can_view('customers') ){
header('Location: index.php');
   exit;
 }

if(isset($_REQUEST['customer_list_key']) and is_numeric($_REQUEST['customer_list_key']) ){
  $customer_list_key=$_REQUEST['customer_list_key'];

}else{
header('Location: index.php?error');

}

/*if(! ($user->can_view('stores') and in_array($store_id,$user->stores)   ) ){
  header('Location: index.php?error_store='.$store_id);
   exit;
}*/



$smarty->assign('customer_list_key',$customer_list_key);

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
		'js/create_campaign.js'
		);


	
		$sqlQuery = "select `Customer List Name` from `Customer List Dimension` where `Customer List Key` = '".$customer_list_key."'";
		$queryResult = mysql_query($sqlQuery);
		$row = mysql_fetch_array($queryResult);
		$smarty->assign('listName',$row['Customer List Name']);


$_SESSION['state']['customers']['list']['where']='';
$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers Lists'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

	


//$smarty->assign('view',$_SESSION['state']['customers']['list']['view']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

/*$product_ordered_or='∀';
$smarty->assign('product_ordered_or',$product_ordered_or);*/

$smarty->display('new_campaigns.tpl');
?>
