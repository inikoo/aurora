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
               'container.css',
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


  
  $general_options_list[]=array('tipo'=>'url','url'=>'customer_categories.php?store_id='.$store->id.'&id=0','label'=>_('Categories'));
//$general_options_list[]=array('tipo'=>'url','url'=>'customers_lists.php?store='.$store->id,'label'=>_('Lists'));
$general_options_list[]=array('tipo'=>'url','url'=>'search_customers.php?store='.$store->id,'label'=>_('Advanced Search'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers_stats.php','label'=>_('Stats'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));
$general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'new_customers_list.php?store='.$store_id,'label'=>_('New List'));

  
  
//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('options_box_width','600px');

$smarty->display('customers_lists.tpl');
?>
