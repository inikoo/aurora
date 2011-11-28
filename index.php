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


if ($user->data['User Type']=='Supplier') {
    header('Location: suppliers_index.php');
    exit;
}


include_once('class.Product.php');
include_once('class.Order.php');

$general_options_list=array();
//$general_options_list[]=array('tipo'=>'js','state'=>'','id'=>'edit_widgets','label'=>_('Customize Page'));
$smarty->assign('general_options_list',$general_options_list);

if ($user->data['User Type']=='Supplier') {
    $num_suppliers=count($user->suppliers);

    if ($num_suppliers==1) {
        header('Location: supplier.php?id='.$user->suppliers[0]);
        exit;

    }
}



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
               'container.css',
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





$blocks=array();
$sql=sprintf("select * from `Dashboard User Bridge` where `User Key`=%d order by `Dashboard Order`",
$user->id
);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
    $blocks[]=array('key'=>$row['Dashboard Key'],'src'=>$row['Dashboard URL'],'class'=>$row['Dashboard Class'],'metadata'=>$row['Dashboard Metadata']);
}
$smarty->assign('blocks',$blocks);

//print_r($blocks);


$splinters_data=array(
                    'messages'=>array(
                                   'title'=>_('Display Board'),
                                   'index'=>200,
                                   'php'=>'splinter_messages.php',
                                   'tpl'=>'splinter_messages.tpl',
                                   'js'=>'splinter_messages.js.php'
                               ),
                    'top_customers'=>array(
                                        'title'=>_('Top Customers'),
                                        'index'=>201,
                                        'php'=>'splinter_top_customers.php',
                                        'tpl'=>'splinter_top_customers.tpl',
                                        'js'=>'js/splinter_top_customers.js',
                                        'order'=>$_SESSION['state']['home']['splinters']['top_customers']['order'],
                                        'nr'=>$_SESSION['state']['home']['splinters']['top_customers']['nr'],

                                    ),


                    'sales'=>array(
                                'title'=>_('Sales Overview'),
                                'index'=>203,
                                'php'=>'splinter_sales.php',
                                'tpl'=>'splinter_sales.tpl',
                                'js'=>'js/splinter_sales.js',
                                'order'=>$_SESSION['state']['home']['splinters']['top_products']['order'],
                                'nr'=>$_SESSION['state']['home']['splinters']['top_customers']['nr'],

                            ),





                    'top_products'=>array(
                                       'title'=>_('Top Products'),
                                       'index'=>202,
                                       'php'=>'splinter_top_products.php',
                                       'tpl'=>'splinter_top_products.tpl',
                                       'js'=>'js/splinter_top_products.js',
                                       'order'=>$_SESSION['state']['home']['splinters']['top_products']['order'],
                                       'nr'=>$_SESSION['state']['home']['splinters']['top_products']['nr'],
                                       'type'=> $_SESSION['state']['home']['splinters']['top_products']['type']

                                   ),

                );

$splinters=array();
foreach($myconf['splinters'] as $splinter_name) {
    if (array_key_exists($splinter_name,$splinters_data))
        $splinters[$splinter_name]=$splinters_data[$splinter_name];
}
//exit;
//print_r($splinters)     ;

foreach($splinters as $splinter_name=>$splinter) {

    if (array_key_exists('order',$splinter))
        $smarty->assign($splinter_name.'_order',$splinter['order']);
    if (array_key_exists('nr',$splinter))
        $smarty->assign($splinter_name.'_nr',$splinter['nr']);



    $smarty->assign($splinter_name.'_index',$splinter['index']);


    if ($splinter['js']!='')
        $js_files[]=$splinter['js'];
    include_once($splinter['php']);
}


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

//print_r($_SESSION['state']['home']['splinters']);
$smarty->assign('conf_data',$_SESSION['state']['home']['splinters']);
$smarty->assign('display_block',$_SESSION['state']['home']['display']);


$smarty->assign('search_scope','all');

$smarty->assign('search_label',_('Search'));

$smarty->assign('splinters',$splinters);
$smarty->assign('parent','home');
$smarty->assign('title', _('Home'));
$smarty->assign('test','hola');


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('index.tpl');
?>

