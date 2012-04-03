<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');

include_once('class.Page.php');
include_once('class.Site.php');


if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $page_key=$_REQUEST['id'];

} else {
    $page_key=$_SESSION['state']['page']['id'];
}




if (!($user->can_view('stores')    ) ) {
    header('Location: index.php');
    exit;
}


include_once('class.Image.php');
$page=new Page($page_key);
//$page->update_preview_snapshot();
//exit;

if (!$page->id) {
    header('Location: index.php');
    exit;
}


$_SESSION['state']['page']['id']=$page->id;

$site=new Site($page->data['Page Site Key']);
$smarty->assign('site',$site);
$store=new Store($site->data['Site Store Key']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);
$smarty->assign('page',$page);
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
               'css/container.css',
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

$js_files[]='page.js.php';



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




if (isset($_REQUEST['view'])) {
    $valid_views=array('details','hits','visitors');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state']['page']['view']=$_REQUEST['view'];

}
$smarty->assign('block_view',$_SESSION['state']['page']['view']);

$subject_id=$page_key;
$smarty->assign('site',$site);

$smarty->assign('parent','websites');
$smarty->assign('title', $page->data['Page Store Title']);


$order=$_SESSION['state']['site']['pages']['order'];
if ($order=='code') {
    $order='`Page Code`';
    $order_label=_('Code');
} else if ($order=='url') {
    $order='`Page URL`';
    $order_label=_('URL');
} else if ($order=='title') {
    $order='`Page Store Title`';
    $order_label=_('Title');
} else {
    $order='`Page Code`';
    $order_label=_('Code');
}

$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Page Key` as id , `Page Store Title` as name from `Page Store Dimension`   where  `Page Site Key`=%d  and %s < %s  order by %s desc  limit 1",
             $site->id,
             $order,
             prepare_mysql($page->get($_order)),
             $order
            );

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $prev['link']='page.php?id='.$row['id'];
    $prev['title']=$row['name'];
    $smarty->assign('prev',$prev);
}
mysql_free_result($result);


$sql=sprintf(" select `Page Key` as id , `Page Store Title` as name from `Page Store Dimension`    where  `Page Site Key`=%d  and  %s>%s  order by %s   ",
             $site->id,
             $order,
             prepare_mysql($page->get($_order)),
             $order
            );

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $next['link']='page.php?id='.$row['id'];
    $next['title']=$row['name'];
    $smarty->assign('next',$next);
}
mysql_free_result($result);


$smarty->assign('parent_url','site.php?id='.$site->id);
$parent_title=$site->data['Site Name'].' '._('Pages').' ('.$order_label.')';
$smarty->assign('parent_title',$parent_title);

$tipo_filter=$_SESSION['state']['page']['requests']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['page']['requests']['f_value']);
$filter_menu=array(
                 'handle'=>array('db_key'=>'handle','menu_label'=>_('Handle starting with  <i>x</i>'),'label'=>_('Handle')),

             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$tipo_filter=$_SESSION['state']['page']['users']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['page']['users']['f_value']);
$filter_menu=array(
                'handle'=>array('db_key'=>'handle','menu_label'=>_('Handle starting with  <i>x</i>'),'label'=>_('Handle')),


             );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$smarty->display('page.tpl');

?>
