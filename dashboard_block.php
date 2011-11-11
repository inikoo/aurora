<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

include_once('common.php');

if (!isset($_REQUEST['tipo']))
    exit;
$tipo=$_REQUEST['tipo'];

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'container.css',
               'button.css',
               'table.css',

               // 'css/index.css',
               'theme.css.php',
               'css/dashboard.css'
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
              'external_libs/ampie/ampie/swfobject.js',

              //      'js/index_tools.js',
              'js/index.js',

              //    'js/index_sliding_tabs.js.php?slide='.$_SESSION['state']['home']['display'],
          );




switch ($tipo) {
case 'sales_overview':
    $js_files[]='js/splinter_sales.js';
    $template='splinter_sales.tpl';
    
    switch ($_SESSION['state']['home']['splinters']['sales']['period']) {
        case 'ytd':
             $table_title=_('Overview Sales:  Year-to-Date');   
            break;
        default:
            $table_title=_('Sales').' '.$_SESSION['state']['home']['splinters']['sales']['period'];
            break;
        
    
    }
$smarty->assign('table_title',$table_title);
    break;
default:
    exit;
    break;
}
$smarty->assign('conf_data',$_SESSION['state']['home']['splinters']);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
if (isset($_REQUEST['block_key'])) {
    $smarty->assign('block_key',$_REQUEST['block_key']);
}
$smarty->display($template);
?>

