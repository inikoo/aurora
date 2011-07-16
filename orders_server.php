<?php
/*
 File: orders_server.php 

 Orders server when ther is more that one store

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/


include_once('common.php');
if(!$user->can_view('orders'))
  exit();








$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css'
		 );
		 
		 
	$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',

		 //	 $yui_path.'assets/skins/sam/autocomplete.css',
		 
		 'container.css',
		 'button.css',
		
		 'css/dropdown.css'
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
		$yui_path.'calendar/calendar-min.js',
				'js/php.default.min.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
                'js/dropdown.js',
                'js/edit_common.js','js/csv_common.js',
		'orders_server.js.php'
		);


$q='';

$general_options_list=array();
 $general_options_list[]=array('tipo'=>'url','url'=>'warehouse_orders.php','label'=>_('Warehouse Operations'));
$smarty->assign('general_options_list',$general_options_list);

$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');


if(isset($_REQUEST['view']) and preg_match('/^orders|invoices|dn$/',$_REQUEST['view'])){
$_SESSION['state']['stores']['orders_view']=$_REQUEST['view'];
}

$smarty->assign('block_view',$_SESSION['state']['stores']['orders_view']);
$smarty->assign('dn_view',$_SESSION['state']['stores']['delivery_notes']['view']);




$smarty->assign('parent','orders');
$smarty->assign('title', _('Orders'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$tipo_filter0=($q==''?$_SESSION['state']['stores']['orders']['f_field']:'public_id');
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0',($q==''?$_SESSION['state']['stores']['orders']['f_value']:addslashes($q)));
$filter_menu0=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Store Code starting with  <i>x</i>','label'=>'Code'),
		   
		   );
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);
$smarty->assign('paginator_menu1',$paginator_menu0);
$smarty->assign('paginator_menu2',$paginator_menu0);

 $csv_export_options0=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['code']),
                                                             'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['name']),
                                                             'orders'=>array('label'=>_('Orders'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['orders']),
                                                             'cancelled'=>array('label'=>_('Cancelled'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['cancelled']),
                                                             'suspended'=>array('label'=>_('Suspended'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['suspended']),
                                                   
                                                             'pending'=>array('label'=>_('Pending'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['pending']),
                                                             'dispatched'=>array('label'=>_('Dispatched'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['dispatched']),
                                                     
                                                         )
                                                     )
                                          ),
                            
                            'sales_all'=>array('title'=>_('Sales (All times)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_all'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['sales_all']),
                                                       'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['profit_all']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1y'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['sales_1y']),
                                                       'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['profit_1y']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1q'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['sales_1q']),
                                                       'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['profit_1q']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1m'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['sales_1m']),
                                                       'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['profit_1m']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
                            'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1w'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['sales_1w']),
                                                       'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['orders']['csv_export']['profit_1w']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols0',6);
$smarty->assign('csv_export_options0',$csv_export_options0);
// ----------------------------------ARRAY FOR INVOICE STARTS HERE -----------------------------------------------
 $csv_export_options1=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['code']),
                                                             'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['name']),
                                                             'invoices'=>array('label'=>_('Invoices'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['invoices']),
                                                       'invpaid'=>array('label'=>_('Inv Paid'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['invpaid']),
                                                       'invtopay'=>array('label'=>_('Inv To Pay'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['invtopay']),
                                                       'refunds'=>array('label'=>_('Refunds'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['refunds']),
                                                       'refpaid'=>array('label'=>_('Ref Paid'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['refpaid']),
                                                
                                                       'reftopay'=>array('label'=>_('Ref To Pay'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['reftopay']),
                                                     
                                                         )
                                                     )
                                          ),
                            
                            'sales_all'=>array('title'=>_('Sales (All times)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_all'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['sales_all']),
                                                       'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['profit_all']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1y'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['sales_1y']),
                                                       'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['profit_1y']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1q'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['sales_1q']),
                                                       'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['profit_1q']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1m'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['sales_1m']),
                                                       'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['profit_1m']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
                            'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1w'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['sales_1w']),
                                                       'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['invoices']['csv_export']['profit_1w']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols1',6);
$smarty->assign('csv_export_options1',$csv_export_options1);
// ----------------------------------ARRAY FOR INVOICE ENDS HERE -----------------------------------------------
// ----------------------------------ARRAY FOR DELIVERY_NOTES STARTS HERE -----------------------------------------------
 $csv_export_options2=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['code']),
                                                             'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['name']),
                                                             'total'=>array('label'=>_('Total'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['total']),
                                                       'topick'=>array('label'=>_('To Pick'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['topick']),
                                                       'picking'=>array('label'=>_('Picking'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['picking']),
                                                       'packing'=>array('label'=>_('Packing'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['packing']),
                                                       'ready'=>array('label'=>_('Ready'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['ready']),
                                                
                                                       'send'=>array('label'=>_('Send'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['send']),
							'returned'=>array('label'=>_('Returned'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['returned']),
                                                     
                                                         )
                                                     )
                                          ),
                            
                            'sales_all'=>array('title'=>_('Sales (All times)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_all'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['sales_all']),
                                                       'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['profit_all']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1y'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['sales_1y']),
                                                       'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['profit_1y']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1q'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['sales_1q']),
                                                       'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['profit_1q']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1m'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['sales_1m']),
                                                       'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['profit_1m']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
                            'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1w'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['sales_1w']),
                                                       'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['delivery_notes']['csv_export']['profit_1w']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols2',6);
$smarty->assign('csv_export_options2',$csv_export_options2);
// ----------------------------------ARRAY FOR DELIVERY_NOTES ENDS HERE -----------------------------------------------

$smarty->display('orders_server.tpl');
?>
