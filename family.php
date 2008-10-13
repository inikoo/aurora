<?
include_once('common.php');

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
		$yui_path.'datatable/datatable-debug.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/family.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
  $family_id=1;
 else
   $family_id=$_REQUEST['id'];
$_SESSION['state']['family']['id']=$_REQUEST['id'];

$_SESSION['state']['assets']['page']='department';
if(isset($_REQUEST['view'])){
  $valid_views=array('sales','general','stoke');
  if (in_array($_REQUEST['view'], $valid_views)) 
    $_SESSION['state']['family']['view']=$_REQUEST['view'];

 }



$sql=sprintf("select 
(select count(*) from product where group_id=g.id) as products,
d.name as department,
g.products,
g.name as family,
g.name as name,
g.stock_value,
g.outofstock
,g.tsq
,g.tsy
,g.tsall
,g.tsm
,g.products
,g.active
,g.description, department_id from product_group as g left join product_department as d on (department_id=d.id) where g.id=%d",$family_id);

$result =& $db->query($sql);
if(!$family=$result->fetchRow())
  exit;

//get previoues
$families_order=$_SESSION['state']['department']['table']['order'];
$sql=sprintf("select g.id,g.name as code from product_group as g  where  %s<'%s' and  department_id=%d order by %s desc  ",$families_order,$family[$families_order],$family['department_id'],$families_order);
$result =& $db->query($sql);

if(!$prev=$result->fetchRow())
  $prev=array('id'=>0,'code'=>'');
$sql=sprintf("select id,name as code from product_group where  %s>'%s' and department_id=%d order by %s   ",$families_order,$family[$families_order],$family['department_id'],$families_order);

$result =& $db->query($sql);
if(!$next=$result->fetchRow())
  $next=array('id'=>0,'code'=>'');

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);






$smarty->assign('parent','assets_tree.php');
$smarty->assign('title',$family['family'].' - '.$family['description']);


$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$family['department']);
$smarty->assign('department_id',$family['department_id']);
$smarty->assign('products',$family['products']);

$smarty->assign('family',$family['family']);
$smarty->assign('family_id',$family_id);

$smarty->assign('family_description',$family['description']);

$smarty->assign('units_tipo',$_units_tipo);


$smarty->assign('filter','code');
$smarty->assign('filter_name',_('Product code'));
$smarty->assign('filter_value',$_SESSION['tables']['products_list'][7]);



$smarty->assign('view_table',$_SESSION['state']['family']['view']);
$smarty->assign('show_details',$_SESSION['state']['family']['details']);
$table_title=_('Product List');
$smarty->assign('table_title',$table_title);
$smarty->assign('table_info',$family['products'].' '.ngettext('Product','Products',$family['products']).' '._('in').' '.$family['name']);


$smarty->display('family.tpl');
?>