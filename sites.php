<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');

include_once('assets_header_functions.php');

if (!($user->can_view('stores')    ) ) {
    header('Location: index.php');
    exit;
}

if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_id=$_REQUEST['store'];

} else {
    header('Location: stores.php?error=no_store_key');
}


$store=new Store($store_id);
if (!$store->id) {
    header('Location: stores.php?error=no_store');
    exit;
}



if ($store->data['Store Websites']==0) {
    header('Location: stores.php');
    exit;
} else if ($store->data['Store Websites']==1) {
    $site_key=$store->get_active_sites_keys();
    header('Location: site.php?id='.array_pop($site_key));
    exit;
}

$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

$create=$user->can_create('sites');

$modify=$user->can_edit('sites');


$smarty->assign('create',$create);
$smarty->assign('modify',$modify);




$general_options_list=array();

//if ($modify)
$general_options_list[]=array('tipo'=>'url','url'=>'edit_site.php?id='.$site->id,'label'=>_('Edit Site'));


//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'container.css',
               'button.css',
               'table.css',
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
              'js/edit_common.js',
              'js/csv_common.js',
              'js/dropdown.js'
          );


$js_files[]='js/search.js';
$js_files[]='common_plot.js.php?page='.'site';

$js_files[]='site.js.php';



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$_SESSION['state']['assets']['page']='site';
if (isset($_REQUEST['view'])) {
    $valid_views=array('sales','general','stoke');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state']['site']['view']=$_REQUEST['view'];

}
$smarty->assign('block_view',$_SESSION['state']['site']['view']);





$subject_id=$site_id;


$smarty->assign('site',$site);

$smarty->assign('parent','products');
$smarty->assign('title', $site->data['Site Name']);

$q='';
$tipo_filter=($q==''?$_SESSION['state']['site']['pages']['f_field']:'code');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state']['site']['pages']['f_value']:addslashes($q)));
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Page code starting with  <i>x</i>','label'=>'Code'),
                 'title'=>array('db_key'=>'code','menu_label'=>'Page title like  <i>x</i>','label'=>'Code'),

             );
$smarty->assign('filter_menu0',$filter_menu);
//$smarty->assign('departments',$site->data['Site Departments']);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$smarty->display('site.tpl');

?>
