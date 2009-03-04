<?
include_once('common.php');
include_once('classes/Department.php');

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

$department=new Department($department_id);


$order=$_SESSION['state']['departments']['table']['order'];
$period=$_SESSION['state']['departments']['period'];
 if($order=='profit'){
    if($period=='all')
      $order='`Product Department Total Profit`';
    elseif($period=='year')
      $order='`Product Department 1 Year Acc Profit`';
    elseif($period=='quarter')
      $order='`Product Department 1 Quarter Acc Profit`';
    elseif($period=='month')
      $order='`Product Department 1 Month Acc Profit`';
    elseif($period=='week')
      $order='`Product Department 1 Week Acc Profit`';
  }elseif($order=='sales'){
    if($period=='all')
      $order='`Product Department Total Invoiced Amount`';
    elseif($period=='year')
      $order='`Product Department 1 Year Acc Invoiced Amount`';
    elseif($period=='quarter')
      $order='`Product Department 1 Quarter Acc Invoiced Amount`';
    elseif($period=='month')
      $order='`Product Department 1 Month Acc Invoiced Amount`';
    elseif($period=='week')
      $order='`Product Department 1 Week Acc Invoiced Amount`';

  }
  elseif($order=='name')
    $order='`Product Department Name`';
  elseif($order=='families')
    $order='`Product Department Families`';
  elseif($order=='active')
    $order='`Product Department For Sale Products`';
  elseif($order=='outofstock')
    $order='`Product Department Out Of Stock Products`';
  elseif($order=='stockerror')
    $order='`Product Department Unknown Stock Products`';



$sql=sprintf("select `Product Department Key` as id,`Product Department Name` as code  from `Product Department Dimension` where  %s<'%s' order by %s desc  ",$order,$department->get(str_replace('`','',$order)),$order);

$result=mysql_query($sql);
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC)   )
  $prev=array('id'=>0,'code'=>'');
$sql=sprintf("select `Product Department Key` as id,`Product Department Name` as code  from `Product Department Dimension`   where    %s>'%s' order by %s   ",$order,$department->get(str_replace('`','',$order)),$order);

//print $sql;
$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC)   )
  $next=array('id'=>0,'code'=>'');

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);



$smarty->assign('parent','departments.php');
$smarty->assign('title', _('Families in').' '.$department->get('Product Department Name'));
$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$department);
// $smarty->assign('department_id',$_REQUEST['id']);
// $smarty->assign('products',$families['products']);

$smarty->assign('filter',$_SESSION['state']['department']['table']['f_field']);
$smarty->assign('filter_value',$_SESSION['state']['department']['table']['f_value']);
$smarty->assign('filter_name',_('Family code'));

$smarty->assign('view',$_SESSION['state']['department']['view']);
$smarty->assign('show_details',$_SESSION['state']['department']['details']);
$smarty->assign('show_percentages',$_SESSION['state']['department']['percentages']);
$smarty->assign('avg',$_SESSION['state']['department']['avg']);
$smarty->assign('period',$_SESSION['state']['department']['period']);


//$table_title=_('Family List');
//$smarty->assign('table_title',$table_title);
//$smarty->assign('table_info',$families['families'].' '.ngettext('Families','Families',$families['families']).' '._('in').' '.$families['department']);

$smarty->display('department.tpl');
?>