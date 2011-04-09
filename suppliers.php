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
$view_sales=$user->can_view('supplier sales');



$view_stock=$user->can_view('supplier stock');

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);


$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

$smarty->assign('suppliers_view',$_SESSION['state']['suppliers']['suppliers']['view']);
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

$smarty->assign('general_options_list',$general_options_list);


$smarty->assign('search_label',_('Search'));
$smarty->assign('search_scope','suppliers');

//$smarty->assign('box_layout','yui-t4');
//print_r($_SESSION['state']['suppliers']);

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 		 $yui_path.'assets/skins/sam/autocomplete.css',

		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 
		 'button.css',
		 'container.css',
		
		 );

$theme="";
if($theme)
{
array_push($css_files, 'themes_css/'.$Themecss1);   
array_push($css_files, 'themes_css/'.$Themecss2);
array_push($css_files, 'themes_css/'.$Themecss3);
}    

else{
array_push($css_files, 'common.css'); 
array_push($css_files, 'css/dropdown.css');
array_push($css_files, 'table.css');
}


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



$smarty->assign('total_suppliers',$suppliers['numberof']);
$smarty->assign('table_title',_('Suppliers List'));

$tipo_filter=($q==''?$_SESSION['state']['suppliers']['suppliers']['f_field']:'public_id');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state']['suppliers']['suppliers']['f_value']:addslashes($q)));


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

    $csv_export_options=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'id'=>array('label'=>_('Id'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['id']),
                                                             'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['code']),
                                                             'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['name']),
                                                             'opo'=>array('label'=>_('Open Purchase Orders'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['opo']),
                                                             
                                                         )
                                                     )
                                          ),
			    'supplier_details'=>array(
                                              'title'=>_('Supplier Details'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'contact_name'=>array('label'=>_('Contact Name'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['contact_name']),
                                                             'telephone'=>array('label'=>_('Telephone'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['telephone']),
                                                             'email'=>array('label'=>_('Email'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['email']),
                                                             'currency'=>array('label'=>_('Currency'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['currency']),
                                                             
                                                         )
                                                     )
                                          ),
                            'stock'=>array(
                                        'title'=>_('Stock'),
                                        'rows'=>
                                               array(
                                                   array(
						       'discontinued'=>array('label'=>_('Discontinued'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['discontinued']),
                                                       'surplus'=>array('label'=>_('Surplus'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['surplus']),
                                                       'ok'=>array('label'=>_('Ok'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['ok']),
                                                       'low'=>array('label'=>_('Low'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['low']),
                                                       'critical'=>array('label'=>_('Critical'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['critical']),
                                                       'gone'=>array('label'=>_('Gone'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['gone']),
                                                
                                                       
                                                       

                                                   )
                                               )
                                    ),
                            'sales_all'=>array('title'=>_('Sales (All times)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'cost_all'=>array('label'=>_('Costs'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['cost_all']),
                                                       'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['profit_all']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'cost_1y'=>array('label'=>_('Costs'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['cost_1y']),
                                                       'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['profit_1y']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'cost_1q'=>array('label'=>_('Costs'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['cost_1q']),
                                                       'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['profit_1q']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'cost_1m'=>array('label'=>_('Costs'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['cost_1m']),
                                                       'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['profit_1m']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
                            'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'cost_1w'=>array('label'=>_('Costs'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['cost_1w']),
                                                       'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['suppliers']['suppliers']['csv_export']['profit_1w']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols',8);
$smarty->assign('csv_export_options',$csv_export_options);



$smarty->display('suppliers.tpl');
?>
