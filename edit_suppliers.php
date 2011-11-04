<?php
/*
 File: suppliers.php 

 UI suppliers page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('common.php');


if(!($user->can_view('suppliers'))){
  header('Location: index.php');
   exit;
}

if(!($user->can_edit('suppliers'))){
  header('Location: suppliers.php');
  exit;
}




$general_options_list=array();



  $general_options_list[]=array('tipo'=>'url','url'=>'suppliers.php','label'=>_('Exit Edit'));
   $general_options_list[]=array('tipo'=>'url','url'=>'new_suppler.php','label'=>_('Add Supplier'));

$smarty->assign('general_options_list',$general_options_list);



//$smarty->assign('box_layout','yui-t4');



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		
		 'button.css',
		 'container.css',
		 'css/edit.css'
		 );

$css_files[]='theme.css.php';


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
		'js/common.js',
		'js/table_common.js',
	
		'js/edit_common.js',
	
		'edit_suppliers.js.php'
		);



 $js_files[]='js/validate_telecom.js';
  $js_files[]='new_company.js.php?scope=supplier';
  $js_files[]='edit_address.js.php';
  $js_files[]='edit_contact_from_parent.js.php';
  $js_files[]='edit_contact_telecom.js.php';
  $js_files[]='edit_contact_name.js.php';
  $js_files[]='edit_contact_email.js.php';


$smarty->assign('edit',$_SESSION['state']['suppliers']['edit']);


$smarty->assign('scope','supplier');

$smarty->assign('parent','suppliers');
$smarty->assign('title', _('Edit Suppliers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$tipo_filter=$_SESSION['state']['suppliers']['table']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['suppliers']['table']['f_value']);


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

$smarty->display('edit_suppliers.tpl');
?>
