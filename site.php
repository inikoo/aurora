<?php
/*
 File: site.php

 UI site page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');

include_once('class.Site.php');
include_once('assets_header_functions.php');


$smarty->assign('page','site');
if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $site_id=$_REQUEST['id'];

} else {
    $site_id=$_SESSION['state']['site']['id'];
}




if (!($user->can_view('stores')    ) ) {
    header('Location: index.php');
    exit;
}



$site=new Site($site_id);
if (!$site->id) {
    header('Location: index.php');
    exit;
}


$_SESSION['state']['site']['id']=$site->id;

$store=new Store($site->data['Site Store Key']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

$create=$user->can_create('sites');

$modify=$user->can_edit('sites');


$smarty->assign('create',$create);
$smarty->assign('modify',$modify);



$smarty->assign('search_label',_('Website'));
$smarty->assign('search_scope','site');

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'container.css',
               'button.css',
               'table.css',
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
              'js/edit_common.js',
              'js/csv_common.js',
              'js/dropdown.js'
          );


$js_files[]='js/search.js';
$js_files[]='common_plot.js.php?page='.'site';

$js_files[]='site.js.php';



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$_SESSION['state']['assets']['page']='site';
if (isset($_REQUEST['view'])) {
    $valid_views=array('sales','general','stoke');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state']['site']['view']=$_REQUEST['view'];

}
$smarty->assign('block_view',$_SESSION['state']['site']['view']);





$subject_id=$site_id;


$smarty->assign('site',$site);

$smarty->assign('parent','products');
$smarty->assign('title', $site->data['Site Name']);


$tipo_filter=$_SESSION['state']['site']['pages']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['site']['pages']['f_value']);
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Page code starting with  <i>x</i>','label'=>'Code'),
                 'title'=>array('db_key'=>'code','menu_label'=>'Page title like  <i>x</i>','label'=>'Code'),

             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('table_type',$_SESSION['state']['site']['pages']['type']);
$elements_number=array('FamilyCatalogue'=>0,'DepartmentCatalogue'=>0,'ProductDescription'=>0,'Other'=>0);
$sql=sprintf("select count(*) as num,`Page Store Section` from  `Page Store Dimension` where `Page Site Key`=%d group by `Page Store Section`",$site->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
$_key=preg_replace('/ /','',$row['Page Store Section']);

   if(in_array($_key,array('FamilyCatalogue','DepartmentCatalogue','ProductDescription')))
   $elements_number[$_key]=$row['num'];
   else{
    $elements_number['Other']+=$row['num'];
   }
   
  
}
$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['site']['pages']['elements']);


$smarty->display('site.tpl');

?>
