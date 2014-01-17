<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('common.php');

include_once('class.Account.php');

//include_once('stock_functions.php');
if (!$user->can_create('account'))
    exit();






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
              'js/new_store.js'

          );



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);





$smarty->assign('parent','products');
$smarty->assign('title', _('New Store'));





global $myconf;






$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');
$smarty->assign('store_key','');


$tipo_filter2='code';
$filter_menu2=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Country Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Country Name'),'label'=>_('Name')),
	'wregion'=>array('db_key'=>'wregion','menu_label'=>_('World Region Name'),'label'=>_('Region')),
);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2','');



$locales=array(
	'en_GB'=>array('description'=>_('English').', '._('United Kingdom').' (£)'),
	'de_DE'=>array('description'=>_('German').', '._('Germany').' (€)'),
	'fr_FR'=>array('description'=>_('French').', '._('France').' (€)'),
	'es_ES'=>array('description'=>_('Spanish').', '._('Spain').' (€)'),
	'pl_PL'=>array('description'=>_('Polish').', '._('Poland').' (zł)'),
	'it_IT'=>array('description'=>_('Italian').', '._('Italy').' (€)'),
	);

$smarty->assign('locales',$locales);
$smarty->assign('default_locale','en_GB');


$smarty->display('new_store.tpl');




?>
