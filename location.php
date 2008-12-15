<?
include_once('common.php');
include_once('classes/Location.php');

$view_sales=$LU->checkRight(PROD_SALES_VIEW);
$view_stock=$LU->checkRight(PROD_STK_VIEW);
$view_orders=$LU->checkRight(ORDER_VIEW);

$create=$LU->checkRight(PROD_CREATE);
$modify=$LU->checkRight(PROD_MODIFY);
$modify_stock=$LU->checkRight(PROD_STK_MODIFY);
$smarty->assign('modify_stock',$modify_stock);
$view_suppliers=$LU->checkRight(SUP_VIEW);
$view_cust=$LU->checkRight(CUST_VIEW);
$smarty->assign('view_suppliers',$view_suppliers);
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('view_orders',$view_orders);
$smarty->assign('view_customers',$view_cust);

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
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
		$yui_path.'animation/animation-min.js',
		$yui_path.'datasource/datasource-min.js',	
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js.php',
		'js/search.js',
		'js/table_common.js.php',
		);



if(!isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
  $location_id=1;
else
  $location_id=$_REQUEST['id'];
$_SESSION['state']['location']['id']=$location_id;


$location= new location($location_id);



//print_r($location->data);
$warehouse_order=$_SESSION['state']['warehouse']['locations']['order'];
$sql=sprintf("select id,name as code from location where  %s<'%s'  order by %s desc  ",$warehouse_order,$location->data[$warehouse_order],$warehouse_order);
$result =& $db->query($sql);
if(!$prev=$result->fetchRow())
  $prev=array('id'=>0,'code'=>'');
$sql=sprintf("select id,name as code from location where  %s>'%s'   order by %s   ",$warehouse_order,$location->data[$warehouse_order],$warehouse_order);
//print "$sql";
$result =& $db->query($sql);
if(!$next=$result->fetchRow())
  $next=array('id'=>0,'code'=>'');

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);


$location->load('product');
$smarty->assign('has_stock',$location->get('has_stock'));
//print_r($locations);
$smarty->assign('products',$location->items);
$smarty->assign('num_products',count($location->items));

$smarty->assign('parent','warehouse.php');
$smarty->assign('title',_('Location ').$location->data['name']);




$js_files[]='js/location.js.php';

$smarty->assign('data',$location->data);
$smarty->assign('id',$location->id);

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);





$smarty->display('location.tpl');
?>