<?
include_once('common.php');
include_once('classes/Family.php');

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
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-debug.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/search.js',
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



$family=new Family($family_id);

if(isset($_REQUEST['department_id']) and $_REQUEST['department_id']>0){
  $department_id=$_REQUEST['department_id'];
  $order=$_SESSION['state']['department']['table']['order'];
  if($order=='per_tsall' or $order=='tsall')
    $order='total_sales';
  if($order=='per_tsm' or $order=='tms')
    $order='month_sales';
  if($order=='code')
    $order='Product Family Code';
  if($order=='name')
    $order='Product Family Name';
  if($order=='active')
    $order='Product Family For Sale Products';
  if($order=='outofstock')
    $order='Product Family Out Of Stock Products';
  if($order=='stockerror')
    $order='Product Family Unknown Stock Products';
  




$sql=sprintf("select  F.`Product Family Key` as id, `Product Family Code` as code  from `Product Family Dimension`   F left join `Product Family Department Bridge` FD on (FD.`Product Family Key`=F.`Product Family Key`) where  `%s`<'%s' and `Product Department Key`=%d  order by `%s` desc  ",$order,$family->get($order),$department_id,$order);


$res = mysql_query($sql);
if(!$prev=mysql_fetch_array($res, MYSQL_ASSOC))
  $prev=array('id'=>0,'code'=>'');

$sql=sprintf("select F.`Product Family Key` as id, `Product Family Code` as code   from `Product Family Dimension`   F left join `Product Family Department Bridge`  FD on (FD.`Product Family Key`=F.`Product Family Key`)  where  `%s`>'%s' and `Product Department Key`=%d order by `%s`   ",$order,$family->get($order),$department_id,$order);

$res = mysql_query($sql);

if(!$next=mysql_fetch_array($res, MYSQL_ASSOC))
  $next=array('id'=>0,'code'=>'');



 


$smarty->assign('prev',$prev);
$smarty->assign('next',$next);

 }

$smarty->assign('parent','departments.php');
$smarty->assign('title',$family->get('Product Family Code').' - '.$family->get('Product Family Name'));


$product_home="Products Home";
$smarty->assign('home',$product_home);
// $smarty->assign('department',$family->get('department'));
// $smarty->assign('department_id',$family->data['department_id']);
// $smarty->assign('products',$family->get('product_numbers'));
// $smarty->assign('data',$family->data);




 $smarty->assign('family',$family);
// $smarty->assign('family_id',$family->id);

// $smarty->assign('family_description',$family->data['description']);

// $smarty->assign('units_tipo',$_units_tipo);


$smarty->assign('filter','code');
$smarty->assign('filter_name',_('Product code'));
$smarty->assign('filter_value',$_SESSION['tables']['products_list'][7]);



$smarty->assign('view',$_SESSION['state']['family']['view']);
$smarty->assign('show_details',$_SESSION['state']['family']['details']);
$table_title=_('Product List');
$smarty->assign('table_title',$table_title);



$smarty->display('family.tpl');
?>