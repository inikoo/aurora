<?php
include_once('common.php');
include_once('class.Warehouse.php');
include_once('location_header_functions.php');

if (!($user->can_view('warehouses')  ) ) {
    header('Location: index.php');
    exit;
}
$create=$user->can_create('warehouses');
$modify=$user->can_edit('warehouses');
$smarty->assign('view_parts',$user->can_view('parts'));
get_header_info($user,$smarty);

$general_options_list=array();

//$general_options_list[]=array('tipo'=>'url','url'=>'locations.php','label'=>_('Locations'));
//$general_options_list[]=array('tipo'=>'url','url'=>'parts.php','label'=>_('Parts'));

if ($modify) {
    $general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'edit_warehouse.php','label'=>_('Edit Warehouses'));
    $general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'edit_warehouse.php','label'=>_('Add Warehouse'));
    $general_options_list[]=array('tipo'=>'url','url'=>'part_configuration.php','label'=>_('Part Configuration'));

}

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
              'js/table_common.js','js/edit_common.js','js/csv_common.js',
              'js/search.js',
              'js/dropdown.js',
              'warehouses.js.php'
          );




$smarty->assign('parent','warehouses');
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
$csv_export_options=array(
                        'description'=>array(
                                          'title'=>_('Description'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'id'=>array('label'=>_('Id'),'selected'=>$_SESSION['state']['warehouses']['warehouses']['csv_export']['id']),
                                                         'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['warehouses']['warehouses']['csv_export']['code']),
                                                         'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['warehouses']['warehouses']['csv_export']['name']),

                                                     )
                                                 )
                                      ),

                        'Numbers Of'=>array(
                                         'title'=>_('Other Details'),
                                         'rows'=>
                                                array(
                                                    array(
                                                        'locations_no'=>array('label'=>_('Locations'),'selected'=>$_SESSION['state']['warehouses']['warehouses']['csv_export']['locations_no']),
                                                        'areas_no'=>array('label'=>_('Areas'),'selected'=>$_SESSION['state']['warehouses']['warehouses']['csv_export']['areas_no']),
                                                        'shelfs_no'=>array('label'=>_('Shelfs'),'selected'=>$_SESSION['state']['warehouses']['warehouses']['csv_export']['shelfs_no']),



                                                    )
                                                )
                                     )
                    );
$smarty->assign('export_csv_table_cols',2);

$smarty->assign('options_box_width','550px');

$smarty->assign('csv_export_options',$csv_export_options);
$smarty->display('warehouses.tpl');

?>
