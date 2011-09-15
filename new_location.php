<?php
include_once('common.php');
include_once('class.Warehouse.php');


if(isset($_REQUEST['auto']))
	$auto=1;
else
	$auto=0;
	


$smarty->assign('box_layout','yui-t0');
$warehouse=new warehouse($_SESSION['state']['warehouse']['id']);

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		
		 'button.css',
		 'container.css'
		 );

include_once('Theme.php');

$general_options_list=array();

$general_options_list[]=array('tipo'=>'url','url'=>'http://localhost/kaktus/edit_warehouse.php','label'=>_('Back'));


$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Locations'));
$smarty->assign('search_scope','locations');
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
		'js/raphael.js',
		'new_location.js.php?auto='.$auto,
		'js/search.js'
		
		);




$smarty->assign('parent','warehouses');
$smarty->assign('title', _('New Location'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$used_for='Picking';
$used_for_list=array(
		'Picking'=>array('selected'=>true,'name'=>_('Picking'))
		,'Storing'=>array('selected'=>false,'name'=>_('Storing'))
		,'Displaying'=>array('selected'=>false,'name'=>_('Displaying'))
		,'Loading'=>array('selected'=>false,'name'=>_('Loading'))
		);
$shape_type='Box';
$shape_type_list=array(
		'Box'=>array('selected'=>true,'name'=>_('Box'))
		,'Cylinder'=>array('selected'=>false,'name'=>_('Cylinder'))

		);


$smarty->assign('warehouse',$warehouse);
$smarty->assign('used_for',$used_for);
$smarty->assign('shape_type',$shape_type);
$smarty->assign('used_for_list',$used_for_list);
$smarty->assign('shape_type_list',$shape_type_list);

$smarty->display('new_location.tpl');
?>
