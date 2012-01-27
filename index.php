<?php
/*
 File: index.php

 UI index page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/



include_once('common.php');





include_once('class.Product.php');
include_once('class.Order.php');

//$general_options_list=array();
//$general_options_list[]=array('tipo'=>'js','state'=>'','id'=>'edit_widgets','label'=>_('Customize Page'));
//$smarty->assign('general_options_list',$general_options_list);



$smarty->assign('store_keys',join(',',$user->stores));


$search_options_list=array();

//$search_options_list[]=array('tipo'=>'url','url'=>'search_customers.php','label'=>_('Search Customers'));
//$search_options_list[]=array('tipo'=>'url','url'=>'customers_stats.php','label'=>_('Products'));

//$smarty->assign('search_options_list',$search_options_list);





$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
               'css/index.css',
               'theme.css.php'

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
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'external_libs/ampie/ampie/swfobject.js',

              //      'js/index_tools.js',
              'js/index.js',

              //    'js/index_sliding_tabs.js.php?slide='.$_SESSION['state']['home']['display'],
          );

/*
'sales'=>array(
                                     'title'=>_('Sales Overview'),
                                     'index'=>0,
                                     'php'=>'splinter_sales.php',
                                     'tpl'=>'splinter_sales.tpl',
                                     'js'=>'js/splinter_sales.js'
                                 ),

'store_sales'=>array(
                                     'title'=>_('Store Sales'),
                                     'index'=>0,
                                     'php'=>'splinter_store_sales.php',
                                     'tpl'=>'splinter_store_sales.tpl',
                                     'js'=>'js/splinter_store_sales.js'
                                 ),


             'orders_in_process'=>array(
                                     'title'=>_('Pending orders'),
                                     'index'=>1,
                                     'php'=>'splinter_orders_in_process.php',
                                     'tpl'=>'splinter_orders_in_process.tpl',
                                     'js'=>'splinter_orders_in_process.js.php'
                                 ),




*/


$smarty->assign('dashboard_key',$user->data['User Dashboard Key']);


if(isset($_REQUEST['dashboard_id']) and is_numeric($_REQUEST['dashboard_id'])){
	$dashboard_key=$_REQUEST['dashboard_id'];
}
else{
	$dashboard_key=$user->data['User Dashboard Key'];
}




$blocks=array();
$sql=sprintf("select * from  `Dashboard Widget Bridge`B  left join `Widget Dimension` W on (B.`Widget Key`=W.`Widget Key`)   where `Dashboard Key`=%d  order by `Dashboard Widget Order`",
$dashboard_key
);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
$dashboard_key=$row['Dashboard Key'];
    $blocks[]=array('key'=>$row['Dashboard Widget Key'],'src'=>$row['Widget URL'],'class'=>$row['Widget Block'],'metadata'=>$row['Dashboard Widget Metadata'],'height'=>$row['Dashboard Widget Height']);
}
$smarty->assign('blocks',$blocks);

//print_r($blocks);

$sql=sprintf("select *	from `Dashboard Dimension` where `User Key`=%d", $user->id);

$result=mysql_query($sql);
$number_of_dashboards=mysql_num_rows($result);
$smarty->assign('number_of_dashboards',$number_of_dashboards);

/*
$sql=sprintf("select * from `Dashboard Dimension` where `Dashboard key`=%d", $dashboard_key);
$result=mysql_query($sql);
$current_dashboard_data=mysql_fetch_assoc($result);








$sql=sprintf("select * from `Dashboard Dimension` where `User key`=%d order by `Dashboard Order` DESC", $user->id);
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);
$last_dashboard_id=$row['Dashboard Order'];
*/
$next_id=0;
$prev_id=0;
$dashboard_data=array();
$dashboard_data2=array();
$sql=sprintf("select * from `Dashboard Dimension` where `User key`=%d order by `Dashboard Order`", $user->id);
$result=mysql_query($sql);
while($row=mysql_fetch_assoc($result)){
	$dashboard_data[$row['Dashboard Order']]=$row['Dashboard Key'];
$dashboard_data2[$row['Dashboard Key']]=$row['Dashboard Order'];
}

if(count($dashboard_data)>1){


if($dashboard_data2[$dashboard_key] == $number_of_dashboards ){
	$next_id=$dashboard_data[1];
}
else{
$next_id=$dashboard_data[$dashboard_data2[$dashboard_key]+1];

}

if($dashboard_data2[$dashboard_key] == 1 ){
	$prev_id=$dashboard_data[$number_of_dashboards];
}
else{
$prev_id=$dashboard_data[$dashboard_data2[$dashboard_key]-1];

}

}

$prev=array('id'=> $prev_id, 'name'=>'');
$next=array('id'=> $next_id, 'name'=>'');
$smarty->assign('prev',$prev);
$smarty->assign('next',$next);

$valid_sales=true;
//$sql = "select count(*) from `Invoice Dimension`";
//$result = mysql_query($sql);
//if (!$row=mysql_fetch_array($result))
//    $valid_sales=false;

$smarty->assign('valid_sales',$valid_sales);

$valid_customers=true;
//$sql = "select * from `Product Dimension`";
//$result = mysql_query($sql);
//if (!$row=mysql_fetch_array($result))
//    $valid_customers=false;

$smarty->assign('valid_customers',$valid_customers);

$valid_products=true;
//$sql = "select * from `Customer Dimension`";
//$result = mysql_query($sql);
//if (!$row=mysql_fetch_array($result))
//    $valid_products=false;

$smarty->assign('valid_products',$valid_products);
//print_r($_SESSION['state']['orders']['invoices']);


$smarty->assign('search_scope','all');

$smarty->assign('search_label',_('Search'));

$smarty->assign('parent','home');
$smarty->assign('title', _('Home'));
$smarty->assign('test','hola');


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('index.tpl');
?>

