<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('assets_header_functions.php');
include_once('class.Account.php');

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
              'js/csv_common.js',
              'country_select.js.php',
              'edit_account.js.php'
          );
          
         
$smarty->assign('parent','account');
$smarty->assign('title', _('Editing Account'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if (!$user->can_edit('account')){

if($user->can_view('account')){
 header('Location: account.php');
    exit();
}

$smarty->assign('scope', 'edit_account');
	$smarty->display('forbidden.tpl');
 exit();
}


$filter_menu=array(
	'name'=>array('db_key'=>'notes','menu_label'=>_('Fields with name *<i>x</i>*'),'label'=>_('Name')),
);
$tipo_filter=$_SESSION['state']['account']['custom_fields']['f_field'];
$filter_value=$_SESSION['state']['account']['custom_fields']['f_value'];

$smarty->assign('filter_value0',$filter_value);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$account=new Account();
$smarty->assign('account',$account);


$block_view='description';
$smarty->assign('block_view',$block_view);

$smarty->display('edit_account.tpl');




?>
