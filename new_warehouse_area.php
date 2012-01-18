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
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
               'css/edit',
               'theme.css.php'
		 );




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


$smarty->assign('warehouse','warehouse');
$smarty->assign('search_label',_('Locations'));
$smarty->assign('search_scope','locations');

$smarty->assign('parent','warehouses');
$smarty->assign('title', _('New Warehouse Area'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$tipo_filter=$_SESSION['state']['locations']['table']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['locations']['table']['f_value']);

$filter_menu=array(
		   'code'=>array('db_key'=>_('code'),'menu_label'=>'Location Code','label'=>'Code'),
		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

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
