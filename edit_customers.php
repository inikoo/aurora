<?php
/*
 File: customers.php 

 UI customers page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');

if(!$user->can_view('customers')){
  header('Location: index.php');
  exit;
}

if(!$user->can_edit('customers')){
  header('Location: customers.php');
  exit;
}

if(isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ){
  $store_id=$_REQUEST['store'];

}else{
  $store_id=$_SESSION['state']['customers']['store'];

}

if(!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ){
  header('Location: index.php');
   exit;
}



$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');




$store=new Store($store_id);
$smarty->assign('store',$store);

$_SESSION['state']['customers']['store']=$store_id;

$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'customers.php','label'=>_('Exit Edit'));
$general_options_list[]=array('tipo'=>'url','url'=>'new_customer.php?edit=1','label'=>_('Add Customer'));
$smarty->assign('general_options_list',$general_options_list);



$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'common.css',
               'button.css',
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
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
              'js/csv_common.js',
              'common_customers.js.php',
              'customers.js.php',
              'js/import_csv.js'
          );

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
 $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'table.css',
		 'css/edit.css'
		 );
include_once('Theme.php');
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js',
		  'js/search.js',
		'js/table_common.js',
		'js/edit_common.js',
		'edit_customers.js.php'
		);



$smarty->assign('options_box_width','200px');

$smarty->assign('parent','customers');
$smarty->assign('title', _('Edit Customers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$tipo_filter=$_SESSION['state']['customers']['table']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['customers']['table']['f_value']);

$filter_menu=array(
		   'customer name'=>array('db_key'=>_('customer name'),'menu_label'=>'Customer Name','label'=>'Name'),
		   'postcode'=>array('db_key'=>_('postcode'),'menu_label'=>'Customer Postcode','label'=>'Postcode'),
		   'min'=>array('db_key'=>_('min'),'menu_label'=>'Mininum Number of Orders','label'=>'Min No Orders'),
		   'max'=>array('db_key'=>_('min'),'menu_label'=>'Maximum Number of Orders','label'=>'Max No Orders'),

		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

 $smarty->assign('view',$_SESSION['state']['customers']['view']);
 
 


$smarty->display('edit_customers.tpl');
?>
