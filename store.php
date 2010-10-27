<?php
/*
 File: store.php

 UI store page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');
include_once('assets_header_functions.php');

$page='store';
$smarty->assign('page',$page);
if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $store_id=$_REQUEST['id'];

} else {
    $store_id=$_SESSION['state'][$page]['id'];
}

if (isset($_REQUEST['edit'])) {
    header('Location: edit_store.php?id='.$store_id);

    exit("E2");
}


if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
    header('Location: index.php');
    exit;
}



$store=new Store($store_id);
$_SESSION['state'][$page]['id']=$store->id;

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product departments');

$modify=$user->can_edit('stores');

$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);


$stores_order=$_SESSION['state']['stores']['table']['order'];
$stores_period=$_SESSION['state']['stores']['period'];
$stores_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));

$smarty->assign('stores_period',$stores_period);
$smarty->assign('stores_period_title',$stores_period_title[$stores_period]);

$show_details=$_SESSION['state'][$page]['details'];
$smarty->assign('show_details',$show_details);
get_header_info($user,$smarty);




$general_options_list=array();

if ($modify)
    $general_options_list[]=array('tipo'=>'url','url'=>'store.php?edit=1','label'=>_('Edit Store'));
$general_options_list[]=array('tipo'=>'js','state'=>$show_details,'id'=>'details','label'=>($show_details?_('Hide Details'):_('Show Details')));


$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');

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
              $yui_path.'dragdrop/dragdrop-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              'js/php.default.min.js',
              'common.js.php',
              'table_common.js.php',

              'js/dropdown.js'
          );


$js_files[]='js/search.js';
$js_files[]='common_plot.js.php?page='.$page;

$js_files[]='store.js.php';



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$_SESSION['state']['assets']['page']=$page;
if (isset($_REQUEST['view'])) {
    $valid_views=array('sales','general','stoke');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state'][$page]['view']=$_REQUEST['view'];

}
$smarty->assign('view',$_SESSION['state'][$page]['view']);


$smarty->assign('show_percentages',$_SESSION['state'][$page]['percentages']);
$smarty->assign('avg',$_SESSION['state'][$page]['avg']);
$smarty->assign('period',$_SESSION['state'][$page]['period']);
$info_period_menu=array(
                      array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
                      ,array("period"=>'month','label'=>_('Last Month'),'title'=>_('Last Month'))
                      ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
                      ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
                      ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
                  );
$smarty->assign('info_period_menu',$info_period_menu);


$subject_id=$store_id;
include_once('plot.inc.php');

$smarty->assign($page,$store);

$smarty->assign('parent','products');
$smarty->assign('title', $store->data['Store Name']);

$q='';
$tipo_filter=($q==''?$_SESSION['state'][$page]['table']['f_field']:'code');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state'][$page]['table']['f_value']:addslashes($q)));
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Store starting with  <i>x</i>','label'=>'Code')
             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('departments',$store->data['Store Departments']);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->display('store.tpl');

?>