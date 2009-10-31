<?php
include_once('common.php');
include_once('class.Warehouse.php');

$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css',
		 'css/edit.css'
		 );
$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'common.js.php',
		'table_common.js.php',
		'js/edit_common.js',
		
		'edit_warehouse.js.php'
		);


 
 
$smarty->assign('parent','warehouse.php');
$smarty->assign('title', _('Warehouse'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Location List'));


$warehouse=new warehouse($_SESSION['state']['warehouse']['id']);


$tipo_filter=$_SESSION['state']['warehouse']['locations']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['warehouse']['locations']['f_value']);

$filter_menu=array(
		   'code'=>array('db_key'=>_('code'),'menu_label'=>'Location Code','label'=>'Code'),
		   );
$smarty->assign('filter_menu',$filter_menu);
$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);



$smarty->assign('warehouse',$warehouse);
//print_r($warehouse->get('areas'));

$smarty->assign('paginator_menu',$paginator_menu);


$smarty->display('edit_warehouse.tpl');
?>