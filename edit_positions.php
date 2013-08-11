<?php
include_once('common.php');
if(!$user->can_view('staff')){
   header('Location: index.php');
   exit;
}

if( !$user->can_edit('staff')){
 header('Location: hr.php');
   exit;
}

$sql="select `Account Company Key` from `Account Dimension` ";
$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){
   $company_key=$row['Account Company Key'];
}else{
       header('Location: new_corporation.php');
       exit;

}

mysql_free_result($res);
$general_options_list=array();


  $general_options_list[]=array('tipo'=>'url','url'=>'company_departments.php','label'=>_('Exit Edit'));


$smarty->assign('general_options_list',$general_options_list);





$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		  $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',

		 
		 'css/container.css',
		
		 'css/edit.css'
		 
		 );

$css_files[]='theme.css.php';





$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/edit_common.js',
		'edit_positions.js.php?company_key='.$company_key
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','staff');
$smarty->assign('sub_parent','positions');

$smarty->assign('title', _('Company Positions'));

$tipo_filter=$_SESSION['state']['hr']['staff']['f_field'];

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

$smarty->assign('edit','positions');


$smarty->display('edit_positions.tpl');
?>
