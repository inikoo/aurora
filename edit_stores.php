<?php
/*
 File: stores.php

 UI stores page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('common.php');

include_once('class.Account.php');

//include_once('stock_functions.php');
if (!$user->can_view('stores'))
    exit();


$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('account');
$modify=$user->can_edit('account');
if (!$modify) {
    header('Location: stores.php');
    exit;
}





$smarty->assign('create',$create);
$smarty->assign('modify',$modify);


$corporation=new Account();
$smarty->assign('corporation',$corporation);




$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
               'css/edit.css',

               'theme.css.php'
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
              'js/search.js',
              'js/edit_common.js',
              'js/csv_common.js'

          );


$smarty->assign('block_view',$_SESSION['state']['stores']['edit_block_view']);

$js_files[]='country_select.js.php';
$js_files[]='edit_stores.js.php';

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$_SESSION['state']['assets']['page']='stores';


$smarty->assign('parent','products');
$smarty->assign('title', _('Stores'));





global $myconf;
$stores=array();
$sql=sprintf("select count(distinct `Store Currency Code` ) as distint_currencies, sum(IF(`Store Currency Code`=%s,1,0)) as default_currency    from `Store Dimension` "
             ,prepare_mysql($corporate_currency));

$res=mysql_query($sql);
if ($row=mysql_fetch_array($res)) {
    $distinct_currencies=$row['distint_currencies'];
    $default_currency=$row['default_currency'];
}

$mode_options=array(
                  array('mode'=>'percentage','label'=>_('Percentages')),
                  array('mode'=>'value','label'=>_('Values')),

              );




$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');
$smarty->assign('store_key','');


$q='';
$tipo_filter=($q==''?$_SESSION['state']['stores']['stores']['f_field']:'code');
//$smarty->assign('filter_show0',$_SESSION['state']['stores']['table']['f_show']);
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',($q==''?$_SESSION['state']['stores']['stores']['f_value']:addslashes($q)));
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>_('Store Code'),'label'=>_('Code')),
                 'name'=>array('db_key'=>'name','menu_label'=>_('Store Name'),'label'=>_('Name')),
             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('mode_options_menu',$mode_options);


$smarty->display('edit_stores.tpl');




?>
