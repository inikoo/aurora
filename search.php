<?php
/*
 File: index.php

 UI index page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once('common.php');


$general_options_list=array();
$smarty->assign('general_options_list',$general_options_list);


$smarty->assign('store_keys',join(',',$user->stores));

 $query=''; 
if(isset($_REQUEST['q'])){
$query=$_REQUEST['q'];
}
$smarty->assign('query',$query);


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               'common.css',
               'button.css',
               'css/container.css',
               'table.css',
               'css/index.css'
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
              'search.js.php',

          );

 $query=''; 
if(isset($_REQUEST['q'])){

}


$smarty->assign('search_scope','all');

$smarty->assign('search_label',_('Search'));

$smarty->assign('parent','home');
$smarty->assign('title', _('Search'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('search.tpl');
?>

