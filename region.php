<?php
/*
 File: region.php

 UI product page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Kaktus

 Version 2.0
*/
include_once('common.php');
include_once('class.Address.php');


$parent_page='product';
$smarty->assign('page',$parent_page);


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
               'common.css',
               'container.css',
               'button.css',
               'table.css',
               'css/dropdown.css'
           );
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
              'common.js.php',
              'table_common.js.php',
              'js/dropdown.js',
              'region.js.php',
		
          );


if(isset($_REQUEST['country'])){
$mode='country';
$tag=$_REQUEST['country'];
}if(isset($_REQUEST['wregion'])){
$mode='wregion';
$tag=$_REQUEST['wregion'];
}if(isset($_REQUEST['continent'])){
$mode='continent';
$tag=$_REQUEST['continent'];
$js_files[]='continent.js.php';

}else{
$mode='world';
$tag='world';

$js_files[]='world.js.php';

}

switch ($mode) {
case 'world':
$template='world.tpl';
    break;
case 'wregion':
  
    $tempalte='world_region.tpl';
    break;
case 'continent':
    $smarty->assign('continent_code',$tag);
$template='continent.tpl';
    
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





$tipo_filter=$_SESSION['state']['world']['countries']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['world']['countries']['f_value']);

$tipo_filter=$_SESSION['state']['world']['wregions']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['world']['wregions']['f_value']);

$tipo_filter=$_SESSION['state']['world']['continents']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['world']['wregions']['f_value']);


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('paginator_menu1',$paginator_menu);
$smarty->assign('paginator_menu2',$paginator_menu);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display($template);
?>
