<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 5 December 2013 12:07:48 CET, Malaga, Spain
 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');


//include_once('stock_functions.php');
if (!$user->can_create('sites'))
    exit();
    
if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$store_key=$_REQUEST['id'];

} else {
	exit("no id");
}


$store=new Store($store_key);    
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

    
$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
               'css/edit.css',
               'theme.css.php'
           );

$js_files=array(

              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'dragdrop/dragdrop-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              'js/php.default.min.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
              'js/new_site.js'

          );

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('parent','websites');
$smarty->assign('title', _('New website'));


$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');





$locales=array(
	'en_GB'=>array('description'=>_('English').', '._('United Kingdom').' (£)'),
	'de_DE'=>array('description'=>_('German').', '._('Germany').' (€)'),
	'fr_FR'=>array('description'=>_('French').', '._('France').' (€)'),
	'es_ES'=>array('description'=>_('Spanish').', '._('Spain').' (€)'),
	'pl_PL'=>array('description'=>_('Polish').', '._('Poland').' (zł)'),
	'it_IT'=>array('description'=>_('Italian').', '._('Italy').' (€)'),
	);

$smarty->assign('locales',$locales);
$smarty->assign('default_locale',$store->data['Store Locale']);


$smarty->display('new_site.tpl');




?>
