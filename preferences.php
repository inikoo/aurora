<?php

/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once('common.php');

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
          // 'css/theme_3.css',
            'theme.css.php',
               'theme_showcase.css.php'
               );





$js_files=array(

              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              $yui_path.'yahoo/yahoo-min.js',
              $yui_path.'event/event-min.js',
              $yui_path.'connection/connection_core-min.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'preferences.js.php',

          );



$smarty->assign('view',$_SESSION['state']['preferences']['view']);

$theme=array();
$sql=sprintf("select * from `Theme Dimension`");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $themes[]=array('name'=>$row['Theme Name'],'key'=>$row['Theme Key']);
}
$smarty->assign('themes',$themes);


$backgrounds=array();
$sql=sprintf("select * from `Theme Background Dimension`  TB left join `Theme Background Bridge` B on (TB.`Theme Background Key`=B.`Theme Background Key`) where `Theme Key`=%d order by `Theme Background Name`",
$user->data['User Theme Key']
);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $backgrounds[]=array('name'=>$row['Theme Background Name'],'key'=>$row['Theme Background Key']);
}
$smarty->assign('backgrounds',$backgrounds);

$smarty->assign('parent','');


$title=_('Preferences');


$smarty->assign('title', $title);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('preferences.tpl');
?>
