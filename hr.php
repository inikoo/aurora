<?php
include_once('common.php');
if(!$user->can_view('staff')){
   header('Location: index.php');
   exit;
}

$modify=$user->can_edit('staff');

$general_options_list=array();

   $general_options_list[]=array('tipo'=>'url','url'=>'staff_holidays.php','label'=>_('Holidays'));


$smarty->assign('general_options_list',$general_options_list);


$staff_options_list=array();


if($modify){
  $staff_options_list[]=array('tipo'=>'url','url'=>'edit_staff.php','label'=>_('Edit Staff'));
   $staff_options_list[]=array('tipo'=>'url','url'=>'new_staff.php','label'=>_('Add Staff'));
      $staff_options_list[]=array('tipo'=>'url','url'=>'import_csv.php?subject=staff','label'=>_('Import Staff'));

}
  


$smarty->assign('staff_options_list',$staff_options_list);


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		                $yui_path.'assets/skins/sam/autocomplete.css',

		 'container.css'
		 );

include_once('Theme.php');



$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js','js/search.js',
		'js/table_common.js','js/edit_common.js','js/csv_common.js',
		'hr.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','staff');
$smarty->assign('sub_parent','hr');

$smarty->assign('title', _('Staff'));

$tipo_filter=$_SESSION['state']['hr']['staff']['f_field'];

$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['hr']['staff']['f_value']);

$smarty->assign('block_view',$_SESSION['state']['hr']['view']);
$smarty->assign('staff_view',$_SESSION['state']['hr']['staff']['view']);

$filter_menu=array(
		   'name'=>array('db_key'=>'staff.alias','menu_label'=>'Staff name <i>*x*</i>','label'=>'Name'),
		   'position_id'=>array('db_key'=>'position_id','menu_label'=>'Position Id','label'=>'Position Id'),
		   'area_id'=>array('db_key'=>'area_id','menu_label'=>'Area Id','label'=>'Area Id'),
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
                                                             'id'=>array('label'=>_('Id'),'selected'=>$_SESSION['state']['staff']['table']['csv_export']['id']),
                                                             'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['staff']['table']['csv_export']['name']),
                                                             'alias'=>array('label'=>_('Alias'),'selected'=>$_SESSION['state']['staff']['table']['csv_export']['alias']),
                                                             'position'=>array('label'=>_('Position'),'selected'=>$_SESSION['state']['staff']['table']['csv_export']['position']),
                                                             
                                                
                                                   )
                            )
                            ),


'Other Details'=>array(
                                              'title'=>_('Other Details'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'valid_from'=>array('label'=>_('Valid From'),'selected'=>$_SESSION['state']['staff']['table']['csv_export']['valid_from']),
                                                             'valid_to'=>array('label'=>_('Valid To'),'selected'=>$_SESSION['state']['staff']['table']['csv_export']['valid_to']),
                                                             'description'=>array('label'=>_('Position Description'),'selected'=>$_SESSION['state']['staff']['table']['csv_export']['description']),
                                                            
                                                             
                                                
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols',2);

                     
$smarty->assign('csv_export_options',$csv_export_options);


$smarty->assign('search_label',_('Staff'));
$smarty->assign('search_scope','staff');

$smarty->display('hr.tpl');
?>
