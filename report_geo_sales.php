<?php
/*
 File: region.php

 UI product page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Address.php');
$ammap_path='external_libs/ammap_2.5.5';
$smarty->assign('ammap_path',$ammap_path);


$parent_page='product';
$smarty->assign('page',$parent_page);


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
             
               'container.css',
               'button.css',
              
                'css/calendar.css'
           );

include_once('Theme.php');

$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-debug.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              'js/php.default.min.js',
              'js/common.js',
              'js/table_common.js',
              'js/dropdown.js',
              'region.js.php',
              'reports_calendar.js.php',
              $ammap_path.'/ammap/swfobject.js'

          );
$report_name='report_geo_sales';

if (isset($_REQUEST['country'])) {
    $mode='country';
    $tag=$_REQUEST['country'];
}
elseif(isset($_REQUEST['wregion'])) {
    $mode='wregion';
    $tag=$_REQUEST['wregion'];
  

}
elseif(isset($_REQUEST['continent'])) {

    $mode='continent';
    $tag=$_REQUEST['continent'];
   

}
elseif(isset($_REQUEST['world'])) {
    $mode='world';
    $tag='world';
}else{
$mode=$_SESSION['state'][$report_name]['mode'];
$tag=$_SESSION['state'][$report_name]['mode_key'];
}

$_SESSION['state'][$report_name]['mode']=$mode;
$_SESSION['state'][$report_name]['mode_key']=$tag;



if(isset($_REQUEST['tipo'])){
  $tipo=$_REQUEST['tipo'];
  $_SESSION['state'][$report_name]['tipo']=$tipo;
}else
  $tipo=$_SESSION['state'][$report_name]['tipo'];


$root_title=_('Geographical Sales Report');
$smarty->assign('report_url','report_geo_sales.php');

if($_SESSION['state'][$report_name]['store_keys']=='all')
  $store_keys=join(',',$user->stores);
else
  $store_keys=$_SESSION['state'][$report_name]['store_keys'];


include_once('report_dates.php');
$_SESSION['state'][$report_name]['from']=$from;
$_SESSION['state'][$report_name]['to']=$to;


$smarty->assign('tipo',$tipo);
$smarty->assign('period',$period);

$smarty->assign('title',$title);
$smarty->assign('year',date('Y'));
$smarty->assign('month',date('m'));
$smarty->assign('month_name',date('M'));


$smarty->assign('week',date('W'));

$smarty->assign('from',$from);
$smarty->assign('to',$to);


switch ($mode) {
case 'world':
 $tag='world';
 $js_files[]='report_geo_sales_world.js.php';
 $view=$_SESSION['state'][$report_name]['world']['view'];
    $map_link=$_SESSION['state'][$report_name]['world']['map_links'];


    $smarty->assign('settings_file','conf/world_heatmap_settings.xml');
    $smarty->assign('view',$view);
    $smarty->assign('map_links',$map_link);


    $template='report_geo_sales_world.tpl';
    break;
case 'wregion':


  $sql=sprintf("select `World Region`,`Continent`,`Continent Code` from kbase.`Country Dimension` where `World Region Code`=%s",prepare_mysql($tag));
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $smarty->assign('wregion_name',$row['World Region']);
                $smarty->assign('continent_name',$row['Continent']);

                $smarty->assign('continent_code',$row['Continent Code']);

    } else {
        header('Location: report_geo_sales.php?world');
        print $sql;
        exit;

    }



  $js_files[]='report_geo_sales_wregion.js.php';
    $smarty->assign('wregion_code',$tag);
    $template='report_geo_sales_wregion.tpl';
    $_SESSION['state']['wregion']['code']=$tag;
    
    
    
       $view=$_SESSION['state'][$report_name]['wregion']['view'];
$map_link=$_SESSION['state'][$report_name]['wregion']['map_links'];
    $smarty->assign('view',$view);
    $smarty->assign('map_links',$map_link);

    

    break;
case 'continent':
 $js_files[]='report_geo_sales_continent.js.php';
    $smarty->assign('continent_code',$tag);
    $template='report_geo_sales_continent.tpl';
    $_SESSION['state']['continent']['code']=$tag;
    
    
       $view=$_SESSION['state'][$report_name]['continent']['view'];
$map_link=$_SESSION['state'][$report_name]['continent']['map_links'];
    $smarty->assign('view',$view);
    $smarty->assign('map_links',$map_link);

    
    
    break;

case 'country':

    $country=new Country('code',  Address::parse_country($tag));
    $smarty->assign('country',$country);
    $template='country.tpl';
}
$_SESSION['state']['region']['tag']=$tag;
$_SESSION['state']['region']['mode']=$mode;

$_SESSION['state']['region']['orders']['mode']=$mode;
$_SESSION['state']['region']['customers']['mode']=$mode;




$tipo_filter0=$_SESSION['state']['world']['countries']['f_field'];
$filter_menu0=array(
    'country_code'=>array('db_key'=>_('country_code'),'menu_label'=>_('Country Code'),'label'=>_('Code')),
    'wregion_code'=>array('db_key'=>_('wregion_code'),'menu_label'=>_('World Region Code'),'label'=>_('Region Code')),
    'continent_code'=>array('db_key'=>_('continent_code'),'menu_label'=>_('Continent Code'),'label'=>_('Continent Code')),
);

$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0',$_SESSION['state']['world']['countries']['f_value']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);


$tipo_filter1=$_SESSION['state']['world']['wregions']['f_field'];
$filter_menu1=array(
                  'wregion_code'=>array('db_key'=>_('wregion_code'),'menu_label'=>_('World Region Code'),'label'=>_('Region Code')),
                  'continent_code'=>array('db_key'=>_('continent_code'),'menu_label'=>_('Continent Code'),'label'=>_('Continent Code')),
              );

$smarty->assign('filter_name1',$filter_menu0[$tipo_filter1]['label']);
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter1',$tipo_filter1);
$smarty->assign('filter_value1',$_SESSION['state']['world']['wregions']['f_value']);
$paginator_menu1=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu1);


$tipo_filter2=$_SESSION['state']['world']['continents']['f_field'];
$filter_menu2=array(
                  'continent_code'=>array('db_key'=>_('continent_code'),'menu_label'=>_('Continent Code'),'label'=>_('Continent Code')),
              );

$smarty->assign('filter_name2',$filter_menu0[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter1',$tipo_filter2);
$smarty->assign('filter_value2',$_SESSION['state']['world']['continents']['f_value']);
$paginator_menu2=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu2);

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display($template);
?>
