<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/



include_once('common.php');

include_once('class.Product.php');
include_once('class.Order.php');


$smarty->assign('store_keys',join(',',$user->stores));

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'container.css',
               'button.css',
               'table.css',
               'css/index.css',
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
            
            
          
              'js/dashboard_confuguration.js',
          );


$blocks=array();
$sql=sprintf("select * from `Dashboard User Bridge` where `User Key`=%d order by `Dashboard Order`",
$user->id
);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
    $blocks[]=array('key'=>$row['Dashboard Key'],'src'=>$row['Dashboard URL'],'class'=>$row['Dashboard Class'],'metadata'=>$row['Dashboard Metadata']);
}
$smarty->assign('blocks',$blocks);




$smarty->assign('parent','home');
$smarty->assign('title', _('Home'));

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('dashboard_configuration.tpl');
?>

