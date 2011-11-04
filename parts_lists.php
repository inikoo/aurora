<?php
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



$store=new Store($store_id);




$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);



$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'common.css',
               'button.css',
               'container.css',
               'table.css',
               'css/marketing_menu.css',
               'css/marketing_campaigns.css'
           );
$css_files[]='theme.css.php';
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
              'parts_lists.js.php',
              'js/list_function.js',
              
              'js/menu.js'
          );


$smarty->assign('parent','parts');
$smarty->assign('title', _('Parts Lists'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'new_parts_list.php?store='.$store_id,'label'=>_('New List'));
$general_options_list[]=array('tipo'=>'url','url'=>'warehouse_parts.php','label'=>_('Warehouse'));
   //  $general_options_list[]=array('tipo'=>'url','url'=>'product_categories.php','label'=>_('Categories'));
 //$general_options_list[]=array('tipo'=>'url','url'=>'store.php?id='.$store->id,'label'=>_('Store'));
  
$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('options_box_width','600px');

$smarty->display('parts_lists.tpl');
?>
