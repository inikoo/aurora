<?php
/*
 About: 
 Autor: Raul Perusquia <raul@inikoo.com>
 
 Copyright (c) 2014, Inikoo 
 Created: 14 May 2014 14:30:19 BST, Sheffield UK
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Store.php');
include_once('class.DealCampaign.php');

if (!$user->can_view('stores') or count($user->stores)==0 ) {
	
    header('Location: index.php?e1');
    exit;
}
if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $campaign_key=$_REQUEST['id'];

} else {
      header('Location: marketing_server.php');
    exit;

}


$campaign=new DealCampaign($campaign_key);

if(!$campaign->id){
 header('Location: marketing_server.php');
    exit;

}


$store_key=$campaign->data['Deal Campaign Store Key'];
$store=new Store($store_key);

if (!($user->can_view('stores') and in_array($store_key,$user->stores)   ) ) {
	print $store_key;
   // header('Location: index.php?e2');
    exit;
}



$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);
$smarty->assign('store_id',$store->id);

$smarty->assign('campaign',$campaign);





$smarty->assign('edit_block_view',$_SESSION['state']['campaign']['edit_block_view']);

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'css/common.css',
               'css/button.css',
               'css/container.css',
               'css/edit.css',
               'css/table.css',
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
              'edit_campaign.js.php',
          
          );


$smarty->assign('parent','marketing');
$smarty->assign('title', _('Edit Campaign').': '.$campaign->data['Deal Campaign Code']);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

  
$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->display('edit_campaign.tpl');
?>
