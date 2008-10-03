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


$_SESSION['views']['assets']='index';

if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
  $family_id=1;
 else
   $family_id=$_REQUEST['id'];
	      

if(isset($_REQUEST['vt']) and is_numeric($_REQUEST['vt']) and $_REQUEST['vt']>=0 and $_REQUEST['vt']<3)
  $_SESSION['views']['assets_tables']=$_REQUEST['vt'];
$smarty->assign('view_table',$_SESSION['views']['assets_tables']);
$smarty->assign('hide_first',($_SESSION['views']['assets_tables']==0?0:1));

$sql=sprintf("select 
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
//print $sql;
$result =& $db->query($sql);
if(!$families=$result->fetchRow())
  exit;
$_SESSION['tables']['products_list'][4]=$family_id;



//get previoues
$department_order=$_SESSION['tables']['families_list'][0];

$sql=sprintf("select g.id,g.name as code from product_group as g  where  %s<'%s' and  department_id=%d order by %s desc  ",$department_order,$families[$department_order],$families['department_id'],$department_order);

$result =& $db->query($sql);

if(!$prev=$result->fetchRow())
  $prev=array('id'=>0,'code'=>'');
$sql=sprintf("select id,name as code from product_group where  %s>'%s' and department_id=%d order by %s   ",$department_order,$families[$department_order],$families['department_id'],$department_order);

$result =& $db->query($sql);
if(!$next=$result->fetchRow())
  $next=array('id'=>0,'code'=>'');

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);








$smarty->assign('box_layout','yui-t4');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		 'container.css',
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
		'js/assets_family.js.php'
		);




$smarty->assign('parent','assets_tree.php');
$smarty->assign('title',$families['family']);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$families['department']);
$smarty->assign('department_id',$families['department_id']);
$smarty->assign('products',$families['products']);

$smarty->assign('family',$families['family']);
$smarty->assign('family_id',$family_id);

$smarty->assign('family_description',$families['description']);

$smarty->assign('units_tipo',$_units_tipo);


$smarty->assign('filter','code');
$smarty->assign('filter_name',_('Product code'));
$smarty->assign('filter_value',$_SESSION['tables']['products_list'][7]);


$sql="select id,code from supplier  order by code";
$result =& $db->query($sql);

$asuppliers=array();
while($row=$result->fetchRow()){
  $asuppliers[$row['id']]=$row['code'];
  
 }

$smarty->assign('asuppliers',$asuppliers);

$smarty->display('assets_family.tpl');
?>