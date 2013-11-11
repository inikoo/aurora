<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');
if (!$user->can_view('customers')) {
    header('Location: index.php');
    exit();
}
if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_key=$_REQUEST['store'];
} else {
    $store_key=$_SESSION['state']['customers']['store'];
}
if (!($user->can_view('stores') and in_array($store_key,$user->stores)   ) ) {
    header('Location: index.php');
    exit;
}
$store=new Store($store_key);
$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

$_SESSION['state']['customers']['store']=$store_key;
$modify=$user->can_edit('customers');

$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');
$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
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
              //'external_libs/ampie/ampie/swfobject.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
              
              'customers_stats.js.php',
              'external_libs/ammap/ammap/swfobject.js'
          );


//$smarty->assign('advanced_search',$_SESSION['state']['customers']['advanced_search']);


$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers Stats'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$smarty->assign('view',$_SESSION['state']['customers']['stats_view']);


$tipo_filter=$_SESSION['state']['customers']['correlations']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customers']['correlations']['f_value']);

$filter_menu=array(
	'name_a'=>array('db_key'=>'name_a','menu_label'=>_('Customer Name'),'label'=>_('Name')),
	'correlation_more'=>array('db_key'=>'correlation_more','menu_label'=>_('Correlation more than'),'label'=>_('Correlation').' >'),
	'correlation_less'=>array('db_key'=>'correlation_less','menu_label'=>_('Correlation less than'),'label'=>_('Correlation').' <'),
);


$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);




$smarty->display('customers_stats.tpl');
?>
