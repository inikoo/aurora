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
               'css/container.css',
               'button.css',
               'table.css',
               'css/index.css',
               'theme.css.php'

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


$dashboards=array();
$sql=sprintf("select `Dashboard ID` from `Dashboard User Bridge` where `User Key`=%d group by `Dashboard ID`", $user->id);

//print $sql;

$res=mysql_query($sql);

while($row=mysql_fetch_assoc($res)){
	$dashboards[]=$row['Dashboard ID'];
}
$smarty->assign('number_of_dashboards',count($dashboards));

//print_r($dashboards);
foreach($dashboards as $dashboards_a) {
        $status_sql[] = '\''.$dashboards_a.'\'';
    }
$dashboards = implode(',',$status_sql);
//print_r($dashboards);

$dashboard_data=array();
$sql=sprintf("select * from `Dashboard User Bridge` where `Dashboard ID` in (%s) and `User Key`=%d order by `Dashboard ID` ", $dashboards, $user->id);
//print $sql;
$res=mysql_query($sql);

while($row=mysql_fetch_assoc($res)){
	$dashboard_data[$row['Dashboard ID']][]=array('order'=>$row['Dashboard Order']);
}

//print_r($dashboard_data);


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
              'js/edit_common.js',
            
          
              'dashboard_configuration.js.php?user_id='.$user->id,
          );

$smarty->assign('dashboard_data',$dashboard_data);
$smarty->assign('user_id',$user->id);
$smarty->assign('parent','home');
$smarty->assign('title', _('Dashboard Configuration'));

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('dashboard_configuration.tpl');
?>

