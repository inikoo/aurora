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
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/search_product.js',
		'js/department.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


if(isset($_REQUEST['view'])){
  $valid_views=array('sales','general','stoke');
  if (in_array($_REQUEST['view'], $valid_views)) 
    $_SESSION['state']['department']['view']=$_REQUEST['view'];

 }

if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']) )
  $_REQUEST['id']=1;
$department_id=$_REQUEST['id'];
$_SESSION['state']['department']['id']=$_REQUEST['id'];


$sql=sprintf("select 
count(*) as families,
product_department.name as department,
product_department.code as department_code,

product_department.id as department_id,
product_department.tsq,
product_department.tsm,
product_department.tsy,
product_department.tsall,
product_department.stock_value,
product_department.name,
product_department.products
from product_group left join product_department on (product_department.id=department_id) where department_id=%d group by department_id",$department_id);

$result =& $db->query($sql);
$families=$result->fetchRow();
  



$families_order=$_SESSION['state']['departments']['table']['order'];
$sql=sprintf("select id,name as code from product_department  where  %s<'%s' order by %s desc  ",$families_order,$families[$families_order],$families_order);
$result =& $db->query($sql);
if(!$prev=$result->fetchRow())
  $prev=array('id'=>0,'code'=>'');
$sql=sprintf("select id,name as code  from product_department  where  %s>'%s' order by %s   ",$families_order,$families[$families_order],$families_order);

$result =& $db->query($sql);
if(!$next=$result->fetchRow())
  $next=array('id'=>0,'code'=>'');

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);



$smarty->assign('parent','departments.php');
$smarty->assign('title', _('Product Families'));
$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$families['department']);
$smarty->assign('department_id',$_REQUEST['id']);
$smarty->assign('products',$families['products']);

$smarty->assign('filter',$_SESSION['state']['department']['table']['f_field']);
$smarty->assign('filter_value',$_SESSION['state']['department']['table']['f_value']);
$smarty->assign('filter_name',_('Family code'));
$smarty->assign('view',$_SESSION['state']['department']['view']);
$smarty->assign('show_details',$_SESSION['state']['department']['details']);
$table_title=_('Family List');
$smarty->assign('table_title',$table_title);
$smarty->assign('table_info',$families['families'].' '.ngettext('Families','Families',$families['families']).' '._('in').' '.$families['department']);

$smarty->display('department.tpl');
?>