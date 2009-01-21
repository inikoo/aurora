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
		'js/search.js',
		'js/departments.js.php',
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$_SESSION['state']['assets']['page']='departments';
if(isset($_REQUEST['view'])){
  $valid_views=array('sales','general','stoke');
  if (in_array($_REQUEST['view'], $valid_views)) 
    $_SESSION['state']['departments']['view']=$_REQUEST['view'];

 }
$smarty->assign('view',$_SESSION['state']['departments']['view']);
$smarty->assign('show_details',$_SESSION['state']['departments']['details']);


//$sql="select id from product";
//$result =& $db->query($sql);

// include_once('classes/product.php');
// while($row=$result->fetchRow()){
//   $product= new product($row['id']);
//   $product->set_stock();
// }

 $table_title=_('Department List');
//  $sql="select count(*) as numberof ,sum(tsall) as total_sales,sum(stock_value) as stock_value  from product_department left join sales on (tipo_id=product_department.id) and tipo='dept' ";
//  $result =& $db->query($sql);
//  if(!$departments=$result->fetchRow())
//    exit;



// //$smarty->assign('table_info',$departments['numberof'].' '.ngettext('Department','Departments',$departments['numberof']));
// $sql="select count(*) as numberof from product_group";
// $result =& $db->query($sql);
// $families=$result->fetchRow();
// $sql="select count(*) as numberof from product";
// $result =& $db->query($sql);
// $products=$result->fetchRow();





// $smarty->assign('stock_value',money($departments['stock_value']));
// $smarty->assign('total_sales',money($departments['total_sales']));
// $smarty->assign('departments',number($departments['numberof']));
// $smarty->assign('families',number($families['numberof']));
// $smarty->assign('products',number($products['numberof']));

$smarty->assign('parent','departments.php');
$smarty->assign('title', _('Product Departments'));
//$smarty->assign('total_departments',$departments['numberof']);
$smarty->assign('table_title',$table_title);

$smarty->display('departments.tpl');

?>