<?php
/*
 File: family.php

 UI family page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/


include_once('common.php');
include_once('class.Family.php');
include_once('class.Store.php');
include_once('class.Department.php');
include_once('assets_header_functions.php');

if (!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
    $family_id=$_SESSION['state']['family']['id'];
else
    $family_id=$_REQUEST['id'];
    
 $family=new Family($family_id);
 if(!$family->id){
   header('Location: stores.php');
    exit();
 
 }
 
    
$_SESSION['state']['family']['id']=$family_id;





//$tmp_page_data=$family->get_page_data();
//$page_data=array();
//foreach($tmp_page_data as $key=>$value) {
//    $page_data[preg_replace('/\s/','',$key)]=$value;
//}
//$smarty->assign('page_data',$page_data);



//print_r($page_data);

$_SESSION['state']['department']['id']=$family->data['Product Family Main Department Key'];
$_SESSION['state']['store']['id']=$family->data['Product Family Store Key'];



if (!( $user->can_view('stores') and in_array($family->data['Product Family Store Key'],$user->stores))) {
    header('Location: index.php');
    exit();
}

$store=new Store($family->data['Product Family Store Key']);
$department=new Department($family->get('Product Family Main Department Key'));

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product families');
$modify=$user->can_edit('stores');

if (isset($_REQUEST['edit'])) {
    header('Location: edit_department.php?id='.$department_id);
    exit();

}




$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

get_header_info($user,$smarty);


$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');

$block_view=$_SESSION['state']['family']['block_view'];
$smarty->assign('block_view',$block_view);







$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
               	$yui_path.'calendar/assets/skins/sam/calendar.css',

               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
			   'css/edit.css',
			   'css/calendar.css',
			   'theme.css.php'
           );
		   

$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'uploader/uploader.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-debug.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              
              'js/php.default.min.js',
              'js/common.js',
              'js/table_common.js',
              'js/edit_common.js',
              'js/csv_common.js',
              'js/dropdown.js',
              'js/assets_common.js',
 'js/calendar_interval.js',
          );



$js_files[]='js/search.js';
$js_files[]='family.js.php';



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);






//$_SESSION['state']['assets']['page']='department';
if (isset($_REQUEST['view'])) {
    $valid_views=array('sales','general','stoke');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state']['families']['products']['view']=$_REQUEST['view'];

}

$department_order=$_SESSION['state']['department']['products']['order'];
$department_period=$_SESSION['state']['department']['products']['period'];
$department_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));


$smarty->assign('department_period',$department_period);
$smarty->assign('department_period_title',$department_period_title[$department_period]);







$info_period_menu=array(
                      array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
                      ,array("period"=>'month','label'=>_('Last Month'),'title'=>_('Last Month'))
                      ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
                      ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
                      ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
                  );
$smarty->assign('info_period_menu',$info_period_menu);




$smarty->assign('parent','products');
$smarty->assign('title',$family->get('Product Family Code').' - '.$family->get('Product Family Name'));


$product_home="Products Home";
$smarty->assign('home',$product_home);




$smarty->assign('family',$family);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

$smarty->assign('department',$department);



$smarty->assign('product_view',$_SESSION['state']['family']['products']['view']);
$smarty->assign('product_period',$_SESSION['state']['family']['products']['period']);
$smarty->assign('product_avg',$_SESSION['state']['family']['products']['avg']);

$tipo_filter=$_SESSION['state']['family']['products']['f_field'];
$smarty->assign('filter_name0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['family']['products']['f_value']);
$filter_menu=array(
        'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
       'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))
             );
             
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$tipo_filter1=$_SESSION['state']['family']['product_sales']['f_field'];
$smarty->assign('filter_name1',$tipo_filter1);
$smarty->assign('filter_value1',$_SESSION['state']['family']['product_sales']['f_value']);
$filter_menu=array(
        'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
       'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))
             );
             
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter1]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['family']['pages']['f_field'];
$smarty->assign('filter4',$tipo_filter);
$smarty->assign('filter_value4',$_SESSION['state']['family']['pages']['f_value']);
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Page code starting with  <i>x</i>','label'=>'Code'),
                 'title'=>array('db_key'=>'code','menu_label'=>'Page title like  <i>x</i>','label'=>'Code'),

             );
$smarty->assign('filter_menu4',$filter_menu);
$smarty->assign('filter_name4',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);


$table_title=_('List');
$smarty->assign('table_title',$table_title);

$info_period_menu=array(
                      array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
                      ,array("period"=>'month','label'=>_('last Month'),'title'=>_('last Month'))
                      ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
                      ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
                      ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
                  );
$smarty->assign('info_period_menu',$info_period_menu);



$smarty->assign('title',_('Family').': '.$family->get('Product Family Name'));
// -----------------------------------------------export csv code starts here------------------------
$csv_export_options=array(
                        'description'=>array(
                                          'title'=>_('Description'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['code']),
                                                         'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['name']),

                                                         'status'=>array('label'=>_('Status'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['status']),
                                                         'web'=>array('label'=>_('Web'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['web']),


                                                     )
                                                 )
                                      ),

                        'sales_all'=>array('title'=>_('Sales (All times)'),
                                           'rows'=>
                                                  array(
                                                      array(
                                                          'sales_all'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['sales_all']),
                                                          'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['profit_all']),
                                                          array('label'=>''),
                                                          array('label'=>''),
                                                      )
                                                  )
                                          ),
                        'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'sales_1y'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['sales_1y']),
                                                         'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['profit_1y']),
                                                         array('label'=>''),
                                                         array('label'=>''),
                                                     )
                                                 )
                                         ),
                        'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'sales_1q'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['sales_1q']),
                                                         'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['profit_1q']),
                                                         array('label'=>''),
                                                         array('label'=>''),
                                                     )
                                                 )
                                         ),
                        'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'sales_1m'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['sales_1m']),
                                                         'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['profit_1m']),
                                                         array('label'=>''),
                                                         array('label'=>''),
                                                     )
                                                 )
                                         ),
                        'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'sales_1w'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['sales_1w']),
                                                         'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['profit_1w']),
                                                         array('label'=>''),
                                                         array('label'=>''),
                                                     )
                                                 )
                                         )
                    );
$smarty->assign('export_csv_table_cols',6);
$smarty->assign('csv_export_options',$csv_export_options);
// -----------------------------------------------export csv code ends here------------------------



$elements_number=array('Historic'=>0,'Discontinued'=>0,'NoSale'=>0,'Sale'=>0,'Private'=>0);
$sql=sprintf("select count(*) as num,`Product Main Type` from  `Product Dimension` where `Product Family Key`=%d group by `Product Main Type`",$family->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $elements_number[$row['Product Main Type']]=$row['num'];
}
$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['family']['products']['elements']);

$mode_options=array(
	array('mode'=>'percentage','label'=>_('Percentages')),
	array('mode'=>'value','label'=>_('Sales Amount')),
);
if ($_SESSION['state']['family']['products']['percentages']) {
	$display_mode='percentages';
	$display_mode_label=_('Percentages');
}else {
	$display_mode='value';
	$display_mode_label=_('Sales Amount');
}
$smarty->assign('display_products_mode',$display_mode);
$smarty->assign('display_products_mode_label',$display_mode_label);
$smarty->assign('products_mode_options_menu',$mode_options);
$smarty->assign('products_table_type',$_SESSION['state']['family']['products']['table_type']);


$table_type_options=array(
	'list'=>array('mode'=>'list','label'=>_('List')),
	'thumbnails'=>array('mode'=>'thumbnails','label'=>_('Thumbnails')),
);
$smarty->assign('products_table_type',$_SESSION['state']['family']['products']['table_type']);
$smarty->assign('products_table_type_label',$table_type_options[$_SESSION['state']['family']['products']['table_type']]['label']);
$smarty->assign('products_table_type_menu',$table_type_options);

$order=$_SESSION['state']['department']['families']['order'];
if ($order=='code') {
    $order='`Product Family Code`';
    $order_label=_('Code');
} else {
     $order='`Product Family Code`';
    $order_label=_('Code');
}
$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Product Family Key` as id , `Product Family Code` as name from `Product Family Dimension`  where  `Product Family Main Department Key`=%d  and %s < %s  order by %s desc  limit 1",
             $family->data['Product Family Main Department Key'],
             $order,
             prepare_mysql($family->get($_order)),
             $order
            );

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $prev['link']='family.php?id='.$row['id'];
    $prev['title']=$row['name'];
    $smarty->assign('prev',$prev);
}
mysql_free_result($result);


$sql=sprintf("select`Product Family Key` as id , `Product Family Code` as name from `Product Family Dimension`  where  `Product Family Main Department Key`=%d   and  %s>%s  order by %s   ",
  $family->data['Product Family Main Department Key'],
             $order,
             prepare_mysql($family->get($_order)),
             $order
            );

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $next['link']='family.php?id='.$row['id'];
    $next['title']=$row['name'];
    $smarty->assign('next',$next);
}
mysql_free_result($result);



include_once('conf/period_tags.php');
unset($period_tags['hour']);
$smarty->assign('period_tags',$period_tags);

$family_order=$_SESSION['state']['family']['products']['order'];
$family_period=$_SESSION['state']['family']['products']['period'];
$smarty->assign('products_period',$family_period);


list($db_interval,$from_date,$to_date,$from_date_1yb,$to_1yb)=calculate_inteval_dates($family_period);
$to_little_edian=($to_date?date("d-m-Y",strtotime($to_date)):'');
$from_little_edian=($from_date?date("d-m-Y",strtotime($from_date)):'');

$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('sales_sub_block_tipo',$_SESSION['state']['family']['sales_sub_block_tipo']);




//print $family_period;
//print_r($period_tags);
$smarty->display('family.tpl');


?>
