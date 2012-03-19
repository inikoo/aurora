<?php
/*
 File: customers.php

 UI customers page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('common.php');

if (!$user->can_view('customers')) {
    exit();
}



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
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js','js/csv_common.js',
              'marketing_server.js.php'
          );








$smarty->assign('parent','marketing');
$smarty->assign('title', _('Marketing'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Marketing'));

$smarty->assign('search_scope','marketing');
$smarty->assign('search_label',_('Search'));


$general_options_list=array();
$smarty->assign('general_options_list',$general_options_list);
//$smarty->assign('search_label',_('Customers'));
//$smarty->assign('search_scope','customers');





$tipo_filter=$_SESSION['state']['stores']['marketing']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['stores']['marketing']['f_value']);

$filter_menu=array(
	   'code'=>array('db_key'=>'code','menu_label'=>_('Store Code'),'label'=>_('Code')),

		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);




//$smarty->assign('plot_tipo',$_SESSION['state']['customers']['plot']);
//$smarty->assign('view',$_SESSION['state']['customers']['table']['view']);



$smarty->display('marketing_server.tpl');

?>
