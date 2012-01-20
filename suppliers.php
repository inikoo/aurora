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


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
               'theme.css.php'
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
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'js/suppliers_common.js',
		'suppliers.js.php',
        'js/edit_common.js',
        'js/csv_common.js'

		);





$smarty->assign('parent','suppliers');
$smarty->assign('title', _('Suppliers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




if($user->data['User Type']=='Supplier'){
   if(count($user->suppliers)==0){
   $smarty->display('forbidden.tpl');
    exit();
  }
  
  if(count($user->suppliers)==1){
    header('Location: supplier.php?id='.$user->suppliers[0]);
    exit;
  }
}else{


if(!($user->can_view('suppliers')     )){
  header('Location: index.php');
   exit;
}
}

$q='';
$sql="select count(*) as numberof from `Supplier Dimension`";
$result=mysql_query($sql);
if(!$suppliers=mysql_fetch_array($result, MYSQL_ASSOC))
  exit;


$create=$user->can_create('suppliers');

$modify=$user->can_edit('suppliers');
$view_sales=$user->can_view('supplier sales');



$view_stock=$user->can_view('supplier stock');

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);


$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

$smarty->assign('suppliers_view',$_SESSION['state']['suppliers']['suppliers']['view']);
$smarty->assign('suppliers_period',$_SESSION['state']['suppliers']['suppliers']['period']);

$smarty->assign('supplier_products_view',$_SESSION['state']['suppliers']['supplier_products']['view']);
$smarty->assign('supplier_products_period',$_SESSION['state']['suppliers']['supplier_products']['period']);
$smarty->assign('supplier_products_avg',$_SESSION['state']['suppliers']['supplier_products']['avg']);


$smarty->assign('options_box_width','400px');
$smarty->assign('block_view',$_SESSION['state']['suppliers']['block_view']);

$general_options_list=array();


if($modify){
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_suppliers.php','label'=>_('Edit Suppliers'));
   $general_options_list[]=array('tipo'=>'url','url'=>'new_supplier.php','label'=>_('Add Supplier'));
}
//$general_options_list[]=array('tipo'=>'js','state'=>$show_details,'id'=>'details','label'=>($show_details?_('Hide Details'):_('Show Details')));
$general_options_list[]=array('tipo'=>'url','url'=>'suppliers_lists.php','label'=>_('Lists'));
$general_options_list[]=array('tipo'=>'url','url'=>'supplier_categories.php','label'=>_('Categories'));

//$smarty->assign('general_options_list',$general_options_list);


$smarty->assign('search_label',_('Suppliers'));
$smarty->assign('search_scope','supplier_products');

//$smarty->assign('box_layout','yui-t4');
//print_r($_SESSION['state']['suppliers']);



$smarty->assign('total_suppliers',$suppliers['numberof']);


$tipo_filter=$_SESSION['state']['suppliers']['suppliers']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['suppliers']['suppliers']['f_value']);


$filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>_('Suppliers with code starting with  <i>x</i>'),'label'=>_('Code')),
		   'name'=>array('db_key'=>'name','menu_label'=>_('Suppliers which name starting with <i>x</i>'),'label'=>_('Name')),
		   'low'=>array('db_key'=>'low','menu_label'=>_('Suppliers with more than <i>n</i> low stock products'),'label'=>_('Low')),
		   'outofstock'=>array('db_key'=>'outofstock','menu_label'=>_('Suppliers with more than <i>n</i> products out of stock'),'label'=>_('Out of Stock')),
		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

//$smarty->assign('table_info',$orders.'  '.ngettext('Order','Orders',$orders));
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

 

$tipo_filter=$_SESSION['state']['suppliers']['supplier_products']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['suppliers']['supplier_products']['f_value']);


$filter_menu=array(
		   'sup_code'=>array('db_key'=>'code','menu_label'=>_('Suppliers products with code starting with  <i>x</i>'),'label'=>_('Code')),
		   );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

//$smarty->assign('table_info',$orders.'  '.ngettext('Order','Orders',$orders));
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$smarty->assign('supplier_id',false);


$smarty->display('suppliers.tpl');
?>
