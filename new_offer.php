<?php
/*
 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2011, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Store.php');
if (!$user->can_view('stores') or count($user->stores)==0 ) {
	
    header('Location: index.php');
    exit;
}
if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_id=$_REQUEST['store'];

} else {
    $store_id=$_SESSION['state']['store']['id'];

}

if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
	
    header('Location: index.php');
    exit;
}

$store=new Store($store_id);




$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

$smarty->assign('block_view',$_SESSION['state']['store_offers']['view']);

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'common.css',
               'button.css',
               'css/container.css',
               'table.css',
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
              'js/common.js',
              'js/table_common.js',
              'js/edit_common.js',
              'js/search.js',
              'new_offer.js.php',
          
          );


$smarty->assign('parent','products');
$smarty->assign('title', _('New Offer'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


;
  
//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->display('new_offer.tpl');
?>
