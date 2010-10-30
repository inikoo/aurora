<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Kaktus

 Version 2.0
*/

include_once('common.php');
$general_options_list=array();
$smarty->assign('general_options_list',$general_options_list);



$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               'common.css',
               'button.css',
               'container.css',
               'table.css',
               'css/users.css'
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
              'common.js.php',
              'table_common.js.php',
              'js/search.js',
              'users.js.php',

          );




$smarty->assign('search_scope','users');

$smarty->assign('search_label',_('Search'));

$smarty->assign('parent','users');
$smarty->assign('title', _('Users'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$sql=sprintf("select `Store Key` from `Store Dimension`");
$res=mysql_query($sql);
$stores=array();
$number_stores=0;
while($row=mysql_fetch_assoc($res)){
$number_stores++;
$stores[]=new Store($row['Store Key']);
}

$smarty->assign('number_stores',$number_stores);
$smarty->assign('stores',$stores);


$sql=sprintf("select count(*) as num  from `Supplier Dimension`");
$res=mysql_query($sql);
$number_suppliers=0;
while($row=mysql_fetch_assoc($res)){
$number_suppliers=$row['num'];

}

$smarty->assign('number_suppliers',$number_suppliers);

$sql=sprintf("select count(*) as num  from `Staff Dimension` where `Staff Currently Working`='Yes'");
$res=mysql_query($sql);
$number_staff=0;
while($row=mysql_fetch_assoc($res)){
$number_staff=$row['num'];

}

$smarty->assign('number_staff',$number_staff);


$smarty->display('users.tpl');
?>

