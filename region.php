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
               'css/container.css',
               'button.css'
           );

if($common)
{
array_push($css_files, 'themes_css/'.$common);   
array_push($css_files, 'themes_css/'.$row['Themes css4']);
array_push($css_files, 'themes_css/'.$row['Themes css2']); 
}    

else{
array_push($css_files, 'common.css'); 
array_push($css_files, 'css/dropdown.css');
array_push($css_files, 'table.css');
}



$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-debug.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              'js/php.default.min.js',
              'js/common.js',
              'js/table_common.js',
              'js/dropdown.js',
              'region.js.php',
              $ammap_path.'/ammap/swfobject.js'

          );


if (isset($_REQUEST['country'])) {
    $mode='country';
    $tag=$_REQUEST['country'];
}
elseif(isset($_REQUEST['wregion'])) {
    $mode='wregion';
    $tag=$_REQUEST['wregion'];
    $js_files[]='wregion.js.php';

}
elseif(isset($_REQUEST['continent'])) {

    $mode='continent';
    $tag=$_REQUEST['continent'];
    $js_files[]='continent.js.php';

}
else {
    $mode='world';
    $tag='world';

    $js_files[]='world.js.php';

}

switch ($mode) {
case 'world':
    $view=$_SESSION['state']['world']['view'];



    $smarty->assign('settings_file','conf/world_map_settings.xml');
    $smarty->assign('view',$view);


    $template='world.tpl';
    break;
case 'wregion':
    $smarty->assign('wregion_code',$tag);
    $smarty->assign('settings_file','conf/world_map_settings.xml');
    $template='wregion.tpl';
    $_SESSION['state']['wregion']['code']=$tag;

    $sql=sprintf("select `World Region` from kbase.`Country Dimension` where `World Region Code`=%s",prepare_mysql($tag));
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $smarty->assign('wregion_name',$row['World Region']);
    } else {
        header('Location: region.php?world');
        print $sql;
        exit;

    }

    $tipo_filter0=$_SESSION['state']['wregion']['countries']['f_field'];
    $filter_menu0=array(
        'country_code'=>array('db_key'=>_('country_code'),'menu_label'=>_('Country Code'),'label'=>_('Code'))

    );

    $smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
    $smarty->assign('filter_menu0',$filter_menu0);
    $smarty->assign('filter0',$tipo_filter0);
    $smarty->assign('filter_value0',$_SESSION['state']['wregion']['countries']['f_value']);
    $paginator_menu0=array(10,25,50,100,500);
    $smarty->assign('paginator_menu0',$paginator_menu0);



    break;
case 'continent':
    $smarty->assign('continent_code',$tag);
    $template='continent.tpl';
    $_SESSION['state']['continent']['code']=$tag;
    break;

case 'country':
    
    $country=new Country('code',  Address::parse_country($tag));
    $smarty->assign('country',$country);
    $template='country.tpl';
    $smarty->assign('settings_file','conf/country_map_settings.xml');

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


}
$_SESSION['state']['region']['tag']=$tag;
$_SESSION['state']['region']['mode']=$mode;

$_SESSION['state']['region']['orders']['mode']=$mode;
$_SESSION['state']['region']['customers']['mode']=$mode;




$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display($template);
?>
