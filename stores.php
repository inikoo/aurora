<?php
/*
 File: stores.php 

 UI stores page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');
//include_once('stock_functions.php');
if(!$user->can_view('stores'))
  exit();

$avileable_stores_list=$user->stores;
$avileable_stores=count($avileable_stores_list);
if($avileable_stores==1){
  header('Location: store.php?id='.$avileable_stores_list[0]);
  
}

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('stores');
$modify=$user->can_edit('stores');



$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);


if(isset($_REQUEST['edit']))
  $edit=$_REQUEST['edit'];
else
  $edit=$_SESSION['state']['stores']['editing'];



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'
		 );
$js_files=array(
		
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/php.default.min.js',
		'common.js.php',
		'table_common.js.php',
		);

if($edit){
  $smarty->assign('edit',$_SESSION['state']['stores']['edit']);
  $css_files[]='css/edit.css';
  $js_files[]='js/edit_common.js';
  $js_files[]='country_select.js.php';
  $js_files[]='edit_stores.js.php';
 } else{
   $js_files[]='js/search.js';
   $js_files[]='stores.js.php';
 }


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$_SESSION['state']['assets']['page']='stores';
$smarty->assign('view',$_SESSION['state']['stores']['view']);
$smarty->assign('show_details',$_SESSION['state']['stores']['details']);

$smarty->assign('avg',$_SESSION['state']['stores']['avg']);
$smarty->assign('period',$_SESSION['state']['stores']['period']);


$smarty->assign('parent','stores.php');
$smarty->assign('title', _('Stores'));



global $myconf;
$stores=array();
$sql=sprintf("select count(distinct `Store Currency Code` ) as distint_currencies, sum(IF(`Store Currency Code`=%s,1,0)) as default_currency    from `Store Dimension` "
	     ,prepare_mysql($myconf['currency_code']));

$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){
  $distinct_currencies=$row['distint_currencies'];
  $default_currency=$row['default_currency'];
}

$mode_options=array(
		    array('mode'=>'percentage','label'=>_('Percentages')),
		    array('mode'=>'value','label'=>_('Values')),

		   );


$display_mode='value';
$display_mode_label=_('Values');
if($_SESSION['state']['stores']['percentages']){
  $display_mode='percentages';
  $display_mode_label=_('Percentages');
}

if($distinct_currencies>1){
  $mode_options[]=array('mode'=>'value_default_d2d','label'=>_("Values in")." ".$myconf['currency_code']." ("._('d2d').")");

  if($_SESSION['state']['stores']['table']['show_default_currency']){
    $display_mode='value_default_d2d';
    $display_mode_label=_("Values in")." ".$myconf['currency_code']." ("._('d2d').")";

    
    
    
  }
  
  
  
}


$smarty->assign('display_mode',$display_mode);
$smarty->assign('display_mode_label',$display_mode_label);




$q='';
$tipo_filter=($q==''?$_SESSION['state']['stores']['table']['f_field']:'code');

$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state']['stores']['table']['f_value']:addslashes($q)));
$filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>_('Store Code'),'label'=>_('Code')),
		   'name'=>array('db_key'=>'name','menu_label'=>_('Store Name'),'label'=>_('Name')),
		   );
$smarty->assign('filter_menu',$filter_menu);

$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);
$smarty->assign('mode_options_menu',$mode_options);

if($edit){
$smarty->display('edit_stores.tpl');
 }else
$smarty->display('stores.tpl');

?>