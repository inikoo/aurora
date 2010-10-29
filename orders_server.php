<?php
/*
 File: orders_server.php 

 Orders server when ther is more that one store

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
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
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
				'js/php.default.min.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
                'js/dropdown.js',
                'js/edit_common.js','js/csv_common.js',
		'orders_server.js.php'
		);


$q='';

$general_options_list=array();



$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');


if(isset($_REQUEST['view']) and preg_match('/^orders|invoices|dn$/',$_REQUEST['view'])){
$_SESSION['state']['stores']['orders_view']=$_REQUEST['view'];
}

$smarty->assign('view',$_SESSION['state']['stores']['orders_view']);
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

 $csv_export_options=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['code']),
                                                             'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['name']),
                                                             'orders'=>array('label'=>_('Orders'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['orders']),
                                                             'cancelled'=>array('label'=>_('Cancelled'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['cancelled']),
                                                             'suspended'=>array('label'=>_('Suspended'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['suspended']),
                                                   
                                                             'pending'=>array('label'=>_('Pending'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['pending']),
                                                             'dispatched'=>array('label'=>_('Dispatched'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['dispatched']),
                                                     
                                                         )
                                                     )
                                          ),
                            
                            'sales_all'=>array('title'=>_('Sales (All times)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_all'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['sales_all']),
                                                       'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['profit_all']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1y'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['sales_1y']),
                                                       'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['profit_1y']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1q'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['sales_1q']),
                                                       'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['profit_1q']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1m'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['sales_1m']),
                                                       'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['profit_1m']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
                            'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1w'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['sales_1w']),
                                                       'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['profit_1w']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols',6);
$smarty->assign('csv_export_options',$csv_export_options);

$smarty->display('orders_server.tpl');
?>
