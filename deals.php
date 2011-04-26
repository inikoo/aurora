<?php
/*
 File: store.php 

 UI store page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2010, Inikoo 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');
include_once('assets_header_functions.php');


if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $store_id=$_REQUEST['id'];

}else{
  $store_id=$_SESSION['state']['store']['id'];
}


if(!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ){
  header('Location: index.php');
   exit;
}


$store=new Store($store_id);
$_SESSION['state']['store']['id']=$store->id;

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product departments');



$modify=$user->can_edit('stores');


$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);



$show_details=$_SESSION['state']['store']['details'];
$smarty->assign('show_details',$show_details);
get_header_info($user,$smarty);

$general_options_list=array();

$smarty->assign('general_options_list',$general_options_list);






$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		 'css/dropdown.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/php.default.min.js',
		'js/common.js',
		'js/table_common.js',
		'js/dropdown.js',
		'deals.js.php',
		 'js/edit_common.js',
	         'js/csv_common.js'

		);


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);





$smarty->assign('store',$store);

$smarty->assign('parent','products');
$smarty->assign('title', $store->data['Store Name']);




  $q='';
  $tipo_filter=($q==''?$_SESSION['state']['deals']['table']['f_field']:'code');
  $smarty->assign('filter',$tipo_filter);
  $smarty->assign('filter_value',($q==''?$_SESSION['state']['deals']['table']['f_value']:addslashes($q)));
  $filter_menu=array(
		   'name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Name')
		   
		   );
  $smarty->assign('filter_menu0',$filter_menu);
  $smarty->assign('departments',$store->data['Store Departments']);
  $smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
  $paginator_menu=array(10,25,50,100,500);
  $smarty->assign('paginator_menu0',$paginator_menu);



  $csv_export_options=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['deals']['table']['csv_export']['name']),
                                                             'trigger'=>array('label'=>_('Trigger'),'selected'=>$_SESSION['state']['deals']['table']['csv_export']['trigger']),
                                                             'target'=>array('label'=>_('Target'),'selected'=>$_SESSION['state']['deals']['table']['csv_export']['target']),
                                                             
                                                             'status'=>array('label'=>_('Status'),'selected'=>$_SESSION['state']['deals']['table']['csv_export']['status']),
                                                   
                                                            
                                                     
                                                         )
                                                     )
                                          ),
                            'other_details'=>array(
                                        'title'=>_('Other Details'),
                                        'rows'=>
                                               array(
                                                   array(
                                                       'terms_description'=>array('label'=>_('Terms Description'),'selected'=>$_SESSION['state']['deals']['table']['csv_export']['terms_description']),
                                                       'allowance_description'=>array('label'=>_('Allowance Description'),'selected'=>$_SESSION['state']['deals']['table']['csv_export']['allowance_description']),
                                                       'terms_type'=>array('label'=>_('Terms Type'),'selected'=>$_SESSION['state']['deals']['table']['csv_export']['terms_type'])
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols',2);

                        
$smarty->assign('csv_export_options',$csv_export_options); 
 
  $smarty->display('deals.tpl');
 
?>
