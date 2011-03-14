<?php
include_once('common.php');
if(!$user->can_view('customers') ){
header('Location: index.php');
   exit;
}
//$modify=$user->can_edit('staff');
$general_options_list=array();
if(isset($_REQUEST['id']))
$id=$_REQUEST['id'];
else{
  header('Location: index.php?error=no_id_in_customers_list');
    exit;

}


$sql=sprintf("select * from `Customer List Dimension` where `Customer List Key`=%d",$id);

$res=mysql_query($sql);
if(!$customer_list_data=mysql_fetch_assoc($res)){
 header('Location: index.php?error=id_in_customers_list_not_found');
    exit;

}
$store=new Store($customer_list_data['Customer List Store Key']);


$static_list_name=$customer_list_data['Customer List Name'];
$smarty->assign('static_list_name',$static_list_name);
$smarty->assign('static_list_id',$customer_list_data['Customer List Key']);



$general_options_list[]=array('tipo'=>'js','id'=>'export_data','label'=>_('Export Data(CSV)'));
 $general_options_list[]=array('tipo'=>'url','url'=>'pdf_customer_list.php?id='.$id,'label'=>_('Print Address Label'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers_lists.php?store='.$store->id,'label'=>_('Customers Lists'));
  $general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));

$smarty->assign('general_options_list',$general_options_list);
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 'common.css',
		 'container.css',
		 'table.css'
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
              
		
		'common.js.php',
		'table_common.js.php',
		'js/edit_common.js',
		'js/csv_common.js',
		'common_customers.js.php',
		'customers_list.js.php?id='.$id
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','customers');
//$smarty->assign('sub_parent','areas');
$smarty->assign('view',$_SESSION['state']['customers']['view']);

$smarty->assign('title', _('Customer Static List'));

/*$tipo_filter=$_SESSION['state']['hr']['staff']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['hr']['areas']['f_value']);
$smarty->assign('view',$_SESSION['state']['hr']['view']);
$filter_menu=array(
		   'name'=>array('db_key'=>'areas.alias','menu_label'=>'areas name <i>*x*</i>','label'=>'Name'),
		   'position_id'=>array('db_key'=>'position_id','menu_label'=>'Position Id','label'=>'Position Id'),
		   'area_id'=>array('db_key'=>'area_id','menu_label'=>'Area Id','label'=>'Area Id'),
		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
*/

/* $csv_export_options=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'id'=>array('label'=>_('Id'),'selected'=>$_SESSION['state']['staff']['company_areas']['csv_export']['id']),
                                                             'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['staff']['company_areas']['csv_export']['code']),
                                                             'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['staff']['company_areas']['csv_export']['name']),
                                                             'description'=>array('label'=>_('Description'),'selected'=>$_SESSION['state']['staff']['company_areas']['csv_export']['description']),
                                                             
                                                
                                                   )
                            )
                            ),


'Other Details'=>array(
                                              'title'=>_('Other Details'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'number_of_department'=>array('label'=>_('No. Of Department'),'selected'=>$_SESSION['state']['staff']['company_areas']['csv_export']['number_of_department']),
                                                             'number_of_position'=>array('label'=>_('No. Of Position'),'selected'=>$_SESSION['state']['staff']['company_areas']['csv_export']['number_of_position']),
                                                             'number_of_employee'=>array('label'=>_('No. Of Employee'),'selected'=>$_SESSION['state']['staff']['company_areas']['csv_export']['number_of_employee']),
                                                            
                                                             
                                                
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols',2);

                     
$smarty->assign('csv_export_options',$csv_export_options);*/
$smarty->display('customers_list.tpl');
?>
