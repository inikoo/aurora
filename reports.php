<?
include_once('common.php');
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'container.css',
		 'table.css'
		 );
$js_files=array(

		$yui_path.'yahoo-dom-event/yahoo-dom-event.js',
		$yui_path.'calendar/calendar-min.js',
		
		$yui_path.'element/element-beta-min.js',
		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		$yui_path.'datasource/datasource-beta.js',
		$yui_path.'datatable/datatable-beta.js',
		$yui_path.'charts/charts-experimental-min.js',
		$yui_path.'json/json-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/reports.js.php'
		);


$smarty->assign('plot_tipo','net_sales_month');

$smarty->assign('parent','reports.php');
$smarty->assign('title', _('Reports'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('year',date('Y'));
$smarty->assign('month',date('m'));
$smarty->assign('month_name',date('F'));

$smarty->assign('week',date('W'));
$smarty->assign('from',date('d-m-Y'));
$smarty->assign('to',date('d-m-Y'));


$smarty->display('reports.tpl');

?>

