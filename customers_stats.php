<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');
if (!$user->can_view('customers')) {
    header('Location: index.php');
    exit();
}
if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_id=$_REQUEST['store'];
} else {
    $store_id=$_SESSION['state']['customers']['store'];
}
if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
    header('Location: index.php');
    exit;
}
$store=new Store($store_id);
$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);
$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);

$_SESSION['state']['customers']['store']=$store_id;
$modify=$user->can_edit('customers');

$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');
$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
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
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              //'external_libs/ampie/ampie/swfobject.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
              'js/csv_common.js',
              'customers_stats.js.php',
              'external_libs/ammap/ammap/swfobject.js'
          );


//$smarty->assign('advanced_search',$_SESSION['state']['customers']['advanced_search']);


$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers Stats'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$smarty->assign('view',$_SESSION['state']['customers']['stats_view']);




$smarty->display('customers_stats.tpl');
?>
