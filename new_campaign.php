<?php
include_once('common.php');
if(!$user->can_view('customers') ){
header('Location: index.php');
   exit;
}
/*if(isset($_REQUEST['customer_list_key']) and is_numeric($_REQUEST['customer_list_key']) ){
  $customer_list_key=$_REQUEST['customer_list_key'];

}else{
header('Location: index.php?error');
}*/
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
		'external_libs/ckeditor/ckeditor.js'
		);
		//fetch the customer list name
		$queryString = "select `Customer List Name`,`Customer List Key` from `Customer List Dimension` order by `Customer List Key` desc";
		$resultSet = mysql_query($queryString);		
		$n = 'Customer List Name';
		$k = 'Customer List Key';
		$customer = array();
		while($row = mysql_fetch_assoc($resultSet))
		{
		   $customer[] = $row;
		   
		}
		$smarty->assign('n',$n);
		$smarty->assign('k',$k);
		$smarty->assign('customer',$customer);
		

$_SESSION['state']['customers']['list']['where']='';
$smarty->assign('parent','customers');
$smarty->assign('title', _('Create Campaign'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


if(isset($_SESSION['disp_msg']) && $_SESSION['disp_msg'] != ''){

	$smarty->assign('msg',$_SESSION['disp_msg']);
	//unset($_SESSION['disp_msg']);
}else{
	$smarty->assign('msg','');
}

if(isset($_REQUEST['link']))
{
	$href='<a href=campaign_builder.php><font color=red>Click</font></a> here to go back';
	$link = '<span style="font-size:11px;">'.stripslashes($_REQUEST['link']).'&nbsp;&nbsp;'. $href.'</span>';
	$smarty->assign('link',$link);	
}

if($_SESSION['succ'] = 'yes'){
	$smarty->assign('msg','');
	$smarty->assign('campaign_name','');
	$smarty->assign('campaign_obj','');
	$smarty->assign('campaign_mail','');
	$smarty->assign('campaign_content','');
	unset($_SESSION['succ']);
}elseif($_SESSION['succ'] = 'no'){
	$smarty->assign('campaign_name',$_SESSION['campaign_name']);
	$smarty->assign('campaign_obj',$_SESSION['campaign_obj']);
	$smarty->assign('campaign_mail',$_SESSION['campaign_mail']);
	$smarty->assign('campaign_content',$_SESSION['campaign_content']);
	unset($_SESSION['succ']);
}else{
	$_SESSION['succ'] = '';
}

//$smarty->assign('view',$_SESSION['state']['customers']['list']['view']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
/*$product_ordered_or='∀';
$smarty->assign('product_ordered_or',$product_ordered_or);*/
$smarty->display('new_campaigns.tpl');
?>
