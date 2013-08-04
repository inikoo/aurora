<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

include_once('common.php');
include_once('class.Warehouse.php');

if (!$user->can_view('warehouses')) {
    header('location:index.php?forbidden');
    exit();
}


if (count($user->warehouses)==0) {
    header('location:index.php?forbidden');
    exit();
}
elseif(count($user->warehouses)==1) {


    $_tmp=$user->warehouses;
    $warehouse_key=array_pop($_tmp);
    $warehouse= new Warehouse($warehouse_key);
    if (!$warehouse->id) {
        header('location:index.php?error');
        exit();
    }

}
else {
    header('location:warehouses.php');
    exit();

}



$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
               'css/users.css',
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
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'inventory.js.php'

          );




$smarty->assign('search_scope','parts');
$smarty->assign('search_label',_('Search'));

$smarty->assign('parent','warehouses');
$smarty->assign('title', _('Inventory'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$general_options_list=array();


$smarty->assign('general_options_list',$general_options_list);


$smarty->display('inventory.tpl');
?>

