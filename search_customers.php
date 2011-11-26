<?php
include_once('common.php');
include_once('class.Store.php');

if(isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ){
  $store_id=$_REQUEST['store'];

}else{
header('Location: index.php?error');

}

if(! ($user->can_view('stores') and in_array($store_id,$user->stores)   ) ){
  header('Location: index.php?error_store='.$store_id);
   exit;
}

$store=new Store($store_id);
$smarty->assign('store',$store);
$modify=$user->can_edit('customers');
$general_options_list=array();

if ($modify) {
  //  $general_options_list[]=array('tipo'=>'url','url'=>'edit_customers.php','label'=>_('Edit Customers'));
  //  $general_options_list[]=array('tipo'=>'js','id'=>'new_customer','label'=>_('Add Customer'));
}


$general_options_list[]=array('tipo'=>'url','url'=>'customers_lists.php?store='.$store->id,'label'=>_('Customers Lists'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));

$general_options_list[]=array('tipo'=>'url','url'=>'customers_stats.php','label'=>_('Stats'));

$smarty->assign('general_options_list',$general_options_list);

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 'common.css',
		 'container.css',
		 'table.css'
		 );

$css_files[]='theme.css.php';
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'animation/animation-min.js',

		$yui_path.'datasource/datasource.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'editor/editor-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/phpjs.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'js/edit_common.js',
		'search_customers.js.php'
	
		);
		
		

$js_files[]='company.js.php';
$js_files[]='js/validate_telecom.js';
//$js_files[]='new_company.js.php?scope=customer&store_key='.$store_key;
$js_files[]='edit_address.js.php';
$js_files[]='edit_contact_from_parent.js.php';
$js_files[]='edit_contact_telecom.js.php';
$js_files[]='edit_contact_name.js.php';
$js_files[]='edit_contact_email.js.php';

$smarty->assign('parent','customers');
$smarty->assign('title', _('Advanced Search, Customers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->display('search_customers.tpl');
?>
