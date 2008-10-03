<?
include_once('common.php');


$sql="select count(*) as numberof from supplier";
$result =& $db->query($sql);
if(!$suppliers=$result->fetchRow())
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
		'js/suppliers.js.php'
		);




$smarty->assign('parent','suppliers.php');
$smarty->assign('title', _('Suppliers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$smarty->assign('total_suppliers',$suppliers['numberof']);




$smarty->assign('filter0',$_SESSION['tables']['suppliers_list'][5]);
$smarty->assign('filter_value0',$_SESSION['tables']['suppliers_list'][6]);

switch($_SESSION['tables']['suppliers_list'][5]){
 case('code'):
   $filter_text=_('Supplier Code');
   break;
 case('name'):
   $filter_text=_('Supplier Name');
   break;
 case('low'):
   $filter_text=_('Low stock');
   break;
 case('outofstock'):
   $filter_text=_('Out of Stock');
   break;
 default:
   $filter_text='?';
 }

$smarty->assign('filter_name0',$filter_text);


$smarty->assign('t_title0',_('Suppliers List'));




$smarty->display('suppliers.tpl');
?>