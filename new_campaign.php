<?php
/*
 About: 
 Autor: Raul Perusquia <raul@inikoo.com>
 
 Copyright (c) 2014, Inikoo
 Created: 13 May 2014 11:04:44 BST Sheffield, UK
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Store.php');
if (!$user->can_view('stores') or count($user->stores)==0 ) {
	
    header('Location: index.php');
    exit;
}
if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $store_id=$_REQUEST['id'];

} else {
  
    header('Location: index.php?no_id');
    exit;
}

if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
	
    header('Location: index.php?forbidden');
    exit;
}

$store=new Store($store_id);




$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'css/common.css',
               'css/button.css',
               'css/container.css',
               'css/table.css',
                 'css/edit.css',
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
              'js/edit_common.js',
              'js/search.js',
              'new_campaign.js.php',
          
          );


$smarty->assign('parent','products');
$smarty->assign('title', _('New Campaign'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


;
  
$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');






$smarty->assign('link_back','marketing.php');



$smarty->display('new_campaign.tpl');
?>
