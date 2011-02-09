<?php
include_once('common.php');

if(!$user->can_view('customers') ){
header('Location: index.php');
   exit;
 }

if(isset($_REQUEST['store_key']) and is_numeric($_REQUEST['store_key']) ){
  $store_id=$_REQUEST['store_key'];

}else{
header('Location: index.php?error');

}

if(! ($user->can_view('stores') and in_array($store_id,$user->stores)   ) ){
  header('Location: index.php?error_store='.$store_id);
   exit;
}

$store=new Store($store_id);
$smarty->assign('store',$store);

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
		'new_customers_list.js.php'
		);

$_SESSION['state']['customers']['list']['where']='';
$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers Lists'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$have_options=array(
'email'=>array('name'=>_('Email')),
'tel'=>array('name'=>_('Telephone')),
'fax'=>array('name'=>_('Fax')),
'address'=>array('name'=>_('Address')),
);
$smarty->assign('have_options',$have_options);

$dont_have_options=array(
'email'=>array('name'=>_('Email')),
'tel'=>array('name'=>_('Telephone')),
'fax'=>array('name'=>_('Fax')),
'address'=>array('name'=>_('Address')),
);
$smarty->assign('dont_have_options',$dont_have_options);

//$smarty->assign('view',$_SESSION['state']['customers']['list']['view']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->display('new_customers_lists.tpl');
?>