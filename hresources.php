<?
include_once('common.php');
$_SESSION['views']['assets']='index';

$sql="select count(*) as numberof from product";
$result =& $db->query($sql);
if(!$products=$result->fetchRow())
  exit;


$smarty->assign('box_layout','yui-t4');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'yahoo-dom-event/yahoo-dom-event.js',
		$yui_path.'element/element-beta-min.js',
		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		$yui_path.'autocomplete/autocomplete.js',
		$yui_path.'datasource/datasource-beta.js',
		$yui_path.'datatable/datatable-beta.js',
		$yui_path.'json/json-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/hresources.js.php'
		);




$smarty->assign('parent','customers.php');
$smarty->assign('title', _('Customers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Staff List'));


//$smarty->assign('total_products',$products['numberof']);
//$smarty->assign('rpp',$_SESSION['tables']['pindex_list'][2]);
//$smarty->assign('products_perpage',$_SESSION['tables']['pindex_list'][2]);


$smarty->display('hresources.tpl');
?>