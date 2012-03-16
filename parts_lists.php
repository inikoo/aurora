<?php
include_once('common.php');
include_once('class.Warehouse.php');



if(isset($_REQUEST['warehouse_id']) and is_numeric($_REQUEST['warehouse_id']) ){
  $warehouse_id=$_REQUEST['warehouse_id'];

}else{
  header('Location: index.php?error_no_warehouse_key');
   exit;
}


$warehouse=new warehouse($warehouse_id);
if(!($user->can_view('warehouses') and in_array($warehouse_id,$user->warehouses)   ) ){
  header('Location: index.php');
   exit;
}


$modify=$user->can_edit('warehouses');

$smarty->assign('modify',$modify);


$smarty->assign('view_parts',$user->can_view('parts'));
//get_header_info($user,$smarty);







$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');



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
              'parts_lists.js.php',
              'js/list_function.js',
              
              'js/menu.js'
          );


$smarty->assign('parent','parts');
$smarty->assign('title', _('Parts Lists'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


  
$tipo_filter=$_SESSION['state']['warehouse']['parts_lists']['f_field'];
$smarty->assign('filter_name0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['warehouse']['parts_lists']['f_value']);
$filter_menu=array(
       'name'=>array('db_key'=>'name','menu_label'=>_('List name like <i>x</i>'),'label'=>_('Name'))
             );
             
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);

$smarty->display('parts_lists.tpl');
?>
