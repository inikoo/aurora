<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/
include_once('common.php');

if (!$user->can_view('customers')) {
    exit();
}


$smarty->assign('box_layout','yui-t0');

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
                'common.css',
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
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/csv_common.js',
              'customers_server_stats.js.php'
          );






$smarty->assign('advanced_search',$_SESSION['state']['customers']['advanced_search']);



$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers Statistics'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');




$smarty->display('customers_server_stats.tpl');

?>
