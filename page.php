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



$page=new Page($page_key);
$page->get_products_from_source();
exit;

if (!$page->id) {
    header('Location: index.php');
    exit;
}


$_SESSION['state']['page']['id']=$page->id;

$store=new Store($page->data['Page Store Key']);
$smarty->assign('store',$store);
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




$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');

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

$smarty->assign('parent','products');
$smarty->assign('title', $page->data['Page Store Title']);





$smarty->display('page.tpl');

?>
