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
		'world.js.php'
          );

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if(isset($_REQUEST['country'])){
$mode='country';
$tag=$_REQUEST['country'];
}if(isset($_REQUEST['wregion'])){
$mode='wregion';
$tag=$_REQUEST['wregion'];
}else{
$mode='world';
$tag='world';
}

switch ($mode) {
case 'world':
$template='world.tpl';
    break;
case 'wregion':
  
    $tempalte='world_region.tpl';
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





//$tipo_filter=$_SESSION['state']['country_list']['table']['f_field'];

//$smarty->assign('filter0',$tipo_filter);
//$smarty->assign('filter_value0',$_SESSION['state']['country_list']['table']['f_value']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->display($template);
?>
