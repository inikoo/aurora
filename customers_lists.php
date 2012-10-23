<?php
include_once('common.php');
include_once('class.Store.php');

if (!$user->can_view('customers') or count($user->stores)==0 ) {
	
    header('Location: index.php');
    exit;
}
if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_id=$_REQUEST['store'];

} else {
    $store_id=$_SESSION['state']['customers']['store'];

}

if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
	
    header('Location: index.php');
    exit;
}

$store=new Store($store_id);

$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);



$css_files=array(
           $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'common.css',
               'css/container.css',
               'button.css',
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
              'customers_lists.js.php'
          );


$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers Lists'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');


$tipo_filter=$_SESSION['state']['customers']['list']['f_field'];
$smarty->assign('filter_name0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customers']['list']['f_value']);
$filter_menu=array(
       'name'=>array('db_key'=>'name','menu_label'=>_('List name like <i>x</i>'),'label'=>_('Name'))
             );
             
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$smarty->display('customers_lists.tpl');
?>
