<?php
/*
 File: suppliers.php 

 UI suppliers page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');


if($user->data['User Type']=='Supplier'){
  
  
  if(count($user->suppliers)==1){
    header('Location: supplier.php?id='.$user->suppliers[0]);
    exit;
  }
}


if(!($user->can_view('suppliers'))){
  header('Location: index.php');
   exit;
}


$q='';
$sql="select count(*) as numberof from `Supplier Dimension`";
$result=mysql_query($sql);
if(!$suppliers=mysql_fetch_array($result, MYSQL_ASSOC))
  exit;


$create=$user->can_create('suppliers');

$modify=$user->can_edit('suppliers');


$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

$smarty->assign('view',$_SESSION['state']['suppliers']['view']);

$smarty->assign('show_details',$_SESSION['state']['suppliers']['details']);


$general_options_list=array();


if($modify){
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_suppliers.php','label'=>_('Edit Suppliers'));
   $general_options_list[]=array('tipo'=>'url','url'=>'new_supplier.php','label'=>_('Add Supplier'));
}
//$general_options_list[]=array('tipo'=>'js','state'=>$show_details,'id'=>'details','label'=>($show_details?_('Hide Details'):_('Show Details')));
//$general_options_list[]=array('tipo'=>'js','state'=>'','id'=>'advanced_search','label'=>_('Advanced Search'));

$smarty->assign('general_options_list',$general_options_list);


$smarty->assign('search_label',_('Supplies'));
$smarty->assign('search_scope','supplies');

//$smarty->assign('box_layout','yui-t4');



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 		 $yui_path.'assets/skins/sam/autocomplete.css',

		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
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
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		//	'js/calendar_common.js.php',

		'suppliers.js.php'
		);





$smarty->assign('parent','suppliers');
$smarty->assign('title', _('Suppliers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$smarty->assign('total_suppliers',$suppliers['numberof']);
$smarty->assign('table_title',_('Suppliers List'));

$tipo_filter=($q==''?$_SESSION['state']['suppliers']['table']['f_field']:'public_id');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state']['suppliers']['table']['f_value']:addslashes($q)));


$filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Suppliers with code starting with  <i>x</i>','label'=>'Code'),
		   'name'=>array('db_key'=>'name','menu_label'=>'Suppliers which name starting with <i>x</i>','label'=>'Name'),
		   'low'=>array('db_key'=>'low','menu_label'=>'Suppliers with more than <i>n</i> low stock products','label'=>'Low'),
		   'outofstock'=>array('db_key'=>'outofstock','menu_label'=>'Suppliers with more than <i>n</i> products out of stock','label'=>'Out of Stock'),
		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

//$smarty->assign('table_info',$orders.'  '.ngettext('Order','Orders',$orders));
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);





$smarty->display('suppliers.tpl');
?>