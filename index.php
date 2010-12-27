<?php
/*
 File: index.php

 UI index page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

 Version 2.0
*/

include_once('common.php');


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


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               'common.css',
               'button.css',
               'container.css',
               'table.css',
               'css/index.css'
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
              'common.js.php',
              'table_common.js.php',
              'js/search.js',
              'js/index_tools.js',
              'index.js.php',

              'js/index_sliding_tabs.js.php?slide='.$_SESSION['state']['home']['display'],
          );


$splinters=array(

'messages'=>array(
                                       'title'=>_('Display Board'),
                                       'index'=>0,
                                       'php'=>'splinter_messages.php',
                                       'tpl'=>'splinter_messages.tpl',
                                       'js'=>'splinter_messages.js.php'
                                   ),
               'orders_in_process'=>array(
                                       'title'=>_('Pending orders'),
                                       'index'=>1,
                                       'php'=>'splinter_orders_in_process.php',
                                       'tpl'=>'splinter_orders_in_process.tpl',
                                       'js'=>'splinter_orders_in_process.js.php'
                                   ),


               'top_products'=>array(
                                    'title'=>_('Top Products'),
                                  'index'=>3,
                                  'php'=>'splinter_top_products.php',
                                  'tpl'=>'splinter_top_products.tpl',
                                  'js'=>'splinter_top_products.js.php'
                              ),
               'top_customers'=>array(
                  		'title'=>_('Top Customers'),
                                   'index'=>2,
                                   'php'=>'splinter_top_customers.php',
                                   'tpl'=>'splinter_top_customers.tpl',
                                   'js'=>'splinter_top_customers.js.php'
                               )
           );

foreach($splinters as $splinter) {
    if ($splinter['js']!='')
        $js_files[]=$splinter['js']."?table_id=".$splinter['index'];
    include_once($splinter['php']);
}


//print_r($_SESSION['state']['home']['splinters']);
$smarty->assign('conf_data',$_SESSION['state']['home']['splinters']);
$smarty->assign('display_block',$_SESSION['state']['home']['display']);

$smarty->assign('search_scope','all');

$smarty->assign('search_label',_('Search'));

$smarty->assign('splinters',$splinters);
$smarty->assign('parent','home');
$smarty->assign('title', _('Home'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('index.tpl');
?>

