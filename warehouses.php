<?php
include_once('common.php');
include_once('class.Warehouse.php');


if (!($user->can_view('warehouses')  ) ) {
    header('Location: index.php');
    exit;
}
$create=$user->can_create('warehouses');
$modify=$user->can_edit('warehouses');
$smarty->assign('view_parts',$user->can_view('parts'));


$general_options_list=array();



$smarty->assign('general_options_list',$general_options_list);

$smarty->assign('search_label',_('Locations'));
$smarty->assign('search_scope','locations');

$view=$_SESSION['state']['warehouses']['view'];
$smarty->assign('view',$view);



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
              'js/common.js',
              'js/table_common.js',
              'js/edit_common.js',
              
              'js/search.js',
              
              'warehouses.js.php'
          );




$smarty->assign('parent','inventory');
$smarty->assign('title', _('Warehouses'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$tipo_filter=$_SESSION['state']['warehouses']['warehouses']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['warehouses']['warehouses']['f_value']);
$filter_menu=array(
                 'code'=>array('db_key'=>_('code'),'menu_label'=>'Warehouse Code','label'=>'Code'),
             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->display('warehouses.tpl');

?>
