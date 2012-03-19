<?php
/*
 File: store.php

 UI store page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

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


if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
    header('Location: index.php');
    exit;
}
if (!$user->can_edit('stores') ) {
    header('Location: store.php?error=cannot_edit');
    exit;
}


$store=new Store($store_id);
$_SESSION['state'][$page]['id']=$store->id;

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product departments');



$smarty->assign('pages_view',$_SESSION['state']['store']['edit_pages']['view']);


$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);



get_header_info($user,$smarty);

$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'store.php?id='.$store_id,'label'=>_('Exit Edit'));

//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
               'css/edit.css',
               'css/upload_files.css',
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

              'js/pages_common.js',
              'js/edit_common.js',
              'country_select.js.php',
              'edit_store.js.php'
          );



$smarty->assign('edit',$_SESSION['state'][$page]['edit']);



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('store_key',$store->id);



$subject_id=$store_id;


$smarty->assign($page,$store);

$smarty->assign('parent','products');
$smarty->assign('title', $store->data['Store Name']);


$stores=array();
$sql=sprintf("select * from `Store Dimension` CD order by `Store Key`");

$res=mysql_query($sql);
$first=true;
while ($row=mysql_fetch_array($res)) {
    $stores[$row['Store Key']]=array('code'=>$row['Store Code'],'selected'=>0);
    if ($first) {
        $stores[$row['Store Key']]['selected']=1;
        $first=FALSE;
    }
}
mysql_free_result($res);





$smarty->assign('stores',$stores);


$tipo_filter=$_SESSION['state']['store']['history']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['store']['history']['f_value']);
$filter_menu=array(
                 'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
                 'author'=>array('db_key'=>'author','menu_label'=>_('Done by <i>x</i>*'),'label'=>_('Notes')),
                 'uptu'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
                 'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)')),
                 'abstract'=>array('db_key'=>'abstract','menu_label'=>_('Records with abstract'),'label'=>_('Abstract'))

             );
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['store']['deals']['f_field'];
$smarty->assign('filter4',$tipo_filter);
$smarty->assign('filter_value4',$_SESSION['state']['store']['deals']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'notes','menu_label'=>_('Offers with name like *<i>x</i>*'),'label'=>_('Name')),
               
            
             );
$smarty->assign('filter_name4',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu4',$paginator_menu);

$tipo_filter=$_SESSION['state']['store']['edit_pages']['f_field'];
$smarty->assign('filter6',$tipo_filter);
$smarty->assign('filter_value6',$_SESSION['state']['store']['edit_pages']['f_value']);
$filter_menu=array(
                 'code'=>array('db_key'=>_('code'),'menu_label'=>_('Code'),'label'=>_('Code')),
                  'header'=>array('db_key'=>_('header'),'menu_label'=>_('Header'),'label'=>_('Header')),

             );
$smarty->assign('filter_menu6',$filter_menu);
$smarty->assign('filter_name6',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu6',$paginator_menu);


$number_of_sites=0;
$site_key=0;


$sql=sprintf("select count(*) as num, `Site Key` from `Site Dimension` where `Site Store Key`=%d ",
             $store->id);

$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
    $number_of_sites=$row['num'];
    if ($number_of_sites==1)
        $site_key=$row['Site Key'];

}

$smarty->assign('number_of_sites',$number_of_sites);
$smarty->assign('site_key',$site_key);

$smarty->display('edit_store.tpl');

?>
