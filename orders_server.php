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
if (!$user->can_view('orders'))
    exit();








$smarty->assign('box_layout','yui-t0');

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
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
              'js/php.default.min.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/dropdown.js',
              'js/edit_common.js',
              'orders_server.js.php'
          );


$q='';

$general_options_list=array();
$smarty->assign('general_options_list',$general_options_list);

$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');


if (isset($_REQUEST['view']) and preg_match('/^orders|invoices|dn$/',$_REQUEST['view'])) {
    $_SESSION['state']['stores']['orders_view']=$_REQUEST['view'];
}

$smarty->assign('block_view',$_SESSION['state']['stores']['orders_view']);
$smarty->assign('dn_view',$_SESSION['state']['stores']['delivery_notes']['view']);


$smarty->assign('store_id','');


$smarty->assign('parent','orders');
$smarty->assign('title', _('Orders').' ('._('All Stores').')');

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$tipo_filter0=$_SESSION['state']['stores']['orders']['f_field'];
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0',$_SESSION['state']['stores']['orders']['f_value']);
$filter_menu0=array(
                  'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Order number starting with  <i>x</i>'),'label'=>_('Order Number')),

              );
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);


$tipo_filter1=$_SESSION['state']['stores']['invoices']['f_field'];
$smarty->assign('filter1',$tipo_filter1);
$smarty->assign('filter_value1',$_SESSION['state']['stores']['invoices']['f_value']);
$filter_menu1=array(
                  'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Invoice number starting with  <i>x</i>'),'label'=>_('Invoice Number')),

              );
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter_name1',$filter_menu1[$tipo_filter1]['label']);


$tipo_filter2=$_SESSION['state']['stores']['delivery_notes']['f_field'];
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2',$_SESSION['state']['stores']['delivery_notes']['f_value']);
$filter_menu2=array(
                  'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Delivery Note starting with  <i>x</i>'),'label'=>_('Delivery Note Number')),

              );
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);

$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);



$smarty->assign('paginator_menu1',$paginator_menu0);
$smarty->assign('paginator_menu2',$paginator_menu0);

$smarty->assign('store_id','');


$smarty->display('orders_server.tpl');
?>
