<?
include_once('common.php');
include_once('stock_functions.php');

$view_sales=$LU->checkRight(PROD_SALES_VIEW);
$view_stock=$LU->checkRight(PROD_STK_VIEW);
$create=$LU->checkRight(PROD_CREATE);
$modify=$LU->checkRight(PROD_MODIFY);
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);






$_SESSION['views']['assets']='index';

if(isset($_REQUEST['vt']) and is_numeric($_REQUEST['vt']) and $_REQUEST['vt']>=0 and $_REQUEST['vt']<3)
  $_SESSION['views']['assets_tables']=$_REQUEST['vt'];

//print $_SESSION['views']['assets_tables'];

//print ($_SESSION['views']['assets_tables']==0?0:1);
$smarty->assign('view_table',$_SESSION['views']['assets_view']);



//update_department_all();

$sql="select count(*) as numberof,sum(stock_value) as stock_value,sum(tsall) as total_sales  from product_department";
$result =& $db->query($sql);
if(!$departments=$result->fetchRow())
  exit;


$sql="select count(*) as numberof from product_group";
$result =& $db->query($sql);
$families=$result->fetchRow();
$sql="select count(*) as numberof from product";
$result =& $db->query($sql);
$products=$result->fetchRow();

$smarty->assign('stock_value',money($departments['stock_value']));
$smarty->assign('total_sales',money($departments['total_sales']));

$smarty->assign('departments',number($departments['numberof']));
$smarty->assign('families',number($families['numberof']));
$smarty->assign('products',number($products['numberof']));





$smarty->assign('box_layout','yui-t4');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 //	 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'yahoo-dom-event/yahoo-dom-event.js',
		$yui_path.'connection/connection-min.js',
		$yui_path.'json/json-min.js',
		$yui_path.'element/element-beta-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',

		'js/common.js.php',
		'js/table_common.js.php',
		'js/assets_tree.js.php?departments='.$departments['numberof']
		);




$smarty->assign('parent','assets_tree.php');
$smarty->assign('title', _('Product Departments'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('t_title',_('Departments'));



$smarty->assign('total_departments',$departments['numberof']);
//$smarty->assign('rpp',$_SESSION['tables']['pindex_list'][2]);

//$smarty->assign('products_perpage',$_SESSION['tables']['pindex_list'][2]);


$smarty->display('assets_tree.tpl');
?>