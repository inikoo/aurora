<?php
include_once('common.php');
include_once('class.Warehouse.php');

$smarty->assign('box_layout','yui-t0');

if(isset($_REQUEST['warehouse_id']))
  $wid=$_REQUEST['warehouse_id'];
else
  $wid=$_SESSION['state']['warehouse']['id'];
$warehouse=new warehouse($wid);

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		
		 'button.css',
		 'css/container.css'
		 );

if($common)
{
array_push($css_files, 'themes_css/'.$common);   

array_push($css_files, 'themes_css/'.$row['Themes css2']); 
}    

else{
array_push($css_files, 'common.css'); 

array_push($css_files, 'table.css');
}



$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/edit_common.js',
		'new_warehouse_area.js.php'
		);




$smarty->assign('parent','warehouses');
$smarty->assign('title', _('New Warehouse Area'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$used_for=array(
		'Picking'=>array('selected'=>true,'name'=>_('Picking'))
		,'Storing'=>array('selected'=>false,'name'=>_('Storing'))
		,'Displaying'=>array('selected'=>false,'name'=>_('Displaying'))
		,'Loading'=>array('selected'=>false,'name'=>_('Loading'))
		);
$shape_type=array(
		'Box'=>array('selected'=>true,'name'=>_('Box'))
		,'Cylinder'=>array('selected'=>false,'name'=>_('Cylinder'))

		);


$smarty->assign('warehouse',$warehouse);

$smarty->assign('used_for',$used_for);
$smarty->assign('shape_type',$shape_type);

$smarty->display('new_warehouse_area.tpl');
?>
