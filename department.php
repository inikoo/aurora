<?php
/*
 File: department.php 

 UI department page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');
include_once('class.Department.php');
include_once('assets_header_functions.php');




if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']) )
  $department_id=$_SESSION['state']['department']['id'];
 else{
   $department_id=$_REQUEST['id'];
  
  }
$department=new Department($department_id);

 $_SESSION['state']['department']['id']=$department->id;







if(!( $user->can_view('stores') and in_array($department->data['Product Department Store Key'],$user->stores))){
header('Location: index.php');
  exit();
}
$store=new Store($department->get('Product Department Store Key'));


$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product families');
$modify=$user->can_edit('stores');



if(isset($_REQUEST['edit'])){
header('Location: edit_department.php?id='.$department_id);
  exit();

}


$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

get_header_info($user,$smarty);

$smarty->assign('table_type',$_SESSION['state']['department']['table_type']);


//$smarty->assign('restrictions',$_SESSION['state']['department']['restrictions']);



$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');
$block_view=$_SESSION['state']['department']['block_view'];
$smarty->assign('block_view',$block_view);


$general_options_list=array();


if($modify)
  $general_options_list[]=array('tipo'=>'url','url'=>'department.php?edit=1','label'=>_('Edit Department'));

  
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
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'history/history-min.js',
		$yui_path.'datatable/datatable-debug.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/php.default.min.js',
		'common.js.php',
		'table_common.js.php',
		'js/edit_common.js',
                'js/csv_common.js',
		 'js/dropdown.js',
		  'js/assets_common.js'

		);

$department->load_images_slidesshow();
$images=$department->images_slideshow;
$smarty->assign('images',$images);


     $js_files[]='js/search.js';
     $js_files[]='department.js.php';



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


if(isset($_REQUEST['view'])){
  $valid_views=array('sales','general','stoke');
  if (in_array($_REQUEST['view'], $valid_views)) 
    $_SESSION['state']['department']['view']=$_REQUEST['view'];

 }




$store_order=$_SESSION['state']['store']['departments']['order'];
$store_period=$_SESSION['state']['store']['departments']['period'];
$store_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
  

$smarty->assign('store_period',$store_period);
$smarty->assign('store_period_title',$store_period_title[$store_period]);

 $store_order='`Product Department Code`';
    if ($store_order=='families')
        $store_order='`Product Department Families`';
    if ($store_order=='todo')
        $store_order='`Product Department In Process Products`';
    if ($store_order=='profit') {
        if ($store_period=='all')
            $store_order='`Product Department Total Profit`';
        elseif($store_period=='year')
        $store_order='`Product Department 1 Year Acc Profit`';
        elseif($store_period=='quarter')
        $store_order='`Product Department 1 Quarter Acc Profit`';
        elseif($store_period=='month')
        $store_order='`Product Department 1 Month Acc Profit`';
        elseif($store_period=='week')
        $store_order='`Product Department 1 Week Acc Profit`';
    }
    elseif($store_order=='sales') {
        if ($store_period=='all')
            $store_order='`Product Department Total Invoiced Amount`';
        elseif($store_period=='year')
        $store_order='`Product Department 1 Year Acc Invoiced Amount`';
        elseif($store_period=='quarter')
        $store_order='`Product Department 1 Quarter Acc Invoiced Amount`';
        elseif($store_period=='month')
        $store_order='`Product Department 1 Month Acc Invoiced Amount`';
        elseif($store_period=='week')
        $store_order='`Product Department 1 Week Acc Invoiced Amount`';

    }
    elseif($store_order=='name')
    $store_order='`Product Department Name`';
    elseif($store_order=='code')
    $store_order='`Product Department Code`';
    elseif($store_order=='active')
    $store_order='`Product Department For Sale Products`';
    elseif($store_order=='outofstock')
    $store_order='`Product Department Out Of Stock Products`';
    elseif($store_order=='stock_error')
    $store_order='`Product Department Unknown Stock Products`';
    elseif($store_order=='surplus')
    $store_order='`Product Department Surplus Availability Products`';
    elseif($store_order=='optimal')
    $store_order='`Product Department Optimal Availability Products`';
    elseif($store_order=='low')
    $store_order='`Product Department Low Availability Products`';
    elseif($store_order=='critical')
    $store_order='`Product Department Critical Availability Products`';

  


$sql=sprintf("select `Product Department Key` as id,`Product Department Code` as code  from `Product Department Dimension` where `Product Department Store Key`=%d and   %s<%s order by %s desc  ",$department->data['Product Department Store Key'],$store_order,prepare_mysql($department->get(str_replace('`','',$store_order))),$store_order);
//print $sql;
$result=mysql_query($sql);
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC)   )
  $prev=array('id'=>0,'code'=>'');
mysql_free_result($result);  
  
$sql=sprintf("select `Product Department Key` as id,`Product Department Code` as code  from `Product Department Dimension`   where  `Product Department Store Key`=%d and   %s>%s order by %s   ",$department->data['Product Department Store Key'],$store_order,prepare_mysql($department->get(str_replace('`','',$store_order))),$store_order);

//print $sql;
$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC)   )
  $next=array('id'=>0,'code'=>'');
mysql_free_result($result);  
$smarty->assign('prev',$prev);
$smarty->assign('next',$next);



$smarty->assign('parent','products');

$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$department);
$smarty->assign('store',$store);





$smarty->assign('family_view',$_SESSION['state']['store']['families']['view']);
$smarty->assign('family_show_percentages',$_SESSION['state']['store']['families']['percentages']);
$smarty->assign('family_avg',$_SESSION['state']['store']['families']['avg']);
$smarty->assign('family_period',$_SESSION['state']['store']['families']['period']);
$q='';
$tipo_filter=($q==''?$_SESSION['state']['store']['families']['f_field']:'code');
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',($q==''?$_SESSION['state']['store']['families']['f_value']:addslashes($q)));
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code')
             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('families',$department->data['Product Department Families']);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->assign('product_view',$_SESSION['state']['store']['products']['view']);
$smarty->assign('product_show_percentages',$_SESSION['state']['store']['products']['percentages']);
$smarty->assign('product_avg',$_SESSION['state']['store']['products']['avg']);
$smarty->assign('product_period',$_SESSION['state']['store']['products']['period']);
$q='';
$tipo_filter=($q==''?$_SESSION['state']['store']['products']['f_field']:'code');
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',($q==''?$_SESSION['state']['store']['products']['f_value']:addslashes($q)));
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Product starting with  <i>x</i>','label'=>'Code')
             );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('products',$department->data['Product Department For Public Sale Products']);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);





$info_period_menu=array(
			array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
		     ,array("period"=>'month','label'=>_('last Month'),'title'=>_('last Month'))
		     ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
		     ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
		     ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
		     );
$smarty->assign('info_period_menu',$info_period_menu);





 $q='';
  $tipo_filter=($q==''?$_SESSION['state']['department']['families']['f_field']:'code');
  $smarty->assign('filter_name0',$tipo_filter);
  $smarty->assign('filter_value0',($q==''?$_SESSION['state']['department']['families']['f_value']:addslashes($q)));
  $filter_menu=array(
		     'code'=>array('db_key'=>'code','menu_label'=>'Department code starting with','label'=>'Code')
		     ,'name'=>array('db_key'=>'code','menu_label'=>'Department name containing ','label'=>'Code')
		     ,'description'=>array('db_key'=>'code','menu_label'=>'Department descrioption containing','label'=>'Code')

		     );
  $smarty->assign('filter_menu0',$filter_menu);


  $smarty->assign('title',$department->get('Product Department Name'));
// --------------------------------families' Export(csv) right click code----------------
 $csv_export_options=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['code']),
                                                             'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['name']),
                                                             'stores'=>array('label'=>_('Stores'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['stores']),
                                                             
                                                             'products'=>array('label'=>_('Products'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['products']),
                                                   
                                                            
                                                     
                                                         )
                                                     )
                                          ),
                            'stock'=>array(
                                        'title'=>_('Stock'),
                                        'rows'=>
                                               array(
                                                   array(
                                                       'surplus'=>array('label'=>_('Surplus'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['surplus']),
                                                       'ok'=>array('label'=>_('Ok'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['ok']),
                                                       'low'=>array('label'=>_('Low'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['low']),
                                                       'critical'=>array('label'=>_('Critical'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['critical']),
                                                       'gone'=>array('label'=>_('Gone'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['gone']),
                                                
                                                       'unknown'=>array('label'=>_('Unknown'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['unknown']),
                                                             array('label'=>''),
                                                       

                                                   )
                                               )
                                    ),
                            'sales_all'=>array('title'=>_('Sales (All times)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_all'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_all']),
                                                       'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_all']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1y'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1y']),
                                                       'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1y']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1q'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1q']),
                                                       'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1q']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1m'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1m']),
                                                       'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1m']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
                            'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1w'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1w']),
                                                       'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1w']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols',7);

                        
$smarty->assign('csv_export_options',$csv_export_options);

//{include file='export_csv_menu_splinter.tpl' id=0  export_options=$csv_export_options }

// -----------------------------------------------export csv code ends here------------------------

  $smarty->display('department.tpl');
 


?>
