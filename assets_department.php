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


if(isset($_REQUEST['vt']) and is_numeric($_REQUEST['vt']) and $_REQUEST['vt']>=0 and $_REQUEST['vt']<3)
  $_SESSION['views']['assets_tables']=$_REQUEST['vt'];
$smarty->assign('view_table',$_SESSION['views']['assets_tables']);
$smarty->assign('hide_first',($_SESSION['views']['assets_tables']==0?0:1));




if(!isset($_REQUEST['id']))
  $_REQUEST['id']=1;
$sql=sprintf("select 
count(*) as families,

product_department.name as department,
product_department.id as department_id,
product_department.tsq,
product_department.tsm,
product_department.tsy,
product_department.tsall,
product_department.stock_value,
product_department.name,
product_department.products


from product_group left join product_department on (product_department.id=department_id) where department_id=%d group by department_id",$_REQUEST['id']);


$result =& $db->query($sql);

$families=$result->fetchRow();
  




$_SESSION['tables']['families_list'][4]=$_REQUEST['id'];




$families_order=$_SESSION['tables']['departments_list'][0];

$sql=sprintf("select id from product_department where  %s<'%s' order by %s desc  ",$families_order,$families[$families_order],$families_order);

$result =& $db->query($sql);

if(!$prev=$result->fetchRow())
  $prev=array('id'=>0,'code'=>'');
$sql=sprintf("select id  from product_department where  %s>'%s' order by %s   ",$families_order,$families[$families_order],$families_order);

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
		'js/assets_department.js.php?id='.$_REQUEST['id'].'&families='.$families['families']
		);




$smarty->assign('parent','assets_tree.php');
$smarty->assign('title', _('Product Families'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$families['department']);
$smarty->assign('department_id',$_REQUEST['id']);
$smarty->assign('products',$families['products']);


$smarty->assign('filter','name',$_SESSION['tables']['families_list'][6]);
$smarty->assign('filter_value',$_SESSION['tables']['families_list'][7]);

$smarty->assign('filter_name',_('Family code'));

$smarty->display('assets_department.tpl');
?>