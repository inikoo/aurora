<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/
include_once('common.php');
if (!$user->can_view('stores')) {
    header('Location: index.php');
    exit();
}

$available_stores_list=$user->stores;
$available_stores=count($available_stores_list);
if ($available_stores==1) {
    header('Location: store_stats.php?id='.$available_stores_list[0]);
    exit;
}

$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');
$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
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
              //'external_libs/ampie/ampie/swfobject.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
              'js/csv_common.js',
              'stores_stats.js.php',
              'external_libs/ammap/ammap/swfobject.js'
          );


//$smarty->assign('advanced_search',$_SESSION['state']['customers']['advanced_search']);


$smarty->assign('parent','products');
$smarty->assign('title', _('Stores Stats'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('view',$_SESSION['state']['stores']['stats_view']);
$smarty->display('stores_stats.tpl');
?>
